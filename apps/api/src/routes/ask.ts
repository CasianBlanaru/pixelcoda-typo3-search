import { Hono } from 'hono';
import { zValidator } from '@hono/zod-validator';
import { authMiddleware } from '../middleware/auth.js';
import { MeiliEngine } from '../engines/meili.js';
import { HybridRetriever } from '../lib/hybrid-retrieval.js';
import { CrossEncoderReranker } from '../lib/cross-encoder.js';
import { getTelemetryService } from '../lib/telemetry.js';
import { generateAnswer, rerank, embed } from '@pixelcoda/llm-adapter';
import { vectorSearch } from '../db.js';
import { askSchema } from '../schemas.js';
import { 
  createJsonApiResponse, 
  createJsonApiError, 
  transformCitationToResource,
  createHttpError 
} from '../utils/jsonapi.js';

export const router = new Hono();
const engine = new MeiliEngine(process.env.MEILI_URL || 'http://localhost:7700', process.env.MEILI_KEY);
const hybridRetriever = new HybridRetriever();
const crossEncoderReranker = new CrossEncoderReranker();
const telemetryService = getTelemetryService();

router.post('/ask/:project', 
  authMiddleware.requireKey('read'), 
  zValidator('json', askSchema), 
  async (c) => {
    try {
      const startTime = Date.now();
      const { project } = c.req.param();
      const { q, lang, collections, maxPassages, temperature, includeDebug } = await c.req.json();

      // Step 1: Use hybrid retrieval
      let passages: any[] = [];
      let searchMethod = 'hybrid';
      let useVectorSearch = false;
      
      if (process.env.ENABLE_HYBRID_RETRIEVAL === 'true') {
        const hybridResults = await hybridRetriever.retrieve({
          project,
          query: q,
          collections,
          limit: 50, // Get more for re-ranking
          language: lang,
          enableRerank: false // We'll use cross-encoder instead
        });
        
        passages = hybridResults.map(result => ({
          id: result.id,
          title: result.title,
          url: result.url,
          text: result.content,
          score: result.score,
          collection: result.collection,
          source: result.source
        }));
        
        console.log(`Hybrid retrieval found ${passages.length} passages for query: "${q}"`);
      } else {
        // Fallback to original logic
        useVectorSearch = process.env.ENABLE_VECTOR_SEARCH === 'true';

        if (useVectorSearch) {
          try {
            // Generate embedding for the query
            const queryEmbedding = await embed(q);
            
            // Vector similarity search
            const vectorResults = await vectorSearch(project, queryEmbedding, {
              collections,
              limit: Math.max(maxPassages * 2, 20),
              threshold: 0.5 // Minimum similarity threshold
            });

            passages = vectorResults.map(result => ({
              id: result.id,
              title: result.title || 'Dokument',
              url: result.url,
              text: result.content || '',
              score: result.similarity,
              collection: result.collection,
              source: 'vector'
            }));

            searchMethod = 'vector';
            console.log(`Vector search found ${passages.length} passages for query: "${q}"`);
          } catch (error) {
            console.warn('Vector search failed, falling back to keyword search:', error);
            useVectorSearch = false;
          }
        }

        // Step 2: Fallback to keyword search if vector search failed or is disabled
        if (!useVectorSearch || passages.length === 0) {
          const searchPayload = {
            q,
            limit: Math.max(maxPassages * 2, 20),
            filters: collections?.length ? { _collection: collections } : undefined
          };

          const searchResults = await engine.search(project, searchPayload);
          const hits = searchResults.hits || [];

          passages = hits.map((hit: any) => ({
            id: hit.id,
            title: hit.title || hit.document?.title || 'Dokument',
            url: hit.url || hit.document?.url,
            text: hit.content || hit.document?.content || hit._formatted?.content || hit.summary || '',
            score: hit._score || 0,
            collection: hit._collection || hit.collection,
            source: 'keyword'
          }));

          searchMethod = 'keyword';
          console.log(`Keyword search found ${passages.length} passages for query: "${q}"`);
        }
      }

      if (passages.length === 0) {
        return c.json({
          answer: 'Entschuldigung, ich konnte keine relevanten Informationen zu Ihrer Frage finden.',
          citations: [],
          meta: {
            passages_found: 0,
            response_time_ms: Date.now() - startTime,
            search_method: searchMethod
          }
        });
      }

      // Filter out empty passages
      const validPassages = passages.filter(p => p.text && p.text.length > 0);

      // Step 3: Re-rank passages with cross-encoder
      let rankedPassages = validPassages;
      if (process.env.ENABLE_CROSS_ENCODER === 'true' && validPassages.length > 1) {
        try {
          const rerankedResults = await crossEncoderReranker.rerank(
            q,
            validPassages.map(p => ({
              id: p.id,
              title: p.title,
              content: p.text,
              score: p.score
            })),
            maxPassages
          );
          
          rankedPassages = rerankedResults.map(r => ({
            ...validPassages.find(p => p.id === r.id),
            score: r.rerankScore
          }));
        } catch (error) {
          console.warn('Cross-encoder re-ranking failed, falling back to simple rerank:', error);
          // Fallback to simple reranking
          if (process.env.ENABLE_RERANKING === 'true') {
            try {
              rankedPassages = await rerank(q, validPassages);
            } catch (error2) {
              console.warn('Simple re-ranking also failed:', error2);
            }
          }
        }
      } else if (process.env.ENABLE_RERANKING === 'true' && validPassages.length > 1) {
        try {
          rankedPassages = await rerank(q, validPassages);
        } catch (error) {
          console.warn('Re-ranking failed, using original order:', error);
        }
      }

      // Step 4: Take top passages for context
      const topPassages = rankedPassages.slice(0, maxPassages);

      // Step 5: Generate grounded answer
      const contextText = topPassages
        .map((p, i) => `[${i + 1}] Titel: ${p.title}\nInhalt: ${p.text.slice(0, 800)}`)
        .join('\n\n');

      const prompt = `Beantworte die Frage präzise basierend auf den bereitgestellten Informationen. 
Verwende nur die gegebenen Quellen und zitiere sie mit [1], [2], etc.
Wenn die Informationen nicht ausreichen, sage das ehrlich.

Frage: ${q}

Verfügbare Informationen:
${contextText}

Antwort:`;

      const answer = await generateAnswer(prompt);

      // Step 6: Prepare citations
      const citations = topPassages.map((p, i) => ({
        id: p.id,
        title: p.title,
        url: p.url,
        snippet: p.text.slice(0, 200) + (p.text.length > 200 ? '...' : ''),
        collection: p.collection,
        reference: `[${i + 1}]`
      }));

      const responseTime = Date.now() - startTime;

      // Track telemetry
      await telemetryService.trackQuery({
        query: q,
        project_id: project,
        results_count: citations.length,
        response_time_ms: responseTime,
        language: lang,
        collections,
        user_agent: c.req.header('user-agent'),
        ip: c.req.header('x-forwarded-for') || c.req.header('x-real-ip'),
        timestamp: new Date()
      });
      
      // Log metrics (if enabled)
      if (process.env.ENABLE_METRICS === 'true') {
        console.log(`Ask: "${q}" in ${responseTime}ms, ${citations.length} citations`);
      }

      // Create JSON:API answer resource
      const answerResource = {
        type: 'answer',
        id: `answer-${Date.now()}`,
        attributes: {
          text: answer,
          query: q,
          language: lang,
          generated_at: new Date().toISOString(),
          confidence: calculateConfidence(topPassages),
          search_method: searchMethod
        },
        relationships: {
          citations: {
            data: citations.map((_, index) => ({
              type: 'citation',
              id: `citation-${index}`
            }))
          }
        },
        meta: {
          passages_found: validPassages.length,
          passages_used: topPassages.length,
          response_time_ms: responseTime,
          collections: collections || []
        }
      };

      // Transform citations to JSON:API resources for included section
      const citationResources = citations.map((citation, index) => 
        transformCitationToResource(citation, index)
      );

      // Create meta information
      const meta: Record<string, unknown> = {
        query: {
          text: q,
          language: lang,
          collections: collections || [],
          max_passages: maxPassages,
          temperature
        },
        generation: {
          response_time_ms: responseTime,
          search_method: searchMethod,
          passages_found: validPassages.length,
          passages_used: topPassages.length,
          citations_count: citations.length
        }
      };

      // Add debug information if requested
      if (includeDebug) {
        meta.debug = {
          search_method: searchMethod,
          passages_extracted: validPassages.length,
          reranking_enabled: process.env.ENABLE_RERANKING === 'true',
          vector_search_enabled: process.env.ENABLE_VECTOR_SEARCH === 'true',
          passages: topPassages.map(p => ({
            id: p.id,
            title: p.title,
            text_length: p.text?.length || 0,
            score: p.score,
            source: p.source
          }))
        };
      }

      // Return JSON:API 1.0 compliant response
      return c.json(createJsonApiResponse(answerResource, {
        included: citationResources,
        meta
      }));
    } catch (error) {
      console.error('Ask endpoint error:', error);
      
      const jsonApiError = createHttpError(500, 
        error instanceof Error ? error.message : 'Failed to generate answer'
      );
      
      return c.json(createJsonApiError(jsonApiError), 500);
    }
  }
);

// Helper function to calculate answer confidence
function calculateConfidence(passages: any[]): number {
  if (passages.length === 0) return 0;
  
  // Simple confidence calculation based on passage scores and count
  const avgScore = passages.reduce((sum, p) => sum + (p.score || 0), 0) / passages.length;
  const countFactor = Math.min(passages.length / 6, 1); // Normalize to max 6 passages
  
  return Math.round((avgScore * countFactor) * 100) / 100;
}

import { Hono } from 'hono';
import { zValidator } from '@hono/zod-validator';
import { authMiddleware } from '../middleware/auth.js';
import { MeiliEngine } from '../engines/meili.js';
import { generateAnswer, rerank, embed } from '@pixelcoda/llm-adapter';
import { vectorSearch } from '../db.js';
import { askSchema } from '../schemas.js';

export const router = new Hono();
const engine = new MeiliEngine(process.env.MEILI_URL || 'http://localhost:7700', process.env.MEILI_KEY);

router.post('/ask/:project', 
  authMiddleware.requireKey('read'), 
  zValidator('json', askSchema), 
  async (c) => {
    try {
      const startTime = Date.now();
      const { project } = c.req.param();
      const { q, lang, collections, maxPassages, temperature, includeDebug } = await c.req.json();

      // Step 1: Try vector search first (if pgvector is available)
      let passages: any[] = [];
      let useVectorSearch = process.env.ENABLE_VECTOR_SEARCH === 'true';

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

        console.log(`Keyword search found ${passages.length} passages for query: "${q}"`);
      }

      if (passages.length === 0) {
        return c.json({
          answer: 'Entschuldigung, ich konnte keine relevanten Informationen zu Ihrer Frage finden.',
          citations: [],
          meta: {
            passages_found: 0,
            response_time_ms: Date.now() - startTime,
            search_method: useVectorSearch ? 'vector' : 'keyword'
          }
        });
      }

      // Filter out empty passages
      const validPassages = passages.filter(p => p.text && p.text.length > 0);

      // Step 3: Re-rank passages (if enabled)
      let rankedPassages = validPassages;
      if (process.env.ENABLE_RERANKING === 'true' && validPassages.length > 1) {
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

      // Log metrics (if enabled)
      if (process.env.ENABLE_METRICS === 'true') {
        console.log(`Ask: "${q}" in ${responseTime}ms, ${citations.length} citations`);
      }

      const response: any = {
        answer,
        citations,
        meta: {
          query: q,
          language: lang,
          passages_found: validPassages.length,
          passages_used: topPassages.length,
          response_time_ms: responseTime,
          collections: collections || [],
          search_method: useVectorSearch ? 'vector' : 'keyword'
        }
      };

      if (includeDebug) {
        response.debug = {
          search_method: useVectorSearch ? 'vector' : 'keyword',
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

      return c.json(response);
    } catch (error) {
      console.error('Ask endpoint error:', error);
      return c.json({ 
        error: 'Failed to generate answer', 
        details: error instanceof Error ? error.message : 'Unknown error' 
      }, 500);
    }
  }
);
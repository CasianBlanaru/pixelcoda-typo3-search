/**
 * SSE Streaming endpoint for /v1/ask
 * Provides real-time streaming responses with strict grounding
 */

import { Hono } from 'hono';
import { streamSSE } from 'hono/streaming';
import { zValidator } from '@hono/zod-validator';
import { authMiddleware } from '../middleware/auth.js';
import { askSchema } from '../schemas.js';
import { HybridRetriever } from '../lib/hybrid-retrieval.js';
import { CrossEncoderReranker } from '../lib/cross-encoder.js';
import { OpenAI } from 'openai';
import { 
  createJsonApiResponse, 
  createJsonApiError,
  transformCitationToResource,
  createHttpError 
} from '../utils/jsonapi.js';

export const router = new Hono();

// Initialize components
const hybridRetriever = new HybridRetriever();
const reranker = new CrossEncoderReranker();

/**
 * SSE Streaming endpoint for ask queries
 */
router.post('/ask/:project/stream',
  authMiddleware.requireKey('read'),
  zValidator('json', askSchema),
  async (c) => {
    const startTime = Date.now();
    const { project } = c.req.param();
    const { q, lang, collections, maxPassages, temperature, includeDebug } = await c.req.json();

    // Set SSE headers
    c.header('Content-Type', 'text/event-stream');
    c.header('Cache-Control', 'no-cache');
    c.header('Connection', 'keep-alive');
    c.header('X-Accel-Buffering', 'no'); // Disable nginx buffering

    return streamSSE(c, async (stream) => {
      try {
        // Send initial event
        await stream.writeSSE({
          event: 'start',
          data: JSON.stringify({
            query: q,
            language: lang,
            timestamp: new Date().toISOString()
          })
        });

        // Step 1: Hybrid Retrieval
        await stream.writeSSE({
          event: 'status',
          data: JSON.stringify({ phase: 'retrieval', message: 'Suche nach relevanten Informationen...' })
        });

        const retrievalResults = await hybridRetriever.retrieve({
          project,
          query: q,
          collections,
          limit: 50, // Get more for re-ranking
          language: lang || 'de',
          enableRerank: false // We'll do custom reranking
        });

        if (retrievalResults.length === 0) {
          await stream.writeSSE({
            event: 'error',
            data: JSON.stringify({ 
              message: 'Keine relevanten Informationen gefunden.',
              code: 'NO_RESULTS'
            })
          });
          return;
        }

        // Send retrieval stats
        const retrievalStats = hybridRetriever.getRetrievalStats(retrievalResults);
        await stream.writeSSE({
          event: 'retrieval_complete',
          data: JSON.stringify({
            found: retrievalResults.length,
            stats: retrievalStats
          })
        });

        // Step 2: Re-ranking
        await stream.writeSSE({
          event: 'status',
          data: JSON.stringify({ phase: 'reranking', message: 'Bewerte Relevanz der Ergebnisse...' })
        });

        const rerankedResults = await reranker.rerank(
          q,
          retrievalResults.map(r => ({
            id: r.id,
            title: r.title,
            content: r.content,
            score: r.score
          })),
          maxPassages || 6
        );

        // Prepare citations
        const citations = rerankedResults.map((result, index) => ({
          id: result.id,
          title: result.title,
          url: retrievalResults.find(r => r.id === result.id)?.url || '',
          snippet: result.content.slice(0, 200) + (result.content.length > 200 ? '...' : ''),
          collection: retrievalResults.find(r => r.id === result.id)?.collection,
          reference: `[${index + 1}]`,
          score: result.rerankScore
        }));

        // Send citations
        await stream.writeSSE({
          event: 'citations',
          data: JSON.stringify(citations)
        });

        // Step 3: Generate answer with streaming
        await stream.writeSSE({
          event: 'status',
          data: JSON.stringify({ phase: 'generation', message: 'Generiere Antwort...' })
        });

        const answer = await generateStreamingAnswer(
          q,
          rerankedResults,
          citations,
          temperature || 0.7,
          stream
        );

        // Send completion event with JSON:API response
        const answerResource = {
          type: 'answer',
          id: `answer-${Date.now()}`,
          attributes: {
            text: answer,
            query: q,
            language: lang,
            generated_at: new Date().toISOString(),
            confidence: calculateConfidence(rerankedResults),
            streaming: true
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
            passages_found: retrievalResults.length,
            passages_used: rerankedResults.length,
            response_time_ms: Date.now() - startTime,
            retrieval_stats: retrievalStats
          }
        };

        const citationResources = citations.map((citation, index) =>
          transformCitationToResource(citation, index)
        );

        await stream.writeSSE({
          event: 'complete',
          data: JSON.stringify(
            createJsonApiResponse(answerResource, {
              included: citationResources,
              meta: {
                query: {
                  text: q,
                  language: lang,
                  collections: collections || []
                },
                generation: {
                  response_time_ms: Date.now() - startTime,
                  streaming: true,
                  model: process.env.OPENAI_MODEL || 'gpt-4o-mini'
                }
              }
            })
          )
        });

      } catch (error) {
        console.error('SSE streaming error:', error);
        await stream.writeSSE({
          event: 'error',
          data: JSON.stringify({
            message: error instanceof Error ? error.message : 'Unbekannter Fehler',
            code: 'STREAM_ERROR'
          })
        });
      }
    });
  }
);

/**
 * Generate streaming answer using OpenAI
 */
async function generateStreamingAnswer(
  query: string,
  passages: any[],
  citations: any[],
  temperature: number,
  stream: any
): Promise<string> {
  // Check if OpenAI is configured
  if (!process.env.OPENAI_API_KEY) {
    // Fallback to non-streaming response
    const answer = generateFallbackAnswer(query, passages, citations);
    await stream.writeSSE({
      event: 'answer_chunk',
      data: JSON.stringify({ text: answer, done: true })
    });
    return answer;
  }

  const openai = new OpenAI({
    apiKey: process.env.OPENAI_API_KEY
  });

  // Prepare context
  const contextText = passages
    .map((p, i) => `[${i + 1}] ${p.title}\n${p.content.slice(0, 500)}`)
    .join('\n\n');

  const systemPrompt = `Du bist ein präziser Assistent, der Fragen basierend auf bereitgestellten Informationen beantwortet.
Wichtige Regeln:
1. Verwende NUR die gegebenen Informationen
2. Zitiere Quellen mit [1], [2], etc.
3. Sei präzise und faktentreu
4. Wenn die Informationen nicht ausreichen, sage das ehrlich
5. Strukturiere längere Antworten mit Absätzen
6. Verwende deutsche Sprache`;

  const userPrompt = `Frage: ${query}

Verfügbare Informationen:
${contextText}

Beantworte die Frage präzise und zitiere die relevanten Quellen.`;

  try {
    const completion = await openai.chat.completions.create({
      model: process.env.OPENAI_MODEL || 'gpt-4o-mini',
      messages: [
        { role: 'system', content: systemPrompt },
        { role: 'user', content: userPrompt }
      ],
      temperature,
      max_tokens: 1000,
      stream: true
    });

    let fullAnswer = '';
    
    for await (const chunk of completion) {
      const content = chunk.choices[0]?.delta?.content || '';
      if (content) {
        fullAnswer += content;
        
        // Send chunk to client
        await stream.writeSSE({
          event: 'answer_chunk',
          data: JSON.stringify({ 
            text: content, 
            done: false 
          })
        });
      }
    }

    // Send final chunk
    await stream.writeSSE({
      event: 'answer_chunk',
      data: JSON.stringify({ 
        text: '', 
        done: true,
        full_text: fullAnswer
      })
    });

    return fullAnswer;
  } catch (error) {
    console.error('OpenAI streaming error:', error);
    const fallback = generateFallbackAnswer(query, passages, citations);
    await stream.writeSSE({
      event: 'answer_chunk',
      data: JSON.stringify({ text: fallback, done: true })
    });
    return fallback;
  }
}

/**
 * Generate fallback answer without streaming
 */
function generateFallbackAnswer(
  query: string,
  passages: any[],
  citations: any[]
): string {
  if (passages.length === 0) {
    return 'Entschuldigung, ich konnte keine relevanten Informationen zu Ihrer Frage finden.';
  }

  // Simple extractive answer
  const relevantPassages = passages.slice(0, 3);
  let answer = `Basierend auf den verfügbaren Informationen:\n\n`;
  
  relevantPassages.forEach((passage, index) => {
    const citation = citations[index];
    if (citation) {
      answer += `${citation.reference} ${passage.content.slice(0, 200)}...\n\n`;
    }
  });

  answer += `Diese Informationen stammen aus ${relevantPassages.length} relevanten Quellen.`;
  
  return answer;
}

/**
 * Calculate confidence score
 */
function calculateConfidence(passages: any[]): number {
  if (passages.length === 0) return 0;
  
  const avgScore = passages.reduce((sum, p) => {
    return sum + (p.rerankScore || p.score || 0);
  }, 0) / passages.length;
  
  const countFactor = Math.min(passages.length / 6, 1);
  
  return Math.round((avgScore * countFactor) * 100) / 100;
}

import { Hono } from 'hono';
import { zValidator } from '@hono/zod-validator';
import { authMiddleware } from '../middleware/auth.js';
import { MeiliEngine } from '../engines/meili.js';
import { HybridRetriever } from '../lib/hybrid-retrieval.js';
import { getTelemetryService } from '../lib/telemetry.js';
import { searchSchema, suggestSchema } from '../schemas.js';
import { 
  createJsonApiResponse, 
  createJsonApiError, 
  transformSearchHitToResource,
  createPaginationLinks,
  createHttpError 
} from '../utils/jsonapi.js';

export const router = new Hono();
const engine = new MeiliEngine(process.env.MEILI_URL || 'http://localhost:7700', process.env.MEILI_KEY);
const hybridRetriever = new HybridRetriever();
const telemetryService = getTelemetryService();

// Main search endpoint - JSON:API 1.0 compatible
router.post('/search/:project', 
  authMiddleware.requireKey('read'), 
  zValidator('json', searchSchema), 
  async (c) => {
    try {
      const startTime = Date.now();
      const { project } = c.req.param();
      const payload = await c.req.json();
      const baseUrl = new URL(c.req.url).origin;
      
      // Add collection filtering if specified
      if (payload.collections?.length) {
        payload.filters = {
          ...payload.filters,
          _collection: payload.collections
        };
      }

      // Use hybrid retrieval if enabled
      let searchResults;
      if (process.env.ENABLE_HYBRID_RETRIEVAL === 'true') {
        const hybridResults = await hybridRetriever.retrieve({
          project,
          query: payload.q,
          collections: payload.collections,
          limit: payload.limit,
          language: payload.lang,
          enableRerank: false
        });
        
        // Convert to Meilisearch format
        searchResults = {
          hits: hybridResults,
          estimatedTotalHits: hybridResults.length,
          processingTimeMs: Date.now() - startTime
        };
      } else {
        searchResults = await engine.search(project, payload);
      }
      const responseTime = Date.now() - startTime;
      
      // Extract hits and pagination info
      const hits = searchResults.hits || [];
      const totalHits = searchResults.estimatedTotalHits || hits.length;
      const totalPages = Math.ceil(totalHits / payload.limit);

      // Transform hits to JSON:API resources
      const searchResources = hits.map((hit: any) => 
        transformSearchHitToResource(hit, 'searchResult')
      );

      // Create pagination links
      const links = createPaginationLinks(
        `${baseUrl}/v1/search/${project}`,
        payload.page,
        totalPages,
        payload.limit,
        payload.q
      );

      // Create JSON:API meta
      const meta = {
        pagination: {
          page: payload.page,
          pages: totalPages,
          count: hits.length,
          total: totalHits
        },
        search: {
          query: payload.q,
          response_time_ms: responseTime,
          collections: payload.collections || [],
          language: payload.lang || 'de',
          processing_time_ms: searchResults.processingTimeMs || responseTime
        }
      };

      // Track telemetry
      await telemetryService.trackQuery({
        query: payload.q,
        project_id: project,
        results_count: hits.length,
        response_time_ms: responseTime,
        language: payload.lang,
        collections: payload.collections,
        user_agent: c.req.header('user-agent'),
        ip: c.req.header('x-forwarded-for') || c.req.header('x-real-ip'),
        timestamp: new Date()
      });
      
      // Log metrics (if enabled)
      if (process.env.ENABLE_METRICS === 'true') {
        console.log(`Search: "${payload.q}" in ${responseTime}ms, ${hits.length} results`);
      }

      // Return JSON:API 1.0 compliant response
      return c.json(createJsonApiResponse(searchResources, {
        meta,
        links
      }));

    } catch (error) {
      console.error('Search error:', error);
      
      const jsonApiError = createHttpError(500, 
        error instanceof Error ? error.message : 'Search failed'
      );
      
      return c.json(createJsonApiError(jsonApiError), 500);
    }
  }
);

// Suggest/autocomplete endpoint - JSON:API compatible
router.post('/suggest/:project', 
  authMiddleware.requireKey('read'), 
  zValidator('json', suggestSchema), 
  async (c) => {
    try {
      const { project } = c.req.param();
      const { q, limit, collections } = await c.req.json();
      
      // Simple prefix search for suggestions
      const searchPayload = {
        q,
        limit: limit * 2, // Get more for better suggestions
        filters: collections?.length ? { _collection: collections } : undefined
      };

      const results = await engine.search(project, searchPayload);
      
      // Extract unique suggestions from titles and content
      const suggestions = new Set<string>();
      const hits = results.hits || [];
      
      for (const hit of hits) {
        if (hit.title) {
          // Extract words from title that start with the query
          const words = hit.title.toLowerCase().split(/\s+/);
          words.forEach(word => {
            if (word.startsWith(q.toLowerCase()) && word.length > q.length) {
              suggestions.add(word);
            }
          });
        }
      }

      // Transform suggestions to JSON:API resources
      const suggestionResources = Array.from(suggestions)
        .slice(0, limit)
        .map((suggestion, index) => ({
          type: 'suggestion',
          id: `suggestion-${index}`,
          attributes: {
            text: suggestion,
            query: q,
            completion: suggestion
          },
          meta: {
            relevance: 1.0 - (index * 0.1) // Simple relevance scoring
          }
        }));

      const meta = {
        search: {
          query: q,
          total_suggestions: suggestions.size,
          returned_suggestions: suggestionResources.length,
          collections: collections || []
        }
      };

      return c.json(createJsonApiResponse(suggestionResources, { meta }));

    } catch (error) {
      console.error('Suggest error:', error);
      
      const jsonApiError = createHttpError(500, 
        error instanceof Error ? error.message : 'Suggest failed'
      );
      
      return c.json(createJsonApiError(jsonApiError), 500);
    }
  }
);

import { Hono } from 'hono';
import { zValidator } from '@hono/zod-validator';
import { authMiddleware } from '../middleware/auth.js';
import { MeiliEngine } from '../engines/meili.js';
import { searchSchema, suggestSchema } from '../schemas.js';

export const router = new Hono();
const engine = new MeiliEngine(process.env.MEILI_URL || 'http://localhost:7700', process.env.MEILI_KEY);

// Main search endpoint
router.post('/search/:project', 
  authMiddleware.requireKey('read'), 
  zValidator('json', searchSchema), 
  async (c) => {
    try {
      const startTime = Date.now();
      const { project } = c.req.param();
      const payload = await c.req.json();
      
      // Add collection filtering if specified
      if (payload.collections?.length) {
        payload.filters = {
          ...payload.filters,
          _collection: payload.collections
        };
      }

      const hits = await engine.search(project, payload);
      const responseTime = Date.now() - startTime;

      // Log metrics (if enabled)
      if (process.env.ENABLE_METRICS === 'true') {
        // TODO: Log search metrics to database
        console.log(`Search: ${payload.q} in ${responseTime}ms, ${hits.hits?.length || 0} results`);
      }

      return c.json({
        ...hits,
        meta: {
          query: payload.q,
          page: payload.page,
          limit: payload.limit,
          response_time_ms: responseTime,
          collections: payload.collections
        }
      });
    } catch (error) {
      console.error('Search error:', error);
      return c.json({ 
        error: 'Search failed', 
        details: error instanceof Error ? error.message : 'Unknown error' 
      }, 500);
    }
  }
);

// Suggest/autocomplete endpoint
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
        limit,
        filters: collections?.length ? { _collection: collections } : undefined
      };

      const results = await engine.search(project, searchPayload);
      
      // Extract unique suggestions from titles and content
      const suggestions = new Set<string>();
      const hits = results.hits || [];
      
      for (const hit of hits.slice(0, limit)) {
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

      return c.json({
        suggestions: Array.from(suggestions).slice(0, limit),
        query: q
      });
    } catch (error) {
      console.error('Suggest error:', error);
      return c.json({ 
        error: 'Suggest failed', 
        details: error instanceof Error ? error.message : 'Unknown error' 
      }, 500);
    }
  }
);

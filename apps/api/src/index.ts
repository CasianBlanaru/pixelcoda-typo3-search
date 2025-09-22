import 'dotenv/config';
import { Hono } from 'hono';
import { cors } from 'hono/cors';
import { prettyJSON } from 'hono/pretty-json';
import { zValidator } from '@hono/zod-validator';
import { z } from 'zod';
import { authMiddleware } from './middleware/auth.js';
import { router as indexRouter } from './routes/index.js';
import { router as searchRouter } from './routes/search.js';
import { router as askRouter } from './routes/ask.js';
import { router as synonymsRouter } from './routes/synonyms.js';
import { router as metricsRouter } from './routes/metrics.js';

const app = new Hono();

app.use('*', cors());
app.use('*', prettyJSON());

// Health
app.get('/health', c => c.json({ ok: true }));

// API routes (v1 prefix)
app.route('/v1', indexRouter);
app.route('/v1', searchRouter);
app.route('/v1', askRouter);
app.route('/v1', synonymsRouter);
app.route('/v1', metricsRouter);

// 404
app.notFound((c) => c.json({ error: 'Not Found' }, 404));

const port = Number(process.env.PORT) || 8787;
export default {
  port,
  fetch: app.fetch,
};

if (process.env.NODE_ENV !== 'production') {
  Bun?.serve?.({ fetch: app.fetch, port }) ??
  (async () => {
    const { serve } = await import('@hono/node-server');
    serve({ fetch: app.fetch, port });
    console.log(`[api] listening on http://localhost:${port}`);
  })();
}

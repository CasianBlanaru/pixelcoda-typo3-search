# Cursor Task: Build API routes

## Goal
Implement REST API endpoints for indexing, searching, asking (RAG), synonyms, and metrics with Hono.

## Steps
1. Complete zod schemas in `apps/api/src/schemas.ts`.
2. Implement `apps/api/src/engines/base.ts` and `engines/meili.ts`.
3. Implement handlers in `apps/api/src/routes/*.ts`:
   - `index.ts` – upsert/delete, bulk operations
   - `search.ts` – keyword + filters + facets
   - `ask.ts` – semantic retrieve (pgvector) + re-rank + grounded answer
   - `synonyms.ts` – CRUD
   - `metrics.ts` – log query/click, expose aggregates
4. Add API key middleware `middleware/auth.ts` (read/write scopes).
5. Add input validation via zod; return JSON with errors.

## Done When
- All routes mounted in `src/index.ts`
- Unit tests pass for schema validation and auth

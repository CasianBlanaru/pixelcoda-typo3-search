# Cursor Agent – Master Prompt (PixelCoda Headless Search)

## Role
You are a senior full‑stack engineer. Build a headless, API‑first search platform with KI agents. Priorities: correctness, security, accessibility (BITV 2.0), and developer DX.

## Stack Constraints
- Runtime: Node 20+ (Bun optional but not required here)
- API: Hono + TypeScript
- DB: Postgres (pg + drizzle optional), pgvector for embeddings
- Search engine: Meilisearch (default), adapters for Typesense/OpenSearch
- Queue: simple local queue abstraction; later Inngest
- Auth: project-scoped API keys (read/write separation)
- UI: headless React widgets (no CSS framework assumptions), keyboard/A11y required
- LLM: provider-agnostic via `packages/llm-adapter`

## Deliverables
- `/v1/index`, `/v1/search`, `/v1/ask`, `/v1/suggest`, `/v1/synonyms`, `/v1/metrics/*`
- Embedding pipeline: crawl → chunk → embed → upsert
- Answer Agent (RAG) with strict grounding + citations
- Re-Ranker pluggable (BGE or LLM API)
- Synonym mining draft flow using query telemetry
- Security: HMAC-signed webhooks, API key RBAC, input validation (zod)
- Tests where feasible

## Coding Standards
- TypeScript strict mode
- zod schemas at the boundary
- No any, avoid implicit any
- Explicit error handling; never swallow errors
- Accessible widgets (ARIA roles, roving tabindex, live regions)
- Idempotent jobs/resumable indexing

## Initial Tasks
1) Finish the API skeleton in `apps/api` and wire routes.
2) Implement minimal Meilisearch adapter in `apps/api/src/engines/meili.ts`.
3) Implement `/v1/ask` using pgvector similarity then re-rank (stub ok).
4) Flesh out worker jobs in `apps/worker/src/jobs/*` (crawl, chunk, embed).
5) Provide SDK clients in `apps/widgets/src/client.ts` for API calls.
6) Add API key middleware and HMAC verification for webhooks.
7) Add unit tests for schemas and adapters.

## Acceptance
- `npm -w apps/api run dev` starts Hono server on PORT=8787
- `POST /v1/index/:project/:collection` upserts docs
- `POST /v1/search/:project` returns hits in <100ms on small dataset
- `POST /v1/ask/:project` returns grounded answer with citations

# Cursor Task: Worker Jobs (Ingest Pipeline)

## Goal
Implement crawl → extract → chunk → embed → upsert.

## Steps
1. `src/jobs/crawl.ts` fetch URL/HTML (Playwright optional; simple fetch ok).
2. `src/jobs/extract.ts` strip HTML → text; collect title, headings, meta.
3. `src/jobs/chunk.ts` split into ~500–800 token chunks with overlap.
4. `src/jobs/embed.ts` call `packages/llm-adapter` to get embeddings.
5. `src/jobs/upsert.ts` upsert chunks + doc into DB and Meilisearch adapter.
6. Wire a minimal local queue in `src/queue.ts` and `src/index.ts` CLI.

## Done When
- `npm -w apps/worker run dev` can ingest a URL to the API.

# Railway Deployment

Pixelcoda Search can be deployed from this repository as a Railway service. The
checked-in `railway.json` uses Railpack, builds the production workspaces,
starts `simple-api.js`, and verifies deployments through `/health`.

## Deploy the API

1. Create a Railway project and choose **Deploy from GitHub repo**.
2. Select `CasianBlanaru/pixelcoda-typo3-search`.
3. Keep the service root directory at `/`.
4. Generate a public domain for the service.
5. Add the required variables before deploying:

```dotenv
NODE_ENV=production
API_READ_KEY=<random-secret>
API_WRITE_KEY=<random-secret>
CORS_ALLOWED_ORIGINS=https://your-typo3-site.example,https://www.your-typo3-site.example
SEARCH_DATA_DIR=/data
```

Generate strong API keys locally:

```bash
openssl rand -hex 32
```

Railway supplies `PORT` automatically. Do not set a fixed production port.
Production startup fails intentionally when either API key is missing.

Attach a Railway volume mounted at `/data` when deploying the built-in
persistent API. Without a volume, indexed documents are lost when the service
is redeployed.

## Optional Services

For the complete search platform, add or connect:

- Railway PostgreSQL and use its `DATABASE_URL`.
- Railway Redis and use its `REDIS_URL` or `CACHE_REDIS_URL`.
- A Meilisearch service or managed instance and set `MEILI_URL` and `MEILI_KEY`.
- An LLM provider key such as `OPENAI_API_KEY` only when AI answers are enabled.

The complete API in `apps/api` supports Meilisearch, PostgreSQL/pgvector,
hybrid retrieval, metrics and the provider-agnostic LLM adapter. The root
service started by `npm start` is the lightweight persistent option for
single-service deployments and local TYPO3 testing.

Supported AI provider variables include:

```dotenv
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
# or AZURE_OPENAI_API_KEY / OLLAMA_BASE_URL / HUGGINGFACE_API_KEY
```

Use Railway private networking URLs when services belong to the same project.
Never commit production credentials.

## Verify

Railway checks `/health` during deployment. Verify the public service after the
deployment becomes active:

```bash
curl --fail https://your-service.up.railway.app/health
```

The endpoint must return HTTP `200` and `"ok": true`.

Index TYPO3 content after deployment:

```bash
vendor/bin/typo3 pixelcoda:search:reindex
```

## Configuration

The repository configuration intentionally keeps deployment behavior explicit:

- Build: `npm ci && npm run build`
- Start: `npm start`
- Healthcheck: `/health`
- Restart policy: retry failed processes up to ten times

Override these settings in Railway only when deploying an individual workspace
as a separate service.

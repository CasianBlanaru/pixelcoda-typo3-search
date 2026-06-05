# Railway Deployment

Pixelcoda Search uses two Railway services from this repository:

1. **TYPO3 website**: PHP/Apache application built with `Dockerfile`.
2. **Search API**: lightweight persistent Node.js API built with
   `Dockerfile.api`.

Keeping both runtimes separate prevents Railpack from concurrently modifying
PHP and Node dependencies and allows independent scaling and health checks.

## TYPO3 Service

Create a Railway service from this GitHub repository. Keep the root directory
at `/` and use `/railway.json` as the Railway configuration file.

Add a Railway MySQL service and expose its variables to TYPO3. The entrypoint
automatically supports Railway's `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`,
`MYSQLUSER` and `MYSQLPASSWORD` variables.

Add these TYPO3 variables before the first deployment:

```dotenv
TYPO3_SETUP_ADMIN_USERNAME=admin
TYPO3_SETUP_ADMIN_PASSWORD=<strong-random-password>
TYPO3_SETUP_ADMIN_EMAIL=admin@example.com
TYPO3_PROJECT_NAME=Pixelcoda TYPO3 Search
```

Attach a Railway volume mounted at `/data`. It persists TYPO3 configuration,
uploaded files and runtime data across deployments. A deployment without this
volume is intentionally unsupported because TYPO3's encryption key and site
configuration must remain stable.

The container creates the TYPO3 installation and initial site on first start.
Subsequent starts reuse the persisted configuration. Railway checks
`/healthz.php`.

## Search API Service

Create a second service from the same repository and use
`/railway.api.json` as its Railway configuration file.

Set:

```dotenv
NODE_ENV=production
API_READ_KEY=<random-secret>
API_WRITE_KEY=<random-secret>
CORS_ALLOWED_ORIGINS=https://your-typo3-domain.example
SEARCH_DATA_DIR=/data
```

Attach a separate Railway volume mounted at `/data`. Railway checks `/health`.

Generate strong keys locally:

```bash
openssl rand -hex 32
```

Production startup fails intentionally when either API key is missing.

## Connect TYPO3 And Search API

Expose the Search API with a Railway public domain. Configure the TYPO3
extension with:

```dotenv
PIXELCODA_API_URL=https://your-search-api.up.railway.app
PIXELCODA_API_KEY=<API_WRITE_KEY>
PIXELCODA_READ_API_KEY=<API_READ_KEY>
PIXELCODA_PROJECT_ID=typo3
```

After both services are running, open the TYPO3 service shell and index the
published content:

```bash
vendor/bin/typo3 pixelcoda:search:reindex
```

## Optional Full Search Platform

The API workspaces also support Meilisearch, PostgreSQL/pgvector, Redis and
provider-based AI answers. Add separate Railway services when these features
are required:

```dotenv
DATABASE_URL=
REDIS_URL=
MEILI_URL=
MEILI_KEY=
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
```

Azure OpenAI, Ollama and Hugging Face are supported by the LLM adapter. Never
commit production credentials.

## Verify

```bash
curl --fail https://your-typo3-domain.example/healthz.php
curl --fail https://your-search-api.up.railway.app/health
```

Both endpoints must return HTTP `200` and an `ok` value.

## Complete Pixelcoda TYPO3 Suite

This repository deploys TYPO3 with the Pixelcoda Search extension. A production
project containing Search, FE Editor, GSAP Animation and the Pixelcoda
sitepackage should live in a dedicated TYPO3 distribution repository. Extension
source repositories should remain independent so they can be versioned,
tested and released without coupling all plugins to one deployment.

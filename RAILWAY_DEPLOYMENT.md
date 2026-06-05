# Railway Deployment

This repository deploys the complete Pixelcoda TYPO3 suite on Railway:

- TYPO3 14.3 with the Pixelcoda sitepackage
- Pixelcoda Search
- Pixelcoda FE Editor
- Pixelcoda Content GSAP Animation
- A separate persistent Search API service

The TYPO3 and Search API services use separate containers and volumes so they
can be updated, scaled and monitored independently.

## 1. Create The TYPO3 Service

Create a Railway service from this GitHub repository. Keep the root directory
at `/` and select `/railway.json` as the configuration file.

Add a Railway MySQL service and make its variables available to the TYPO3
service. The entrypoint reads Railway's `MYSQLHOST`, `MYSQLPORT`,
`MYSQLDATABASE`, `MYSQLUSER` and `MYSQLPASSWORD` variables automatically.

Attach a Railway volume at `/data`. It persists:

- `config/system/settings.php` and the encryption key
- the generated site configuration
- uploaded files in `public/fileadmin`

Add these variables before the first deployment:

```dotenv
TYPO3_SETUP_ADMIN_USERNAME=admin
TYPO3_SETUP_ADMIN_PASSWORD=<strong-random-password>
TYPO3_SETUP_ADMIN_EMAIL=admin@example.com
TYPO3_PROJECT_NAME=Pixelcoda TYPO3 Suite
```

Optional FE Editor AI configuration:

```dotenv
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4.1-mini
```

The first start waits for MySQL, installs TYPO3, creates the initial site,
runs all extension database migrations and activates the Pixelcoda site sets.
Every later deployment runs the extension migrations again before Apache
starts. Railway checks `/healthz.php` and only receives HTTP 200 after TYPO3
has a persisted installation.

## 2. Create The Search API Service

Create a second service from the same repository and select
`/railway.api.json` as its Railway configuration file.

Attach a separate Railway volume at `/data` and configure:

```dotenv
NODE_ENV=production
API_READ_KEY=<random-secret>
API_WRITE_KEY=<random-secret>
CORS_ALLOWED_ORIGINS=https://your-typo3-domain.example
SEARCH_DATA_DIR=/data
```

Generate strong keys locally:

```bash
openssl rand -hex 32
```

Optional managed search and AI services:

```dotenv
DATABASE_URL=
REDIS_URL=
MEILI_URL=
MEILI_KEY=
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4.1-mini
```

## 3. Connect TYPO3 To The Search API

Expose the Search API with a Railway public domain and add these variables to
the TYPO3 service:

```dotenv
PIXELCODA_API_URL=https://your-search-api.up.railway.app
PIXELCODA_API_KEY=<API_WRITE_KEY>
PIXELCODA_READ_API_KEY=<API_READ_KEY>
PIXELCODA_PROJECT_ID=typo3
PIXELCODA_CORS_ORIGINS=https://your-typo3-domain.example
```

After both services are healthy, open the TYPO3 service shell and index the
published content:

```bash
vendor/bin/typo3 pixelcoda:search:reindex
```

## Verify

```bash
curl --fail https://your-typo3-domain.example/healthz.php
curl --fail https://your-search-api.up.railway.app/health
```

Both endpoints must return HTTP 200 and an `ok` value.

For an existing Railway volume created with TYPO3 12, take a database backup
before deploying this TYPO3 14 image. The startup migration updates the
database schema, but a major-version migration should always be reversible.

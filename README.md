# Pixelcoda Search for TYPO3

Accessible, API-first search for TYPO3 with classic and headless rendering,
filters, suggestions, hybrid retrieval and optional AI-assisted answers.

**TYPO3 support:** 12.4 LTS, 13.4 LTS and 14.x  
**Composer package:** `pixelcoda/typo3-search`  
**Extension key:** `pixelcoda_search`

## 🚀 Features

- **Headless API-First Architecture** with REST endpoints
- **Hybrid Search**: Keyword search (Meilisearch) + Vector search (pgvector)
- **AI-powered Answers** with RAG (Retrieval-Augmented Generation)
- **Accessible React Widgets** (BITV 2.0 compliant)
- **TYPO3 Connector** with webhook integration
- **Flexible Ingest Pipeline**: Crawl → Extract → Chunk → Embed → Upsert
- **Multi-Provider LLM Support** (OpenAI, Azure, Ollama, Hugging Face)
- **Security**: API Keys, HMAC signatures, Rate limiting
- **Analytics & Telemetry** for query/click tracking
- **Docker-ready** with docker-compose setup

## 🏗️ Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   TYPO3 CMS     │    │   Worker Jobs   │
│   (Widgets)     │    │   (Connector)   │    │   (Ingest)      │
└─────────┬───────┘    └─────────┬───────┘    └─────────┬───────┘
          │                      │                      │
          │ REST API             │ Webhooks             │ Queue
          │                      │                      │
┌─────────▼──────────────────────▼──────────────────────▼───────┐
│                    pixelcoda Search API                       │
│            (Hono + TypeScript + Zod Validation)              │
└─────────┬─────────────────────────────────────────┬─────────┘
          │                                         │
┌─────────▼─────────┐                    ┌─────────▼─────────┐
│   Meilisearch     │                    │   PostgreSQL      │
│   (Keyword)       │                    │   + pgvector      │
└───────────────────┘                    └───────────────────┘
```

## 📦 Components

### Apps
- **`apps/api`** - Hono REST API with TypeScript
- **`apps/worker`** - Ingest pipeline for content processing
- **`apps/widgets`** - Accessible React components
- **`apps/typo3-connector`** - TYPO3 extension for webhook integration

### Packages
- **`packages/llm-adapter`** - Provider-agnostic LLM client

### Development Environment
- **`typo3-dev/`** - Complete DDEV TYPO3 setup for testing

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [QUICKSTART.md](QUICKSTART.md) | 5-minute setup: Simple API, full stack, TYPO3 with DDEV |
| [typo3-dev/README.md](typo3-dev/README.md) | TYPO3 DDEV environment, composer, Install Tool |
| [BOOTSTRAP_PACKAGE_SETUP.md](BOOTSTRAP_PACKAGE_SETUP.md) | TYPO3 Bootstrap Package installation and configuration |
| [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md) | Production deployment on Railway |
| [SECURITY.md](SECURITY.md) | API keys, HMAC, rate limiting, production checklist |
| [CHANGELOG.md](CHANGELOG.md) | Release history and notable changes |

## 🛠️ Quick Start

**Minimal persistent API:** `npm install` → `npm run dev` → API at
http://localhost:8787. Indexed documents are stored in `.data/search-index.json`
locally. See [QUICKSTART.md](QUICKSTART.md).

After configuring the TYPO3 extension API URL and write key, index published
pages and content elements:

```bash
vendor/bin/typo3 pixelcoda:search:reindex
vendor/bin/typo3 pixelcoda:search:index --dry-run
```

The frontend content element provides keyword search and an optional
source-grounded AI answer panel. The production API additionally supports
Meilisearch, PostgreSQL/pgvector, hybrid retrieval, synonyms, metrics and
OpenAI, Azure OpenAI, Ollama or Hugging Face through the LLM adapter.

**Full setup:**

### 1. Clone Repository
```bash
git clone git@github.com:CasianBlanaru/pixelcoda-typo3-search.git
cd pixelcoda-typo3-search
```

### 2. Install Dependencies
```bash
npm install
# or: yarn install
```

### 3. Environment Setup
```bash
cp env.example .env
# Edit .env with your configurations
```

### 4. Start Services
```bash
# All services (Postgres, Meilisearch, API)
docker-compose up -d

# Or just database services
docker-compose up -d postgres meilisearch redis

# API in development mode
yarn -w apps/api run dev
```

### 5. Initialize Database
```bash
# With Docker
docker-compose --profile migrate up migrate

# Or locally
node scripts/migrate.js
```

## 🔧 Development

### Quality checks

```bash
npm ci
npm run build
npm run lint:extension
npm test

cd typo3-dev/packages/pixelcoda_search
composer validate --strict
```

CI resolves the extension against TYPO3 12.4, 13.4 and 14.x. The full PHP
quality suite runs against TYPO3 13.4; JavaScript linting, tests and every
TypeScript production build run on each pull request.

### API Server
```bash
yarn -w apps/api run dev
# Runs on http://localhost:8787
```

### Worker for Content Ingestion
```bash
# Index single URL
yarn -w apps/worker run dev -- crawl https://example.com demo

# Pull from TYPO3-Headless API
yarn -w apps/worker run dev -- typo3-pull https://api.example.com typo3-site --language en --types pages,news

# With advanced options
yarn -w apps/worker run dev -- crawl https://docs.example.com docs \
  --collection documentation \
  --content-type documentation \
  --batch-size 5
```

### Widgets Development
```bash
yarn -w apps/widgets run build
```

### TYPO3 Development Environment
```bash
cd typo3-dev
./setup.sh
# Complete TYPO3 installation with plugin
```

## 📚 API Documentation

### Core Endpoints

#### Indexing
```bash
# Add document
POST /v1/index/:project/:collection
{
  "documents": [{
    "id": "doc1",
    "title": "Title",
    "content": "Content...",
    "url": "https://example.com/page",
    "lang": "en"
  }]
}

# Delete documents
DELETE /v1/index/:project/:collection
{
  "ids": ["doc1", "doc2"]
}
```

#### Search (JSON:API 1.0 Compatible)
```bash
# Keyword search
POST /v1/search/:project
{
  "q": "search term",
  "limit": 10,
  "filters": {"collection": ["pages"]},
  "facets": ["collection", "lang"]
}

# Response:
{
  "data": [{
    "type": "searchResult",
    "id": "doc1",
    "attributes": {
      "title": "Page Title",
      "content": "Page content...",
      "url": "/page",
      "score": 0.95
    }
  }],
  "meta": {
    "pagination": {"page": 1, "total": 42},
    "search": {"query": "search term", "response_time_ms": 120}
  },
  "links": {
    "self": "/v1/search/project?page=1",
    "next": "/v1/search/project?page=2"
  }
}
```

#### AI Answer (RAG)
```bash
POST /v1/ask/:project
{
  "q": "How does the search work?",
  "maxPassages": 6,
  "collections": ["docs"],
  "includeDebug": true
}

# Response:
{
  "data": {
    "type": "answer",
    "id": "answer-123",
    "attributes": {
      "text": "The search works by...",
      "query": "How does the search work?",
      "confidence": 0.89
    },
    "relationships": {
      "citations": {
        "data": [{"type": "citation", "id": "citation-0"}]
      }
    }
  },
  "included": [{
    "type": "citation",
    "id": "citation-0",
    "attributes": {
      "title": "Search Documentation",
      "url": "/docs/search",
      "snippet": "The search engine uses...",
      "reference": "[1]"
    }
  }],
  "meta": {
    "generation": {"response_time_ms": 1500, "citations_count": 3}
  }
}
```

#### Suggestions
```bash
POST /v1/suggest/:project
{
  "q": "sear",
  "limit": 5
}
```

#### Synonyms
```bash
# Add synonyms
POST /v1/synonyms/:project
{
  "add": [{
    "terms": ["car", "vehicle", "automobile"],
    "lang": "en",
    "type": "synonym"
  }]
}
```

#### Metrics
```bash
# Log query metrics
POST /v1/metrics/query/:project
{
  "query": "search term",
  "results_count": 5,
  "response_time_ms": 120
}

# Get analytics
GET /v1/metrics/:project/queries?from=2024-01-01&to=2024-01-31
```

## 🎨 Widget Integration

### SearchBox
```tsx
import { SearchBox, PixelcodaSearchClient } from '@pixelcoda/widgets';

function MyApp() {
  return (
    <SearchBox
      apiBase="http://localhost:8787"
      project="demo"
      apiKey="pc_read_dev_key"
      enableSuggestions={true}
      onResults={(results) => console.log(results)}
      onError={(error) => console.error(error)}
    />
  );
}
```

### AnswerPanel
```tsx
import { AnswerPanel } from '@pixelcoda/widgets';

function MyApp() {
  return (
    <AnswerPanel
      apiBase="http://localhost:8787"
      project="demo"
      apiKey="pc_read_dev_key"
      query="How does the search work?"
      collections={["docs"]}
      showDebug={true}
    />
  );
}
```

### Programmatic Client
```typescript
import { PixelcodaSearchClient } from '@pixelcoda/widgets';

const client = new PixelcodaSearchClient(
  'http://localhost:8787',
  'demo',
  'pc_read_dev_key'
);

// Search
const results = await client.search({
  q: 'search term',
  limit: 10,
  collections: ['pages']
});

// AI Answer
const answer = await client.ask({
  q: 'How does this work?',
  maxPassages: 6
});

// Log metrics
await client.logQuery('search term', results.hits.length, 150);
```

## 🔌 TYPO3 Integration

### Extension Installation
1. Copy `typo3-dev/packages/pixelcoda_search` to `typo3conf/ext/pixelcoda_search`
2. Activate extension in Extension Manager
3. Configure API endpoint and credentials

### Webhook Configuration
```php
// LocalConfiguration.php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] = [
    'api_url' => 'http://localhost:8787',
    'api_key' => 'pc_write_dev_key',
    'hmac_secret' => 'your_hmac_secret',
    'project_id' => 'typo3-site'
];
```

### Classic Plugin Usage
1. Create new page
2. Add content element "pixelcoda Search"
3. Configure settings via FlexForm
4. Choose template variant
5. Save and view in frontend

### Headless Mode
1. Set plugin mode to "headless"
2. Use JSON:API endpoints directly
3. Compatible with nuxt-typo3
4. No server-side rendering

## 🐳 Production Deployment

### With Docker Compose
```bash
# Production setup
NODE_ENV=production docker-compose up -d

# With SSL (Traefik/nginx)
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

### Environment Variables (Production)
```bash
# Generate secure API keys
API_READ_KEY=$(openssl rand -hex 32)
API_WRITE_KEY=$(openssl rand -hex 32)
MEILI_MASTER_KEY=$(openssl rand -hex 32)
POSTGRES_PASSWORD=$(openssl rand -hex 16)

# Configure LLM provider
OPENAI_API_KEY=your_production_key
ENABLE_VECTOR_SEARCH=true
ENABLE_RERANKING=true
```

## 🔒 Security

### API Key Management
- **Read Keys**: For search and analytics
- **Write Keys**: For indexing and admin operations
- **Project Scoping**: Keys can be restricted to projects

### HMAC Webhook Verification
```typescript
// Verify webhook signature
const signature = request.headers['x-pixelcoda-signature'];
const payload = JSON.stringify(request.body);
const expectedSignature = crypto
  .createHmac('sha256', process.env.HMAC_SECRET)
  .update(payload)
  .digest('hex');
```

### Rate Limiting
- Configurable per project
- Default: 100 requests/15min
- Different limits for read/write operations

## 📊 Monitoring & Analytics

### Metrics
- **Query Metrics**: Search terms, response times, result counts
- **Click Metrics**: Click-through rates, position tracking
- **Performance**: API response times, error rates

### Logging
```bash
# Show logs
docker-compose logs -f api
docker-compose logs -f worker

# Structured JSON logging
LOG_FORMAT=json LOG_LEVEL=info
```

## 🧪 Testing

### Unit Tests
```bash
# API Tests
yarn -w apps/api test

# Worker Tests
yarn -w apps/worker test

# Widget Tests
yarn -w apps/widgets test
```

### Integration Tests
```bash
# E2E API Tests
yarn test:integration

# Load Tests
yarn test:load

# TYPO3 Plugin Tests
cd typo3-dev && ddev exec composer test
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push branch (`git push origin feature/amazing-feature`)
5. Create Pull Request

## 📝 License

This project is released under the MIT License. See [LICENSE](LICENSE) for details.

## 🆘 Support

- **Issues**: [GitHub Issues](https://github.com/CasianBlanaru/pixelcoda-typo3-search/issues)
- **Documentation**: [Wiki](https://github.com/CasianBlanaru/pixelcoda-typo3-search/wiki)
- **Discussions**: [GitHub Discussions](https://github.com/CasianBlanaru/pixelcoda-typo3-search/discussions)

---

Built with ❤️ by [pixelcoda](https://pixelcoda.de) for the TYPO3 community.

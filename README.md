# pixelcoda Headless Search Platform

Eine moderne, API-first Suchplattform mit KI-Agents, entwickelt für TYPO3 und andere CMS-Systeme.

## 🚀 Features

- **Headless API-First Architektur** mit REST-Endpoints
- **Hybrid Search**: Keyword-Suche (Meilisearch) + Vektor-Suche (pgvector)
- **KI-gestützte Antworten** mit RAG (Retrieval-Augmented Generation)
- **Barrierefreie React Widgets** (BITV 2.0 konform)
- **TYPO3 Connector** mit Webhook-Integration
- **Flexible Ingest-Pipeline**: Crawl → Extract → Chunk → Embed → Upsert
- **Multi-Provider LLM-Support** (OpenAI, Azure, Ollama, Hugging Face)
- **Sicherheit**: API Keys, HMAC-Signaturen, Rate Limiting
- **Analytics & Telemetrie** für Query/Click-Tracking
- **Docker-ready** mit docker-compose Setup

## 🏗️ Architektur

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

## 📦 Komponenten

### Apps
- **`apps/api`** - Hono REST API mit TypeScript
- **`apps/worker`** - Ingest Pipeline für Content Processing
- **`apps/widgets`** - Barrierefreie React Komponenten
- **`apps/typo3-connector`** - TYPO3 Extension für Webhook-Integration

### Packages
- **`packages/llm-adapter`** - Provider-agnostischer LLM Client

## 🛠️ Quick Start

### 1. Repository klonen
```bash
git clone git@github.com:CasianBlanaru/typo3-search.git
cd typo3-search
```

### 2. Dependencies installieren
```bash
yarn install
```

### 3. Environment Setup
```bash
cp env.example .env
# Bearbeite .env mit deinen Konfigurationen
```

### 4. Services starten
```bash
# Alle Services (Postgres, Meilisearch, API)
docker-compose up -d

# Nur Datenbank-Services
docker-compose up -d postgres meilisearch redis

# API im Development-Modus
yarn -w apps/api run dev
```

### 5. Datenbank initialisieren
```bash
# Mit Docker
docker-compose --profile migrate up migrate

# Oder lokal
node scripts/migrate.js
```

## 🔧 Entwicklung

### API Server starten
```bash
yarn -w apps/api run dev
# Läuft auf http://localhost:8787
```

### Worker für Content-Ingest
```bash
# Einzelne URL indexieren
yarn -w apps/worker run dev -- https://example.com demo

# Mit erweiterten Optionen
yarn -w apps/worker run dev -- https://docs.example.com docs \
  --collection documentation \
  --content-type documentation \
  --batch-size 5
```

### Widgets entwickeln
```bash
yarn -w apps/widgets run build
```

## 📚 API Dokumentation

### Core Endpoints

#### Indexierung
```bash
# Dokument hinzufügen
POST /v1/index/:project/:collection
{
  "documents": [{
    "id": "doc1",
    "title": "Titel",
    "content": "Inhalt...",
    "url": "https://example.com/page",
    "lang": "de"
  }]
}

# Dokumente löschen
DELETE /v1/index/:project/:collection
{
  "ids": ["doc1", "doc2"]
}
```

#### Suche
```bash
# Keyword-Suche
POST /v1/search/:project
{
  "q": "Suchbegriff",
  "limit": 10,
  "filters": {"collection": ["pages"]},
  "facets": ["collection", "lang"]
}

# KI-Antwort (RAG)
POST /v1/ask/:project
{
  "q": "Wie funktioniert die Suche?",
  "maxPassages": 6,
  "collections": ["docs"],
  "includeDebug": true
}

# Suchvorschläge
POST /v1/suggest/:project
{
  "q": "Such",
  "limit": 5
}
```

#### Synonyme
```bash
# Synonyme hinzufügen
POST /v1/synonyms/:project
{
  "add": [{
    "terms": ["Auto", "PKW", "Fahrzeug"],
    "lang": "de",
    "type": "synonym"
  }]
}
```

#### Metriken
```bash
# Query-Metriken loggen
POST /v1/metrics/query/:project
{
  "query": "Suchbegriff",
  "results_count": 5,
  "response_time_ms": 120
}

# Analytics abrufen
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
      query="Wie funktioniert die Suche?"
      collections={["docs"]}
      showDebug={true}
    />
  );
}
```

### Programmatischer Client
```typescript
import { PixelcodaSearchClient } from '@pixelcoda/widgets';

const client = new PixelcodaSearchClient(
  'http://localhost:8787',
  'demo',
  'pc_read_dev_key'
);

// Suchen
const results = await client.search({
  q: 'Suchbegriff',
  limit: 10,
  collections: ['pages']
});

// KI-Antwort
const answer = await client.ask({
  q: 'Wie funktioniert das?',
  maxPassages: 6
});

// Metriken loggen
await client.logQuery('Suchbegriff', results.hits.length, 150);
```

## 🔌 TYPO3 Integration

### Extension installieren
1. Kopiere `apps/typo3-connector` nach `typo3conf/ext/pixelcoda_search`
2. Aktiviere die Extension im Extension Manager
3. Konfiguriere API-Endpunkt und Credentials

### Webhook-Konfiguration
```php
// LocalConfiguration.php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] = [
    'api_url' => 'http://localhost:8787',
    'api_key' => 'pc_write_dev_key',
    'hmac_secret' => 'your_hmac_secret',
    'project_id' => 'typo3-site'
];
```

## 🐳 Production Deployment

### Mit Docker Compose
```bash
# Production Setup
NODE_ENV=production docker-compose up -d

# Mit SSL (Traefik/nginx)
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

### Environment Variables (Production)
```bash
# Sichere API Keys generieren
API_READ_KEY=$(openssl rand -hex 32)
API_WRITE_KEY=$(openssl rand -hex 32)
MEILI_MASTER_KEY=$(openssl rand -hex 32)
POSTGRES_PASSWORD=$(openssl rand -hex 16)

# LLM Provider konfigurieren
OPENAI_API_KEY=your_production_key
ENABLE_VECTOR_SEARCH=true
ENABLE_RERANKING=true
```

## 🔒 Sicherheit

### API Key Management
- **Read Keys**: Für Suche und Analytics
- **Write Keys**: Für Indexierung und Admin-Operationen
- **Project Scoping**: Keys können auf Projekte beschränkt werden

### HMAC Webhook Verification
```typescript
// Webhook-Signatur verifizieren
const signature = request.headers['x-pixelcoda-signature'];
const payload = JSON.stringify(request.body);
const expectedSignature = crypto
  .createHmac('sha256', process.env.HMAC_SECRET)
  .update(payload)
  .digest('hex');
```

### Rate Limiting
- Konfigurierbar per Projekt
- Standard: 100 Requests/15min
- Verschiedene Limits für Read/Write Operations

## 📊 Monitoring & Analytics

### Metriken
- **Query Metrics**: Suchbegriffe, Response Times, Result Counts
- **Click Metrics**: Click-Through-Rates, Position Tracking
- **Performance**: API Response Times, Error Rates

### Logging
```bash
# Logs anzeigen
docker-compose logs -f api
docker-compose logs -f worker

# Structured JSON Logging
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
```

## 🤝 Contributing

1. Fork das Repository
2. Feature Branch erstellen (`git checkout -b feature/amazing-feature`)
3. Changes committen (`git commit -m 'Add amazing feature'`)
4. Branch pushen (`git push origin feature/amazing-feature`)
5. Pull Request erstellen

## 📝 Lizenz

Dieses Projekt ist unter der MIT Lizenz veröffentlicht. Siehe [LICENSE](LICENSE) für Details.

## 🆘 Support

- **Issues**: [GitHub Issues](https://github.com/CasianBlanaru/typo3-search/issues)
- **Dokumentation**: [Wiki](https://github.com/CasianBlanaru/typo3-search/wiki)
- **Diskussionen**: [GitHub Discussions](https://github.com/CasianBlanaru/typo3-search/discussions)

---

Entwickelt mit ❤️ von [pixelcoda](https://pixelcoda.com) für die TYPO3 Community.
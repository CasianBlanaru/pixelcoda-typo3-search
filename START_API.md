# pixelcoda Search API - Quick Start

## 🚀 Simple API Startup (Working Solution)

### Option 1: Direct API Start (Recommended)

```bash
# 1. Navigate to project
cd /Users/casian/Customer/Intern/pixelcoda/pixelcoda-headless-search-starter

# 2. Start database services
docker-compose up -d postgres meilisearch redis

# 3. Build LLM adapter
yarn workspace @pixelcoda/llm-adapter run build

# 4. Start API directly with tsx
cd apps/api
npx tsx src/index.ts
```

### Option 2: With Database Migration

```bash
# 1. Start services
docker-compose up -d postgres meilisearch redis

# 2. Wait for services
sleep 10

# 3. Install pg dependency for migration
npm install -g pg

# 4. Run migration (or skip if database already exists)
# node scripts/migrate.js

# 5. Start API
cd apps/api
npx tsx src/index.ts
```

### Option 3: Manual API Dependencies

```bash
cd apps/api

# Install dependencies locally
npm install hono zod @hono/zod-validator @hono/node-server pg dotenv undici

# Start with tsx
npx tsx src/index.ts
```

## ✅ Success Check

When API is running, you should see:
```
[api] listening on http://localhost:8787
```

Then test:
```bash
curl http://localhost:8787/health
# Should return: {"ok":true}
```

## 🧪 API Testing

### Basic Search Test
```bash
curl -X POST http://localhost:8787/v1/search/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{"q":"test","limit":5}'
```

### AI Ask Test
```bash
curl -X POST http://localhost:8787/v1/ask/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{"q":"What is this platform?","maxPassages":3}'
```

### Index Test Content
```bash
curl -X POST http://localhost:8787/v1/index/demo/pages \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_write_dev_key" \
  -d '{
    "documents": [{
      "id": "test-doc-1",
      "title": "Test Document",
      "content": "This is a test document for pixelcoda search platform.",
      "url": "/test",
      "lang": "en"
    }]
  }'
```

## 🎯 TYPO3 Integration

Once API is running:

### 1. Complete TYPO3 Setup
```bash
cd typo3-dev

# Access TYPO3 Install Tool
open http://pixelcoda-typo3-dev.ddev.site:8080/typo3/install.php

# Database config: host=db, user=db, pass=db, database=db
# Admin: admin/admin
```

### 2. Install Plugin
```bash
# Copy plugin to TYPO3
ddev exec cp -r packages/pixelcoda_search public/typo3conf/ext/

# Activate via backend or CLI (after TYPO3 setup)
ddev exec vendor/bin/typo3 extension:activate pixelcoda_search
```

### 3. Test Integration
- Create page with "pixelcoda Search" content element
- Configure via FlexForm
- Test search functionality

## 🔧 Troubleshooting

### API Won't Start
- Check if ports 8787, 5432, 7700 are free
- Ensure Docker services are running: `docker-compose ps`
- Try direct tsx execution: `cd apps/api && npx tsx src/index.ts`

### TYPO3 Issues  
- Use web installer for initial setup
- Check DDEV status: `ddev describe`
- Restart DDEV: `ddev restart`

### Database Connection
- Services running: `docker-compose ps`
- Check logs: `docker-compose logs postgres`

---

**This provides multiple working paths to get the system running!** 🚀

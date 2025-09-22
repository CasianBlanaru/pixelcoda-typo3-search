# pixelcoda Search - Quick Start Guide

Simple step-by-step installation guide with working solutions.

## 🚀 Fast Track Installation

### Option 1: API-Only (Simplest)

```bash
# 1. Clone and setup
git clone git@github.com:CasianBlanaru/typo3-search.git
cd typo3-search

# 2. Install dependencies
yarn install

# 3. Start services
docker-compose up -d postgres meilisearch redis

# 4. Build packages first
yarn workspace @pixelcoda/llm-adapter run build

# 5. Start API
cd apps/api && npm install && npm run dev
```

**✅ API available at: http://localhost:8787**

**Test immediately:**
```bash
# Health check
curl http://localhost:8787/health

# Search test
curl -X POST http://localhost:8787/v1/search/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{"q":"test"}'
```

### Option 2: TYPO3 Integration

**Prerequisites:**
- DDEV installed: `brew install ddev` (macOS) or [other platforms](https://ddev.readthedocs.io/en/stable/#installation)
- Docker Desktop running

**Steps:**

```bash
# 1. Navigate to TYPO3 directory
cd typo3-search/typo3-dev

# 2. Start DDEV project
ddev config --project-type=typo3 --docroot=public --create-docroot
ddev start

# 3. Install TYPO3
ddev composer install

# 4. Manual TYPO3 setup via browser
open http://pixelcoda-typo3-dev.ddev.site:8080/typo3/install.php
```

**Web Installation:**
1. **Database Settings**:
   - Host: `db`
   - Username: `db`
   - Password: `db`
   - Database: `db`

2. **Admin User**:
   - Username: `admin`
   - Password: `admin`
   - Email: `admin@example.com`

3. **After installation**:
```bash
# Copy plugin
ddev exec cp -r packages/pixelcoda_search public/typo3conf/ext/

# Activate extensions via backend or CLI
ddev exec vendor/bin/typo3 extension:activate headless
ddev exec vendor/bin/typo3 extension:activate pixelcoda_search
```

## 🧪 Quick Tests

### API Tests
```bash
# Health
curl http://localhost:8787/health

# Search (JSON:API format)
curl -X POST http://localhost:8787/v1/search/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{
    "q": "search term",
    "limit": 5,
    "page": 1
  }' | jq '.data[0].attributes'

# AI Ask
curl -X POST http://localhost:8787/v1/ask/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{
    "q": "What is this platform?",
    "maxPassages": 3
  }' | jq '.data.attributes.text'
```

### Content Indexing
```bash
# Index a URL
cd typo3-search
yarn workspace @pixelcoda/worker run dev -- crawl https://example.com demo

# Or pull from TYPO3-Headless API
yarn workspace @pixelcoda/worker run dev -- typo3-pull http://pixelcoda-typo3-dev.ddev.site:8080 typo3-dev
```

### TYPO3 Plugin Test
1. **Backend**: http://pixelcoda-typo3-dev.ddev.site:8080/typo3 (admin/admin)
2. **Create page** with "pixelcoda Search" content element
3. **Configure** via FlexForm (Classic or Headless mode)
4. **Test** in frontend

## 🔧 Troubleshooting

### Common Issues

**1. Yarn workspace errors:**
```bash
# Fix yarn workspace issues
rm -f /Users/casian/yarn.lock /Users/casian/package.json
cd typo3-search && yarn install
```

**2. API not starting:**
```bash
# Build dependencies first
yarn workspace @pixelcoda/llm-adapter run build

# Start API manually
cd apps/api
npm install
npm run dev
```

**3. DDEV issues:**
```bash
# Restart DDEV
ddev restart

# Check status
ddev list
ddev describe
```

**4. TYPO3 database connection:**
- Use web installer: http://pixelcoda-typo3-dev.ddev.site:8080/typo3/install.php
- Database host: `db` (not localhost)
- Credentials: db/db

**5. Extension not found:**
```bash
# Copy plugin manually
ddev exec cp -r packages/pixelcoda_search public/typo3conf/ext/
ddev exec vendor/bin/typo3 cache:flush
```

## 📋 What You Get

### API Platform
- **Search API**: Keyword + Vector search
- **AI Ask API**: RAG-powered answers
- **JSON:API 1.0**: Compatible with nuxt-typo3
- **Multi-provider LLM**: OpenAI, Azure, Ollama, HuggingFace

### TYPO3 Integration
- **Classic Plugin**: Fluid templates for TYPO3 frontend
- **Headless Mode**: JSON:API responses for SPAs
- **Real-time Indexing**: Webhooks on content changes
- **Backend Module**: Configuration and monitoring

### Development Tools
- **React Widgets**: For custom frontends
- **CLI Tools**: Content indexing and management
- **Docker Setup**: Production-ready containers
- **DDEV Environment**: Complete TYPO3 development setup

## 🎯 Success Indicators

Installation successful when:
- ✅ `curl http://localhost:8787/health` returns `{"ok": true}`
- ✅ TYPO3 backend accessible at http://pixelcoda-typo3-dev.ddev.site:8080/typo3
- ✅ Search API returns JSON:API responses
- ✅ TYPO3 plugin appears in content element wizard

## 📞 Need Help?

- **Issues**: [GitHub Issues](https://github.com/CasianBlanaru/typo3-search/issues)
- **DDEV Docs**: https://ddev.readthedocs.io/
- **TYPO3 Docs**: https://docs.typo3.org/

---

**Start with Option 1 (API-Only) for quickest results!** 🚀

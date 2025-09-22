# pixelcoda Search Platform - Installation Guide

Complete installation guide for the pixelcoda Search Platform with TYPO3 integration.

## 🚀 Quick Installation

### Option 1: Standalone API (Recommended for testing)

```bash
# Clone repository
git clone git@github.com:CasianBlanaru/typo3-search.git
cd typo3-search

# Install dependencies
yarn install

# Start services
docker-compose up -d postgres meilisearch redis

# Initialize database
node scripts/migrate.js

# Start API server
yarn workspace @pixelcoda/api run dev
```

**API will be available at: http://localhost:8787**

### Option 2: Full TYPO3 Development Environment

```bash
# Prerequisites: DDEV and Docker installed

# Navigate to TYPO3 directory
cd typo3-search/typo3-dev

# Configure DDEV
ddev config --project-type=typo3 --docroot=public --create-docroot --project-name=pixelcoda-typo3-dev

# Start DDEV
ddev start

# Install TYPO3
ddev composer install
ddev exec vendor/bin/typo3 install:setup \
  --no-interaction \
  --database-user-name=db \
  --database-user-password=db \
  --database-host-name=db \
  --database-port=3306 \
  --database-name=db \
  --admin-user-name=admin \
  --admin-password=admin \
  --site-name="pixelcoda TYPO3 Development"

# Copy plugin to extensions directory
cp -r packages/pixelcoda_search public/typo3conf/ext/

# Activate extensions
ddev exec vendor/bin/typo3 extension:activate headless
ddev exec vendor/bin/typo3 extension:activate news
ddev exec vendor/bin/typo3 extension:activate pixelcoda_search

# Import demo content
ddev exec mysql db < demo-content.sql

# Clear caches
ddev exec vendor/bin/typo3 cache:flush
```

**TYPO3 will be available at: https://pixelcoda-typo3-dev.ddev.site**

## 🧪 Testing

### 1. API Testing

```bash
# Health check
curl http://localhost:8787/health

# Search test
curl -X POST http://localhost:8787/v1/search/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{"q":"test","limit":5}'

# AI Ask test
curl -X POST http://localhost:8787/v1/ask/demo \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pc_read_dev_key" \
  -d '{"q":"What is this platform?","maxPassages":3}'
```

### 2. TYPO3 Plugin Testing

1. **Backend Access**: https://pixelcoda-typo3-dev.ddev.site/typo3 (admin/admin)
2. **Create Search Page**:
   - Create new page
   - Add "pixelcoda Search" content element
   - Configure settings via FlexForm
   - Save and view in frontend

3. **Test Search**:
   - Enter search terms
   - Check results display
   - Test AI ask feature

### 3. Content Indexing

```bash
# Index TYPO3 content
cd typo3-dev
ddev exec vendor/bin/typo3 pixelcoda:search:index

# Or index external URL
cd ..
yarn workspace @pixelcoda/worker run dev -- crawl https://example.com demo
```

## 📋 Manual Installation Steps

If automated setup fails, follow these manual steps:

### 1. API Platform Setup

```bash
# Install main dependencies
cd typo3-search
yarn install

# Start database services
docker-compose up -d postgres meilisearch redis

# Wait for services to be ready
sleep 10

# Initialize database
node scripts/migrate.js

# Build API
yarn workspace @pixelcoda/api run build

# Start API server
yarn workspace @pixelcoda/api run dev
```

### 2. TYPO3 Environment Setup

```bash
cd typo3-dev

# Configure DDEV project
ddev config --project-type=typo3 --docroot=public --create-docroot

# Start containers
ddev start

# Install TYPO3 via Composer
ddev composer install

# Run TYPO3 installer
ddev exec vendor/bin/typo3 install:setup \
  --no-interaction \
  --database-user-name=db \
  --database-user-password=db \
  --database-host-name=db \
  --database-port=3306 \
  --database-name=db \
  --admin-user-name=admin \
  --admin-password=admin \
  --site-name="pixelcoda TYPO3 Dev"
```

### 3. Plugin Installation

```bash
# Create extensions directory
ddev exec mkdir -p public/typo3conf/ext

# Copy plugin files
ddev exec cp -r packages/pixelcoda_search public/typo3conf/ext/

# Activate extensions
ddev exec vendor/bin/typo3 extension:activate headless
ddev exec vendor/bin/typo3 extension:activate news  
ddev exec vendor/bin/typo3 extension:activate pixelcoda_search

# Clear caches
ddev exec vendor/bin/typo3 cache:flush
```

### 4. Demo Content

```bash
# Import demo content
ddev exec mysql db < demo-content.sql

# Clear caches
ddev exec vendor/bin/typo3 cache:flush

# Index content
ddev exec vendor/bin/typo3 pixelcoda:search:index
```

## 🎯 Access URLs

After successful installation:

### API Platform
- **Health Check**: http://localhost:8787/health
- **Search API**: http://localhost:8787/v1/search/:project
- **Ask API**: http://localhost:8787/v1/ask/:project
- **Admin API**: http://localhost:8787/v1/admin/health

### TYPO3 Environment
- **Frontend**: https://pixelcoda-typo3-dev.ddev.site
- **Backend**: https://pixelcoda-typo3-dev.ddev.site/typo3
- **Login**: admin / admin
- **Search Page**: https://pixelcoda-typo3-dev.ddev.site/search

### Database Services
- **PostgreSQL**: localhost:5432 (pixelcoda/pixelcoda_dev)
- **Meilisearch**: http://localhost:7700
- **Redis**: localhost:6379

## 🔧 Troubleshooting

### Common Issues

1. **Yarn workspace errors**:
   ```bash
   # Create yarn.lock in project root
   touch yarn.lock
   yarn install
   ```

2. **DDEV not starting**:
   ```bash
   # Check Docker is running
   docker info
   
   # Restart DDEV
   ddev restart
   ```

3. **TYPO3 installation fails**:
   ```bash
   # Check database connection
   ddev exec mysql -e "SELECT 1" db
   
   # Manual setup
   ddev exec vendor/bin/typo3 install:setup --help
   ```

4. **Plugin not found**:
   ```bash
   # Check extension directory
   ddev exec ls -la public/typo3conf/ext/
   
   # Copy plugin manually
   ddev exec cp -r packages/pixelcoda_search public/typo3conf/ext/
   ```

5. **API connection fails**:
   ```bash
   # Check API is running
   curl http://localhost:8787/health
   
   # Check Docker services
   docker-compose ps
   ```

### Debug Commands

```bash
# TYPO3 status
ddev exec vendor/bin/typo3 configuration:show
ddev exec vendor/bin/typo3 extension:list

# Database status
ddev exec mysql -e "SHOW TABLES" db

# API status
curl -v http://localhost:8787/health

# Docker services
docker-compose logs api
docker-compose logs postgres
```

## 📚 Next Steps

After successful installation:

1. **Configure Plugin**:
   - Access TYPO3 backend
   - Go to "Tools" → "pixelcoda Search"
   - Configure API connection
   - Test indexing

2. **Create Search Pages**:
   - Add "pixelcoda Search" content elements
   - Configure via FlexForm
   - Test both classic and headless modes

3. **Index Content**:
   ```bash
   ddev exec vendor/bin/typo3 pixelcoda:search:index
   ```

4. **Test Search**:
   - Frontend search form
   - API endpoints
   - AI ask feature

## 🎉 Success Criteria

Installation is successful when:

- ✅ API health check returns `{"ok": true}`
- ✅ TYPO3 backend accessible with admin/admin
- ✅ pixelcoda Search plugin visible in extension list
- ✅ Search page renders in frontend
- ✅ Search returns results
- ✅ AI ask feature works

---

For additional help, see:
- [TYPO3 Documentation](https://docs.typo3.org/)
- [DDEV Documentation](https://ddev.readthedocs.io/)
- [Project Issues](https://github.com/CasianBlanaru/typo3-search/issues)

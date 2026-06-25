# Railway Deployment Guide

## TYPO3 Backend Deployment

### 1. Datenbank Setup

Erstelle eine MySQL-Datenbank in Railway:
- New → MySQL
- Kopiere die Connection-Details

### 2. TYPO3 Service erstellen

```bash
# Im Repository Root
railway up
```

### 3. Environment Variables setzen

In Railway Dashboard → Variables:

```env
# Database
TYPO3_INSTALL_DB_HOST=containers-us-west-xxx.railway.app
TYPO3_INSTALL_DB_PORT=6379
TYPO3_INSTALL_DB_NAME=railway
TYPO3_INSTALL_DB_USER=root
TYPO3_INSTALL_DB_PASSWORD=xxx

# TYPO3 Context
TYPO3_CONTEXT=Production
TYPO3_INSTALL_SITE_SETUP_TYPE=site

# URLs
TYPO3_SITE_BASE=https://your-typo3-backend.up.railway.app/
TYPO3_FRONTEND_BASE=https://your-nextjs-frontend.up.railway.app/
TYPO3_BACKEND_BASE=https://your-typo3-backend.up.railway.app/

# Install Tool
TYPO3_INSTALL_TOOL_PASSWORD=your-secure-password
```

### 4. Database Import

Nach dem ersten Deploy:

```bash
# Lokal
railway link
railway run mysql -h $TYPO3_INSTALL_DB_HOST -u $TYPO3_INSTALL_DB_USER -p$TYPO3_INSTALL_DB_PASSWORD $TYPO3_INSTALL_DB_NAME < deployment/database-dump.sql
```

### 5. TypoScript Setup

Das Template wurde bereits konfiguriert in `setup-typoscript.sql`

## Next.js Frontend Deployment

### 1. Frontend Service erstellen

```bash
cd frontend
railway up
```

### 2. Environment Variables

In Railway Dashboard → Variables:

```env
NEXT_PUBLIC_API_BASE_URL=https://your-typo3-backend.up.railway.app
NEXT_PUBLIC_TYPO3_BASE_URL=https://your-typo3-backend.up.railway.app
NEXT_PUBLIC_BASE_URL=https://your-nextjs-frontend.up.railway.app
NEXT_PUBLIC_FRONTEND_FILE_API=/headless/fileadmin
NEXT_PUBLIC_SKIN=premium
NEXT_PUBLIC_HEADLESS_DEVTOOLS=false
```

### 3. Build Settings

Railway erkennt `railway.json` automatisch:

```json
{
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "yarn install && yarn build"
  },
  "deploy": {
    "startCommand": "yarn start",
    "healthcheckPath": "/",
    "restartPolicyType": "ON_FAILURE"
  }
}
```

## Test nach Deployment

### TYPO3 Backend

```bash
curl https://your-typo3-backend.up.railway.app/?type=834 | jq '.content[0].content.header'
```

Sollte Content zurückgeben.

### Frontend

```bash
curl https://your-nextjs-frontend.up.railway.app/ | grep "Welcome to TYPO3"
```

Sollte HTML mit Content zurückgeben.

## Extensions auf Railway

Beide Extensions funktionieren automatisch:

1. **GSAP Animation**: 
   - Backend-Felder verfügbar
   - JSON-API liefert Animation-Settings (wenn konfiguriert)
   - Frontend kann Animationen abspielen

2. **Frontend Editing**:
   - `_pixelcoda` Metadata in JSON
   - `backendEditUrl` nur für eingeloggte Backend-User
   - CORS muss für Frontend-Domain konfiguriert sein

## CORS Setup für Railway

In `config/system/additional.php`:

```php
<?php
return [
    'FE' => [
        'additionalHeaders' => [
            'Access-Control-Allow-Origin' => getenv('TYPO3_FRONTEND_BASE'),
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            'Access-Control-Allow-Credentials' => 'true',
        ],
    ],
];
```

## Troubleshooting

### Content wird nicht geliefert

```bash
# TypoScript prüfen
railway run --service typo3 vendor/bin/typo3 cache:flush
```

### Frontend lädt nicht

```bash
# Build-Logs prüfen
railway logs --service frontend

# Environment-Variablen prüfen
railway vars --service frontend
```

### Extensions fehlen

Extensions sind in `packages/` und werden via Composer geladen:

```json
{
  "repositories": [
    { "type": "path", "url": "./packages/*" }
  ]
}
```

## Monitoring

- TYPO3 Health: `https://your-backend.railway.app/typo3/login`
- Frontend Health: `https://your-frontend.railway.app/api/health`
- API Health: `https://your-backend.railway.app/?type=834`

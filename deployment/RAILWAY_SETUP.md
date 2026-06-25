# Railway Deployment Setup

## Required Environment Variables

### TYPO3 Backend Service

```bash
TYPO3_SITE_BASE=https://web-production-581b4.up.railway.app/
TYPO3_BACKEND_BASE=https://web-production-581b4.up.railway.app/
TYPO3_FRONTEND_BASE=https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
TYPO3_CONTEXT=Production
```

### Frontend Service

```bash
NEXT_PUBLIC_API_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_TYPO3_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_BASE_URL=https://nextjs-front-end-for-typo3-headless-production.up.railway.app
NEXT_PUBLIC_FRONTEND_FILE_API=/fileadmin
NEXT_PUBLIC_SKIN=premium
NEXT_PUBLIC_HEADLESS_DEVTOOLS=false
```

## Setup Steps

### 1. After deployment, run setup script

SSH into Railway TYPO3 service and run:

```bash
php deployment/setup-railway.php
```

Or use Railway CLI:

```bash
railway run php deployment/setup-railway.php
```

### 2. Alternative: SQL Import

If you have database access via Railway plugin:

```bash
railway connect <database-service>
# Then run the SQL from deployment/setup-typoscript-template.sql
```

### 3. Clear TYPO3 Cache

```bash
railway run php vendor/bin/typo3 cache:flush
```

### 4. Verify Setup

Check if content is returned:

```bash
curl -H "Accept: application/json" https://web-production-581b4.up.railway.app/
```

The `content` field should contain objects like `colPos0: [...]` instead of empty array.

## Troubleshooting

### No content returned

1. Check if TypoScript template exists in database
2. Ensure `rootPageId: 2` in config/sites/main/config.yaml
3. Flush all caches
4. Verify environment variables are set correctly

### API returns 404

1. Check TYPO3_SITE_BASE environment variable
2. Ensure site configuration has correct base URL
3. Restart Railway service

### Frontend shows "No TYPO3 content"

1. Check API URL in frontend environment variables
2. Test API directly with curl
3. Check browser console for fetch errors

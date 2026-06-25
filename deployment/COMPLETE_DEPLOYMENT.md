# Complete Railway Deployment Guide

This guide will deploy your complete local TYPO3 instance (database + files) to Railway.

## Files Included

- `typo3-database-export.sql.gz` - Complete database dump (112 KB)
- `fileadmin-files.tar.gz` - All uploaded files and images (4.8 MB)
- `import-database.sh` - Database import script
- `extract-fileadmin.sh` - File extraction script
- `update-site-urls.php` - Site configuration updater
- `setup-railway.php` - TypoScript template setup

## Prerequisites

✅ Railway CLI installed: `npm i -g @railway/cli`
✅ Logged into Railway: `railway login`
✅ Project linked: `railway link` (in project directory)

## Deployment Steps

### 1. Push all files to Git

```bash
git add deployment/
git commit -m "Add complete database and file exports for Railway deployment"
git push origin main
```

### 2. Wait for Railway to deploy

Monitor the deployment in Railway dashboard. Wait until both services (TYPO3 + Frontend) are deployed.

### 3. Import Database

**Option A: Via Railway CLI (Recommended)**

```bash
railway run --service=web bash deployment/import-database.sh
```

**Option B: Via Railway Dashboard**

1. Go to Railway Dashboard → Your TYPO3 Service
2. Open Terminal
3. Run: `bash deployment/import-database.sh`

### 4. Extract Fileadmin Files

```bash
railway run --service=web bash deployment/extract-fileadmin.sh
```

Or in Railway Terminal:
```bash
bash deployment/extract-fileadmin.sh
```

### 5. Update Site URLs

```bash
railway run --service=web php deployment/update-site-urls.php
```

### 6. Verify Environment Variables

**TYPO3 Backend Service:**
```
TYPO3_SITE_BASE=https://web-production-581b4.up.railway.app/
TYPO3_BACKEND_BASE=https://web-production-581b4.up.railway.app/
TYPO3_FRONTEND_BASE=https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
TYPO3_CONTEXT=Production
```

**Frontend Service:**
```
NEXT_PUBLIC_API_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_TYPO3_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_BASE_URL=https://nextjs-front-end-for-typo3-headless-production.up.railway.app
NEXT_PUBLIC_FRONTEND_FILE_API=/fileadmin
NEXT_PUBLIC_SKIN=premium
NEXT_PUBLIC_HEADLESS_DEVTOOLS=false
```

### 7. Restart Services

After import, restart both services:

```bash
railway restart --service=web
railway restart --service=frontend
```

Or via Railway Dashboard: Click "Restart" for each service

## Verification

### Test TYPO3 API

```bash
curl -H "Accept: application/json" https://web-production-581b4.up.railway.app/
```

Expected output: JSON with `"content": {"colPos0": [...]}`

### Test Backend Login

1. Go to: https://web-production-581b4.up.railway.app/typo3/
2. Use your local credentials (they were imported with the database)

### Test Frontend

1. Go to: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
2. Should display content from TYPO3
3. Images should load correctly

### Test Image URLs

Images should be accessible at:
```
https://web-production-581b4.up.railway.app/fileadmin/...
```

## Troubleshooting

### Database import fails

Check database credentials in Railway environment variables. Ensure:
- TYPO3_DB_HOST
- TYPO3_DB_USERNAME
- TYPO3_DB_PASSWORD
- TYPO3_DB_DBNAME

are all set correctly.

### Images not loading

1. Verify fileadmin extraction: `ls -la public/fileadmin/`
2. Check file permissions: `chmod -R 755 public/fileadmin`
3. Test direct URL: `curl -I https://web-production-581b4.up.railway.app/fileadmin/...`

### Content still empty

1. Re-run: `php deployment/setup-railway.php`
2. Flush caches: `php vendor/bin/typo3 cache:flush`
3. Check TypoScript template in backend: Site Management → TypoScript

### Frontend shows errors

1. Check browser console for errors
2. Verify API URL in Network tab
3. Test API directly with curl
4. Check environment variables in Frontend service

## Local Development Credentials

After import, you can use your local TYPO3 backend credentials to login on Railway.

Default local admin:
- Check your local backend user or create a new one after import

## Rollback

If you need to rollback:

1. Railway keeps database snapshots
2. Or re-import from a previous export
3. Redeploy previous Git commit

## Updates

To sync future local changes to Railway:

1. Export database: `ddev export-db > deployment/typo3-database-update.sql`
2. Archive fileadmin: `tar -czf deployment/fileadmin-update.tar.gz -C public fileadmin`
3. Git commit and push
4. Re-run import scripts on Railway

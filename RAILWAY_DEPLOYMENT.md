# Railway Deployment Configuration

## TYPO3 Backend Service

**Service URL:** `https://web-production-581b4.up.railway.app`

### Required Environment Variables

```env
# TYPO3 Site Configuration
TYPO3_SITE_BASE=https://web-production-581b4.up.railway.app
TYPO3_FRONTEND_BASE=https://nextjs-front-end-for-typo3-headless-production.up.railway.app

# Database
DATABASE_HOST=<your-mysql-host>
DATABASE_PORT=3306
DATABASE_NAME=<your-database-name>
DATABASE_USER=<your-database-user>
DATABASE_PASSWORD=<your-database-password>

# TYPO3 Configuration
TYPO3_CONTEXT=Production
TYPO3_INSTALL_TOOL_PASSWORD=<hashed-password>
```

### Health Check
- **Endpoint:** `/healthz.php`
- **Timeout:** 180 seconds

---

## Next.js Frontend Service

**Service URL:** `https://nextjs-front-end-for-typo3-headless-production.up.railway.app`

### Required Environment Variables

```env
# TYPO3 Headless API
NEXT_PUBLIC_API_BASE_URL=https://web-production-581b4.up.railway.app/headless
NEXT_PUBLIC_TYPO3_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_BASE_URL=https://nextjs-front-end-for-typo3-headless-production.up.railway.app

# File API
NEXT_PUBLIC_FRONTEND_FILE_API=/headless/fileadmin

# Frontend Configuration
NEXT_PUBLIC_SKIN=premium
NEXT_PUBLIC_HEADLESS_DEVTOOLS=true

# Search API (optional)
NEXT_PUBLIC_SEARCH_API_BASE_URL=https://web-production-581b4.up.railway.app/api/search

# NPM Token (for @pixelcoda packages)
NPM_TOKEN=<your-npm-token>
```

---

## Deployment Steps

### 1. Update TYPO3 Backend Variables
```bash
# In Railway Dashboard → TYPO3 Backend Service → Variables
TYPO3_SITE_BASE=https://web-production-581b4.up.railway.app
TYPO3_FRONTEND_BASE=https://nextjs-front-end-for-typo3-headless-production.up.railway.app
```

### 2. Update Next.js Frontend Variables
```bash
# In Railway Dashboard → Next.js Frontend Service → Variables
NEXT_PUBLIC_API_BASE_URL=https://web-production-581b4.up.railway.app/headless
NEXT_PUBLIC_FRONTEND_FILE_API=/headless/fileadmin
```

### 3. Redeploy Services
1. Go to Railway Dashboard
2. Redeploy TYPO3 Backend service
3. Wait for health check to pass
4. Redeploy Next.js Frontend service

### 4. Verify Deployment
- **Backend Health:** `https://web-production-581b4.up.railway.app/healthz.php`
- **Headless API:** `https://web-production-581b4.up.railway.app/headless`
- **Frontend:** `https://nextjs-front-end-for-typo3-headless-production.up.railway.app`

---

## Troubleshooting

### Frontend shows "TYPO3 API konnte nicht geladen werden"

**Cause:** Backend is returning 500 error or environment variables are incorrect

**Fix:**
1. Check TYPO3 backend logs: `https://web-production-581b4.up.railway.app/healthz.php`
2. Verify environment variables are set correctly in Railway Dashboard
3. Ensure `TYPO3_SITE_BASE` and `TYPO3_FRONTEND_BASE` are set on backend
4. Ensure `NEXT_PUBLIC_API_BASE_URL` includes `/headless` path
5. Redeploy both services after updating variables

### TYPO3 Backend 500 Error

**Common causes:**
- Database connection issues
- Missing `TYPO3_SITE_BASE` or `TYPO3_FRONTEND_BASE` variables
- TYPO3 Install Tool not configured
- File permissions issues

**Fix:**
1. Check Railway logs for TYPO3 service
2. Verify database connection variables
3. Run TYPO3 Install Tool via Railway web console
4. Check `/var/log/typo3_*.log` in container

### Headless Extension Not Working

**Fix:**
1. Verify headless extension is active: Check composer.json shows `friendsoftypo3/headless: ^5.0@RC`
2. Clear TYPO3 caches: `vendor/bin/typo3 cache:flush`
3. Check site configuration includes `headless: 1` and `renderingMode: headless`

---

## Current Configuration

### Site Configuration (`config/sites/main/config.yaml`)
```yaml
base: '%env(TYPO3_SITE_BASE)%'
frontendBase: '%env(TYPO3_FRONTEND_BASE)%'
headless: 1
customConfiguration:
  renderingMode: headless
```

### Dependencies
- TYPO3: 14.3
- friendsoftypo3/headless: v5.0.0-rc1
- Next.js: 16.2.9
- React: 19.2.7

---

## Next Steps After Variable Update

After setting the environment variables in Railway Dashboard:

1. **Backend should be accessible at:**
   - Health: https://web-production-581b4.up.railway.app/healthz.php
   - Headless API: https://web-production-581b4.up.railway.app/headless

2. **Frontend should load successfully at:**
   - https://nextjs-front-end-for-typo3-headless-production.up.railway.app

3. **If issues persist:**
   - Check Railway deployment logs
   - Verify all environment variables are saved
   - Ensure TYPO3 database is accessible
   - Contact Railway support if infrastructure issues

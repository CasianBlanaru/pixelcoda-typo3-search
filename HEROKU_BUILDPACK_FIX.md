# Heroku Buildpack Detection Fix

## Problem
The application was failing to deploy on Heroku with the error:
```
ERROR: Application not supported by this buildpack!
The 'heroku/php' buildpack is set on this application, but was
unable to detect a PHP codebase.
```

## Root Cause
This is primarily a **Node.js application** (runs `node simple-api.js` via Procfile), but Heroku was configured with the **PHP buildpack** instead of the Node.js buildpack.

## Solution Applied

### 1. Created `composer.json` (Immediate Fix)
Added a minimal `composer.json` file at the root to satisfy the PHP buildpack's detection requirements. This allows the current deployment to succeed while maintaining the PHP components for TYPO3 integration.

### 2. Proper Buildpack Configuration (Recommended)
The application should use the **Node.js buildpack** as the primary buildpack since:
- Main entry point: `simple-api.js` (Node.js)
- Primary dependencies: `package.json` (Node.js)
- Procfile runs: `web: node simple-api.js`

## How to Fix Buildpack Configuration

### Option A: Using Heroku CLI
```bash
# Clear all buildpacks
heroku buildpacks:clear --app YOUR_APP_NAME

# Set Node.js as the primary buildpack
heroku buildpacks:set heroku/nodejs --app YOUR_APP_NAME

# Verify configuration
heroku buildpacks --app YOUR_APP_NAME
```

### Option B: Using the provided script
```bash
./fix-buildpacks.sh
```

### Option C: Multi-buildpack approach (if PHP is needed)
If you need both Node.js and PHP capabilities:
```bash
heroku buildpacks:clear --app YOUR_APP_NAME
heroku buildpacks:add heroku/nodejs --app YOUR_APP_NAME
heroku buildpacks:add heroku/php --app YOUR_APP_NAME
```

## Project Structure
- **Root**: Node.js application (`simple-api.js`, `package.json`)
- **`apps/typo3-connector/`**: PHP TYPO3 extension with its own `composer.json`
- **`typo3-dev/`**: TYPO3 development environment with PHP files

## Files Created/Modified
- ✅ `composer.json` - Added at root for PHP buildpack compatibility
- ✅ `HEROKU_BUILDPACK_FIX.md` - This documentation
- ✅ Updated `fix-buildpacks.sh` - Enhanced script for buildpack management

## Deployment Verification
After applying the fix, verify deployment works:
```bash
git add .
git commit -m "Fix: Add composer.json for Heroku PHP buildpack compatibility"
git push heroku main
```

## Testing the Deployed Application
```bash
# Health check
curl https://your-app.herokuapp.com/health

# Test search endpoint
curl -X POST https://your-app.herokuapp.com/v1/search/demo \
  -H "Content-Type: application/json" \
  -d '{"q":"test"}'
```

## Long-term Recommendation
Consider migrating to Node.js buildpack only, as this is primarily a Node.js application. The PHP components are specific to TYPO3 integration and could be handled separately if needed.
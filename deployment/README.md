# Railway Deployment - Quick Start

## Current Problem
- Frontend shows "No TYPO3 content returned"
- Backend login doesn't work
- API returns 500 error

## Solution: Import Database

### Step-by-Step Instructions

#### 1. Open Railway Terminal
1. Go to https://railway.app
2. Select your project
3. Click on the TYPO3 service (web-production-581b4)
4. Click "Terminal" tab at the top

#### 2. Run Setup Command
Copy and paste this into the Railway terminal:

```bash
bash deployment/simple-setup.sh
```

Wait for completion (30-60 seconds).

#### 3. Restart Service
After setup completes:
1. Go back to Railway dashboard
2. Click on TYPO3 service
3. Click "Restart" button
4. Wait for service to restart (~30 seconds)

#### 4. Also Restart Frontend
1. Click on Frontend service
2. Click "Restart" button

#### 5. Test

**Backend Login:**
- URL: https://web-production-581b4.up.railway.app/typo3/
- Username: `pixelcoda`
- Password: `Pixelcoda123!`

Or try:
- Username: `admin`
- Password: (your local admin password)

**Frontend:**
- URL: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
- Should now show content

**API Test:**
```bash
curl -H "Accept: application/json" https://web-production-581b4.up.railway.app/
```

Should return JSON with content in `colPos0` array.

## Troubleshooting

### If database import fails
Check database credentials in Railway environment variables:
- TYPO3_DB_HOST
- TYPO3_DB_USERNAME
- TYPO3_DB_PASSWORD
- TYPO3_DB_DBNAME

### If files are missing
Ensure the git deployment completed. Check that these files exist in Railway:
```bash
ls -lh deployment/typo3-database-export.sql.gz
ls -lh deployment/fileadmin-files.tar.gz
```

### If login still fails
The password is hashed in the database. Use the same password you use locally.

To reset password via CLI:
```bash
php vendor/bin/typo3 backend:user:create pixelcoda NewPassword123! --admin
```

### Run diagnostics
```bash
bash deployment/diagnose.sh
```

## What Gets Imported

✅ All pages and content elements
✅ All backend users (with passwords)
✅ All TYPO3 configuration
✅ All TypoScript templates
✅ All images and files from fileadmin
✅ Site configuration

## After Successful Import

You can remove the large files from git:
```bash
git rm deployment/typo3-database-export.sql.gz
git rm deployment/fileadmin-files.tar.gz
git commit -m "Remove data files after import"
git push origin main
```

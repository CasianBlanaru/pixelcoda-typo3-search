#!/bin/bash
# INLINE RAILWAY SETUP
# Copy this entire script and paste into Railway Terminal

echo "======================================"
echo "TYPO3 Railway Setup (Inline)"
echo "======================================"

# First, check if we have the files
echo ""
echo "Checking deployment files..."
ls -lh deployment/ 2>/dev/null || echo "Deployment folder not found yet"

# If deployment folder doesn't exist, wait for git deployment
if [ ! -d "deployment" ]; then
    echo ""
    echo "Deployment files not available yet."
    echo "Pulling latest code..."
    git fetch origin main
    git reset --hard origin/main
fi

# Check again
if [ ! -f "deployment/typo3-database-export.sql.gz" ]; then
    echo ""
    echo "ERROR: Database file still not found!"
    echo "Please ensure git deployment completed on Railway."
    echo ""
    echo "Try manually:"
    echo "  git pull origin main"
    echo ""
    exit 1
fi

echo "✓ Files found"
echo ""

# Import database
echo "======================================"
echo "Importing Database..."
echo "======================================"

gunzip -c deployment/typo3-database-export.sql.gz | \
    mysql -h"${TYPO3_DB_HOST}" \
          -P"${TYPO3_DB_PORT:-3306}" \
          -u"${TYPO3_DB_USERNAME}" \
          -p"${TYPO3_DB_PASSWORD}" \
          "${TYPO3_DB_DBNAME}"

if [ $? -eq 0 ]; then
    echo "✓ Database imported successfully"
else
    echo "✗ Database import failed"
    exit 1
fi

# Extract files
echo ""
echo "======================================"
echo "Extracting Files..."
echo "======================================"

if [ -f "deployment/fileadmin-files.tar.gz" ]; then
    tar -xzf deployment/fileadmin-files.tar.gz -C public/
    chmod -R 755 public/fileadmin
    echo "✓ Files extracted"
else
    echo "⚠ Fileadmin archive not found"
fi

# Clear caches
echo ""
echo "======================================"
echo "Clearing Caches..."
echo "======================================"

rm -rf var/cache/*
php vendor/bin/typo3 cache:flush 2>/dev/null

echo "✓ Caches cleared"

# Show results
echo ""
echo "======================================"
echo "✓ Setup Complete!"
echo "======================================"
echo ""

PAGES=$(mysql -h"${TYPO3_DB_HOST}" -P"${TYPO3_DB_PORT:-3306}" \
             -u"${TYPO3_DB_USERNAME}" -p"${TYPO3_DB_PASSWORD}" \
             "${TYPO3_DB_DBNAME}" \
             -se 'SELECT COUNT(*) FROM pages WHERE deleted=0;' 2>/dev/null || echo '?')

USERS=$(mysql -h"${TYPO3_DB_HOST}" -P"${TYPO3_DB_PORT:-3306}" \
             -u"${TYPO3_DB_USERNAME}" -p"${TYPO3_DB_PASSWORD}" \
             "${TYPO3_DB_DBNAME}" \
             -se 'SELECT COUNT(*) FROM be_users WHERE deleted=0;' 2>/dev/null || echo '?')

echo "Imported:"
echo "  - Pages: $PAGES"
echo "  - Backend Users: $USERS"
echo ""
echo "IMPORTANT: Now restart the Railway service!"
echo "Then test:"
echo "  - Backend: https://web-production-581b4.up.railway.app/typo3/"
echo "  - Frontend: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/"

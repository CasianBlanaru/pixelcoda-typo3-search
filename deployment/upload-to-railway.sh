#!/bin/bash
# Upload database and files to Railway using Railway CLI
# Run locally: bash deployment/upload-to-railway.sh

set -e

echo "=========================================="
echo "Uploading Data to Railway"
echo "=========================================="
echo ""

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "ERROR: Railway CLI not found"
    echo "Install with: npm i -g @railway/cli"
    exit 1
fi

# Check if logged in
if ! railway whoami &> /dev/null; then
    echo "Not logged into Railway. Running login..."
    railway login
fi

echo "Step 1: Uploading database dump..."
railway run --service=web bash -c "cat > /tmp/db-import.sql.gz" < deployment/typo3-database-export.sql.gz
echo "✓ Database uploaded to /tmp/db-import.sql.gz"

echo ""
echo "Step 2: Uploading fileadmin archive..."
railway run --service=web bash -c "cat > /tmp/fileadmin.tar.gz" < deployment/fileadmin-files.tar.gz
echo "✓ Fileadmin uploaded to /tmp/fileadmin.tar.gz"

echo ""
echo "Step 3: Importing database..."
railway run --service=web bash -c "
    gunzip -c /tmp/db-import.sql.gz | mysql -h\$TYPO3_DB_HOST -P\${TYPO3_DB_PORT:-3306} -u\$TYPO3_DB_USERNAME -p\$TYPO3_DB_PASSWORD \$TYPO3_DB_DBNAME
    rm /tmp/db-import.sql.gz
    echo 'Database imported successfully'
"

echo ""
echo "Step 4: Extracting fileadmin..."
railway run --service=web bash -c "
    cd public
    tar -xzf /tmp/fileadmin.tar.gz
    chmod -R 755 fileadmin
    rm /tmp/fileadmin.tar.gz
    echo 'Fileadmin extracted successfully'
"

echo ""
echo "Step 5: Updating site configuration..."
railway run --service=web php deployment/update-site-urls.php

echo ""
echo "Step 6: Flushing caches..."
railway run --service=web php vendor/bin/typo3 cache:flush

echo ""
echo "=========================================="
echo "✓ Deployment completed successfully!"
echo "=========================================="
echo ""
echo "Verify:"
echo "  API: curl -H 'Accept: application/json' https://web-production-581b4.up.railway.app/"
echo "  Backend: https://web-production-581b4.up.railway.app/typo3/"
echo "  Frontend: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/"

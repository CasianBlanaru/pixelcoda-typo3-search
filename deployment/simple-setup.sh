#!/bin/bash
# Simple Railway Setup - Run in Railway Terminal
# bash deployment/simple-setup.sh

echo "======================================"
echo "TYPO3 Railway Simple Setup"
echo "======================================"
echo ""

# Check if files exist
if [ ! -f "deployment/typo3-database-export.sql.gz" ]; then
    echo "ERROR: Database file missing!"
    echo "Check if git deployment completed."
    exit 1
fi

echo "Step 1: Importing database..."
echo "This may take 30-60 seconds..."
gunzip -c deployment/typo3-database-export.sql.gz | \
    mysql -h"${TYPO3_DB_HOST}" \
          -P"${TYPO3_DB_PORT:-3306}" \
          -u"${TYPO3_DB_USERNAME}" \
          -p"${TYPO3_DB_PASSWORD}" \
          "${TYPO3_DB_DBNAME}"

if [ $? -eq 0 ]; then
    echo "✓ Database imported"
else
    echo "✗ Database import failed"
    exit 1
fi

echo ""
echo "Step 2: Extracting files..."
if [ -f "deployment/fileadmin-files.tar.gz" ]; then
    tar -xzf deployment/fileadmin-files.tar.gz -C public/
    chmod -R 755 public/fileadmin
    echo "✓ Files extracted"
else
    echo "⚠ Warning: fileadmin archive not found"
fi

echo ""
echo "Step 3: Clearing caches..."
rm -rf var/cache/*
php vendor/bin/typo3 cache:flush 2>/dev/null || echo "Cache flush via CLI skipped"

echo ""
echo "======================================"
echo "✓ Setup Complete!"
echo "======================================"
echo ""
echo "Pages imported: $(mysql -h"${TYPO3_DB_HOST}" -P"${TYPO3_DB_PORT:-3306}" -u"${TYPO3_DB_USERNAME}" -p"${TYPO3_DB_PASSWORD}" "${TYPO3_DB_DBNAME}" -se 'SELECT COUNT(*) FROM pages WHERE deleted=0;' 2>/dev/null || echo '?')"
echo ""
echo "NOW: Restart the Railway service!"
echo "Then test: https://web-production-581b4.up.railway.app/"

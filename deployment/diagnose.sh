#!/bin/bash
# Diagnostic script for Railway TYPO3
# Run in Railway Terminal: bash deployment/diagnose.sh

echo "=========================================="
echo "TYPO3 Railway Diagnostics"
echo "=========================================="
echo ""

echo "1. Checking environment variables..."
echo "   TYPO3_DB_HOST: $TYPO3_DB_HOST"
echo "   TYPO3_DB_DBNAME: $TYPO3_DB_DBNAME"
echo "   TYPO3_SITE_BASE: $TYPO3_SITE_BASE"
echo "   TYPO3_FRONTEND_BASE: $TYPO3_FRONTEND_BASE"
echo ""

echo "2. Checking if deployment files exist..."
if [ -f "deployment/typo3-database-export.sql.gz" ]; then
    echo "   ✓ Database file found: $(du -h deployment/typo3-database-export.sql.gz | cut -f1)"
else
    echo "   ✗ Database file NOT found"
fi

if [ -f "deployment/fileadmin-files.tar.gz" ]; then
    echo "   ✓ Fileadmin archive found: $(du -h deployment/fileadmin-files.tar.gz | cut -f1)"
else
    echo "   ✗ Fileadmin archive NOT found"
fi
echo ""

echo "3. Checking database connection..."
if mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" "$TYPO3_DB_DBNAME" -e "SELECT 1;" &>/dev/null; then
    echo "   ✓ Database connection successful"
    
    echo ""
    echo "4. Checking if pages table has data..."
    PAGE_COUNT=$(mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" "$TYPO3_DB_DBNAME" -se "SELECT COUNT(*) FROM pages WHERE deleted=0;" 2>/dev/null || echo "0")
    echo "   Pages in database: $PAGE_COUNT"
    
    echo ""
    echo "5. Checking if sys_template exists..."
    TEMPLATE_COUNT=$(mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" "$TYPO3_DB_DBNAME" -se "SELECT COUNT(*) FROM sys_template;" 2>/dev/null || echo "0")
    echo "   TypoScript templates: $TEMPLATE_COUNT"
else
    echo "   ✗ Database connection FAILED"
fi
echo ""

echo "6. Checking site configuration..."
if [ -f "config/sites/main/config.yaml" ]; then
    echo "   ✓ Site configuration exists"
    echo "   Content:"
    head -10 config/sites/main/config.yaml | sed 's/^/     /'
else
    echo "   ✗ Site configuration NOT found"
fi
echo ""

echo "7. Checking TYPO3 cache directory..."
if [ -d "var/cache" ]; then
    echo "   ✓ Cache directory exists"
    CACHE_SIZE=$(du -sh var/cache 2>/dev/null | cut -f1)
    echo "   Size: $CACHE_SIZE"
else
    echo "   ✗ Cache directory NOT found"
fi
echo ""

echo "=========================================="
echo "Diagnostics Complete"
echo "=========================================="
echo ""
echo "If pages count is 0, run: bash deployment/import-database.sh"
echo "If files are missing, ensure git deployment completed"
echo "If site config is wrong, run: php deployment/update-site-urls.php"

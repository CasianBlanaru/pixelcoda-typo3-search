#!/bin/bash
# Complete Railway Setup - Run this ONCE after deployment
# Execute in Railway Terminal: bash deployment/complete-setup.sh

set -e

echo "=========================================="
echo "TYPO3 Railway Complete Setup"
echo "=========================================="
echo ""
echo "This will:"
echo "  1. Import database"
echo "  2. Extract fileadmin files"
echo "  3. Update site URLs"
echo "  4. Setup TypoScript template"
echo "  5. Clear all caches"
echo ""
echo "Press Ctrl+C to cancel, or wait 5 seconds to continue..."
sleep 5

# Step 1: Import Database
echo ""
echo "=========================================="
echo "Step 1/5: Importing Database"
echo "=========================================="

if [ ! -f "deployment/typo3-database-export.sql.gz" ]; then
    echo "ERROR: Database file not found!"
    echo "Ensure git deployment completed successfully."
    exit 1
fi

echo "Decompressing and importing database..."
gunzip -c deployment/typo3-database-export.sql.gz | \
    mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" \
          -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" \
          "$TYPO3_DB_DBNAME"

echo "✓ Database imported successfully"

# Step 2: Extract Fileadmin
echo ""
echo "=========================================="
echo "Step 2/5: Extracting Fileadmin Files"
echo "=========================================="

if [ ! -f "deployment/fileadmin-files.tar.gz" ]; then
    echo "WARNING: Fileadmin file not found!"
    echo "Images may not display. Continue anyway..."
else
    echo "Extracting files to public/fileadmin..."
    cd public
    tar -xzf ../deployment/fileadmin-files.tar.gz
    chmod -R 755 fileadmin
    cd ..
    echo "✓ Fileadmin extracted successfully"
fi

# Step 3: Update Site URLs
echo ""
echo "=========================================="
echo "Step 3/5: Updating Site Configuration"
echo "=========================================="

if [ -f "deployment/update-site-urls.php" ]; then
    php deployment/update-site-urls.php
else
    echo "Manual URL update needed..."
    # Fallback: direct file edit
    if [ -f "config/sites/main/config.yaml" ]; then
        sed -i "s|base: .*|base: '${TYPO3_SITE_BASE:-https://web-production-581b4.up.railway.app/}'|g" config/sites/main/config.yaml
        sed -i "s|frontendBase: .*|frontendBase: '${TYPO3_FRONTEND_BASE:-https://nextjs-front-end-for-typo3-headless-production.up.railway.app/}'|g" config/sites/main/config.yaml
        echo "✓ Site URLs updated via sed"
    fi
fi

# Step 4: Setup TypoScript Template (if needed)
echo ""
echo "=========================================="
echo "Step 4/5: Checking TypoScript Template"
echo "=========================================="

TEMPLATE_COUNT=$(mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" \
                        -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" \
                        "$TYPO3_DB_DBNAME" \
                        -se "SELECT COUNT(*) FROM sys_template WHERE pid=2;" 2>/dev/null || echo "0")

if [ "$TEMPLATE_COUNT" -eq "0" ]; then
    echo "No template found, creating..."
    if [ -f "deployment/setup-railway.php" ]; then
        php deployment/setup-railway.php
    else
        # Fallback: direct SQL insert
        mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" \
              -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" \
              "$TYPO3_DB_DBNAME" \
              -e "INSERT INTO sys_template (pid, title, root, clear, include_static_file, crdate, tstamp) 
                  VALUES (2, 'Main Template', 1, 3, 'EXT:headless/Configuration/TypoScript/,EXT:pixelcoda_sitepackage/Configuration/TypoScript/', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());"
        echo "✓ TypoScript template created via SQL"
    fi
else
    echo "✓ TypoScript template already exists ($TEMPLATE_COUNT found)"
fi

# Step 5: Clear All Caches
echo ""
echo "=========================================="
echo "Step 5/5: Clearing Caches"
echo "=========================================="

rm -rf var/cache/*
php vendor/bin/typo3 cache:flush

echo "✓ All caches cleared"

# Final Check
echo ""
echo "=========================================="
echo "Setup Complete! Running Final Checks..."
echo "=========================================="
echo ""

PAGE_COUNT=$(mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" \
                    -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" \
                    "$TYPO3_DB_DBNAME" \
                    -se "SELECT COUNT(*) FROM pages WHERE deleted=0;" 2>/dev/null || echo "0")

CONTENT_COUNT=$(mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" \
                       -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" \
                       "$TYPO3_DB_DBNAME" \
                       -se "SELECT COUNT(*) FROM tt_content WHERE deleted=0;" 2>/dev/null || echo "0")

echo "Statistics:"
echo "  - Pages: $PAGE_COUNT"
echo "  - Content elements: $CONTENT_COUNT"
echo "  - TypoScript templates: $TEMPLATE_COUNT"
echo ""

if [ -d "public/fileadmin" ]; then
    FILEADMIN_SIZE=$(du -sh public/fileadmin 2>/dev/null | cut -f1)
    echo "  - Fileadmin size: $FILEADMIN_SIZE"
fi

echo ""
echo "=========================================="
echo "✓ Setup Completed Successfully!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "  1. Restart Railway service in dashboard"
echo "  2. Test API: curl -H 'Accept: application/json' $TYPO3_SITE_BASE"
echo "  3. Test Backend: ${TYPO3_SITE_BASE}typo3/"
echo "  4. Test Frontend: $TYPO3_FRONTEND_BASE"
echo ""
echo "Your local login credentials should work on Railway."

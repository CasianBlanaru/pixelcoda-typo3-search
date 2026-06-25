#!/bin/bash
# Import local database to Railway
# Run this on Railway: bash deployment/import-database.sh

set -e

echo "=========================================="
echo "Importing TYPO3 Database to Railway"
echo "=========================================="

# Check if database credentials are set
if [ -z "$TYPO3_DB_HOST" ] || [ -z "$TYPO3_DB_USERNAME" ] || [ -z "$TYPO3_DB_PASSWORD" ] || [ -z "$TYPO3_DB_DBNAME" ]; then
    echo "ERROR: Database credentials not set in environment variables"
    exit 1
fi

# Uncompress database dump if needed
if [ -f "deployment/typo3-database-export.sql.gz" ]; then
    echo "Decompressing database dump..."
    gunzip -c deployment/typo3-database-export.sql.gz > deployment/typo3-database-import.sql
    DB_FILE="deployment/typo3-database-import.sql"
elif [ -f "deployment/typo3-database-export.sql" ]; then
    DB_FILE="deployment/typo3-database-export.sql"
else
    echo "ERROR: Database dump file not found"
    exit 1
fi

echo "Importing database..."
mysql -h"$TYPO3_DB_HOST" -P"${TYPO3_DB_PORT:-3306}" -u"$TYPO3_DB_USERNAME" -p"$TYPO3_DB_PASSWORD" "$TYPO3_DB_DBNAME" < "$DB_FILE"

echo "Cleaning up temporary files..."
rm -f deployment/typo3-database-import.sql

echo "Flushing TYPO3 caches..."
php vendor/bin/typo3 cache:flush

echo "=========================================="
echo "✓ Database import completed successfully!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Update site configuration base URLs if needed"
echo "2. Test: curl -H 'Accept: application/json' https://web-production-581b4.up.railway.app/"

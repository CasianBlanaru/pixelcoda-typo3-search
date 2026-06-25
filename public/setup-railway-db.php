<?php
/**
 * Railway Database Setup Script
 * Access via: https://web-production-581b4.up.railway.app/setup-railway-db.php
 * Or CLI: php public/setup-railway-db.php
 */

// Security: Only allow in Production context or with secret key
$allowedKey = getenv('TYPO3_SETUP_KEY') ?: 'setup-typo3-railway-2024';
$providedKey = $_GET['key'] ?? $_SERVER['argv'][1] ?? '';

if ($providedKey !== $allowedKey) {
    die("ERROR: Invalid key. Usage: ?key=$allowedKey\n");
}

echo "========================================\n";
echo "TYPO3 Railway Database Setup\n";
echo "========================================\n\n";

// Database credentials from environment
$dbHost = getenv('TYPO3_DB_HOST');
$dbPort = getenv('TYPO3_DB_PORT') ?: '3306';
$dbUser = getenv('TYPO3_DB_USERNAME');
$dbPass = getenv('TYPO3_DB_PASSWORD');
$dbName = getenv('TYPO3_DB_DBNAME');

echo "Connecting to database...\n";
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}
echo "✓ Connected\n\n";

// Check if database is empty or has data
$result = $mysqli->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema = '$dbName'");
$row = $result->fetch_assoc();
$tableCount = $row['cnt'];

echo "Current tables in database: $tableCount\n";

if ($tableCount > 50) {
    echo "\n⚠ Database already has tables. Pages count: ";
    $result = $mysqli->query("SELECT COUNT(*) as cnt FROM pages WHERE deleted=0");
    if ($result) {
        $row = $result->fetch_assoc();
        echo $row['cnt'] . "\n";
    }
    echo "\nDatabase appears to be already set up.\n";
    echo "If you need to re-import, first drop all tables.\n";
    $mysqli->close();
    exit(0);
}

echo "\n========================================\n";
echo "Database appears empty. Manual import needed.\n";
echo "========================================\n\n";

echo "INSTRUCTIONS:\n";
echo "1. Download database from local:\n";
echo "   Run locally: ddev export-db > local-db.sql\n\n";
echo "2. Import via Railway MySQL plugin:\n";
echo "   - Install 'MySQL' plugin in Railway\n";
echo "   - Connect with provided credentials\n";
echo "   - Import local-db.sql\n\n";
echo "OR\n\n";
echo "3. Use Railway CLI:\n";
echo "   railway run mysql -h\$TYPO3_DB_HOST -u\$TYPO3_DB_USERNAME -p\$TYPO3_DB_PASSWORD \$TYPO3_DB_DBNAME < local-db.sql\n\n";

echo "After import, run this script again to verify.\n";

$mysqli->close();

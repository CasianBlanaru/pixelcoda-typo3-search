<?php
/**
 * Railway Import Script
 * Execute: php public/railway-import.php <secret-key>
 * Or web: https://web-production-581b4.up.railway.app/railway-import.php?key=<secret-key>
 */

set_time_limit(600); // 10 minutes
ini_set('memory_limit', '512M');

$secretKey = 'pixelcoda-railway-2024';
$providedKey = $_GET['key'] ?? ($_SERVER['argv'][1] ?? '');

if ($providedKey !== $secretKey) {
    die("ERROR: Provide key as: ?key=$secretKey or php railway-import.php $secretKey\n");
}

echo "==========================================\n";
echo "TYPO3 Railway Import\n";
echo "==========================================\n\n";

// Get DB credentials
$dbHost = getenv('TYPO3_DB_HOST');
$dbPort = getenv('TYPO3_DB_PORT') ?: '3306';
$dbUser = getenv('TYPO3_DB_USERNAME');
$dbPass = getenv('TYPO3_DB_PASSWORD');
$dbName = getenv('TYPO3_DB_DBNAME');

if (!$dbHost || !$dbUser || !$dbPass || !$dbName) {
    die("ERROR: Database credentials not set in environment\n");
}

// Step 1: Import Database
echo "Step 1: Importing Database\n";
echo "-------------------------------------------\n";

$dbFile = __DIR__ . '/db-import.sql.gz';
if (!file_exists($dbFile)) {
    die("ERROR: Database file not found at: $dbFile\n");
}

echo "Database file: " . round(filesize($dbFile) / 1024) . " KB\n";
echo "Decompressing and importing...\n";

$cmd = sprintf(
    'gunzip -c %s | mysql -h"%s" -P"%s" -u"%s" -p"%s" "%s" 2>&1',
    escapeshellarg($dbFile),
    escapeshellarg($dbHost),
    escapeshellarg($dbPort),
    escapeshellarg($dbUser),
    escapeshellarg($dbPass),
    escapeshellarg($dbName)
);

exec($cmd, $output, $returnCode);

if ($returnCode === 0) {
    echo "✓ Database imported successfully\n";
} else {
    echo "✗ Database import failed:\n";
    echo implode("\n", $output) . "\n";
    exit(1);
}

// Verify import
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if (!$mysqli->connect_error) {
    $result = $mysqli->query("SELECT COUNT(*) as cnt FROM pages WHERE deleted=0");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Pages imported: " . $row['cnt'] . "\n";
    }
    $mysqli->close();
}

echo "\n";

// Step 2: Extract Fileadmin
echo "Step 2: Extracting Fileadmin\n";
echo "-------------------------------------------\n";

$fileadminArchive = __DIR__ . '/fileadmin-import.tar.gz';
if (!file_exists($fileadminArchive)) {
    echo "⚠ Fileadmin archive not found, skipping\n";
} else {
    echo "Archive file: " . round(filesize($fileadminArchive) / 1024 / 1024, 1) . " MB\n";
    echo "Extracting to public/...\n";
    
    $cmd = sprintf(
        'tar -xzf %s -C %s 2>&1',
        escapeshellarg($fileadminArchive),
        escapeshellarg(__DIR__)
    );
    
    exec($cmd, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✓ Files extracted\n";
        exec('chmod -R 755 ' . escapeshellarg(__DIR__ . '/fileadmin'));
        echo "✓ Permissions set\n";
    } else {
        echo "⚠ File extraction had issues:\n";
        echo implode("\n", $output) . "\n";
    }
}

echo "\n";

// Step 3: Clear Caches
echo "Step 3: Clearing Caches\n";
echo "-------------------------------------------\n";

$varCache = dirname(__DIR__) . '/var/cache';
if (is_dir($varCache)) {
    exec('rm -rf ' . escapeshellarg($varCache) . '/*');
    echo "✓ Cache directory cleared\n";
}

// Try TYPO3 cache flush
$typo3Binary = dirname(__DIR__) . '/vendor/bin/typo3';
if (file_exists($typo3Binary)) {
    exec("php $typo3Binary cache:flush 2>&1", $output, $returnCode);
    if ($returnCode === 0) {
        echo "✓ TYPO3 caches flushed\n";
    }
}

echo "\n";
echo "==========================================\n";
echo "✓ Import Complete!\n";
echo "==========================================\n\n";

echo "Next steps:\n";
echo "1. Restart Railway service\n";
echo "2. Test backend: https://web-production-581b4.up.railway.app/typo3/\n";
echo "3. Test frontend: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/\n";
echo "\nLogin: pixelcoda / Pixelcoda123!\n";

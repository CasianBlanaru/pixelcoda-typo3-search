<?php

declare(strict_types=1);

// Direct MySQL connection without TYPO3 Bootstrap
$dbHost = getenv('TYPO3_DB_HOST') ?: 'mysql.railway.internal';
$dbPort = getenv('TYPO3_DB_PORT') ?: '3306';
$dbName = getenv('TYPO3_DB_DBNAME') ?: 'railway';
$dbUser = getenv('TYPO3_DB_USERNAME') ?: 'root';
$dbPass = getenv('TYPO3_DB_PASSWORD') ?: '';

if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
    echo "Database not configured. Skipping demo content.\n";
    exit(0);
}

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    exit(0);
}

// Find homepage (doktype=1, is_siteroot=1)
$stmt = $pdo->prepare('SELECT uid FROM pages WHERE doktype = 1 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC LIMIT 1');
$stmt->execute();
$homepageUid = $stmt->fetchColumn();

if (!$homepageUid) {
    echo "Homepage not found. Skipping demo content.\n";
    exit(0);
}

echo "Found homepage with uid={$homepageUid}\n";

// Check if content already exists
$stmt = $pdo->prepare('SELECT COUNT(*) FROM tt_content WHERE pid = :pid AND deleted = 0');
$stmt->execute(['pid' => $homepageUid]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo "Content already exists on page {$homepageUid} (found {$count} elements). Skipping.\n";
    exit(0);
}

echo "Adding demo content to homepage (pid={$homepageUid})...\n";

$timestamp = time();

$contentElements = [
    [
        'pid' => $homepageUid,
        'colPos' => 0,
        'sorting' => 256,
        'CType' => 'header',
        'header' => 'Welcome to TYPO3 Headless',
        'header_layout' => 1,
        'tstamp' => $timestamp,
        'crdate' => $timestamp,
        'sys_language_uid' => 0,
        'hidden' => 0,
        'deleted' => 0,
    ],
    [
        'pid' => $homepageUid,
        'colPos' => 0,
        'sorting' => 512,
        'CType' => 'text',
        'header' => 'Modern Content Management',
        'header_layout' => 2,
        'bodytext' => '<p>This is a <strong>TYPO3 Headless</strong> setup with a Next.js frontend. Content is managed in TYPO3 and delivered as JSON via the headless extension.</p><p>You can edit this content in the TYPO3 backend and it will automatically appear here.</p>',
        'tstamp' => $timestamp,
        'crdate' => $timestamp,
        'sys_language_uid' => 0,
        'hidden' => 0,
        'deleted' => 0,
    ],
    [
        'pid' => $homepageUid,
        'colPos' => 0,
        'sorting' => 768,
        'CType' => 'text',
        'header' => 'Key Features',
        'header_layout' => 2,
        'bodytext' => '<ul><li>Decoupled Architecture</li><li>Next.js 16 with React 19</li><li>Server-Side Rendering</li><li>TYPO3 14.3 Backend</li><li>JSON API via Headless Extension</li></ul>',
        'tstamp' => $timestamp,
        'crdate' => $timestamp,
        'sys_language_uid' => 0,
        'hidden' => 0,
        'deleted' => 0,
    ],
];

$stmt = $pdo->prepare('
    INSERT INTO tt_content 
    (pid, colPos, sorting, CType, header, header_layout, bodytext, tstamp, crdate, sys_language_uid, hidden, deleted)
    VALUES 
    (:pid, :colPos, :sorting, :CType, :header, :header_layout, :bodytext, :tstamp, :crdate, :sys_language_uid, :hidden, :deleted)
');

foreach ($contentElements as $element) {
    $element['bodytext'] = $element['bodytext'] ?? '';
    $stmt->execute($element);
    echo "  ✓ Added: {$element['header']}\n";
}

echo "\nDemo content added successfully!\n";
echo "Visit: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/\n";

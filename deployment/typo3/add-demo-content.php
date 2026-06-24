<?php

declare(strict_types=1);

require '/var/www/html/vendor/autoload.php';

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

Bootstrap::init();

$connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
$connection = $connectionPool->getConnectionForTable('tt_content');

// Check if content already exists
$existingContent = $connection->count('uid', 'tt_content', ['pid' => 1]);

if ($existingContent > 0) {
    echo "Content already exists on page 1. Skipping.\n";
    exit(0);
}

echo "Adding demo content to homepage (pid=1)...\n";

$contentElements = [
    [
        'pid' => 1,
        'colPos' => 0,
        'sorting' => 256,
        'CType' => 'header',
        'header' => 'Welcome to TYPO3 Headless',
        'header_layout' => 1,
        'tstamp' => time(),
        'crdate' => time(),
        'sys_language_uid' => 0,
        'hidden' => 0,
        'deleted' => 0,
    ],
    [
        'pid' => 1,
        'colPos' => 0,
        'sorting' => 512,
        'CType' => 'text',
        'header' => 'Modern Content Management',
        'header_layout' => 2,
        'bodytext' => '<p>This is a <strong>TYPO3 Headless</strong> setup with a Next.js frontend. Content is managed in TYPO3 and delivered as JSON via the headless extension.</p><p>You can edit this content in the TYPO3 backend and it will automatically appear here.</p>',
        'tstamp' => time(),
        'crdate' => time(),
        'sys_language_uid' => 0,
        'hidden' => 0,
        'deleted' => 0,
    ],
    [
        'pid' => 1,
        'colPos' => 0,
        'sorting' => 768,
        'CType' => 'text',
        'header' => 'Key Features',
        'header_layout' => 2,
        'bodytext' => '<ul><li>Decoupled Architecture</li><li>Next.js 16 with React 19</li><li>Server-Side Rendering</li><li>TYPO3 14.3 Backend</li><li>JSON API via Headless Extension</li></ul>',
        'tstamp' => time(),
        'crdate' => time(),
        'sys_language_uid' => 0,
        'hidden' => 0,
        'deleted' => 0,
    ],
];

foreach ($contentElements as $element) {
    $connection->insert('tt_content', $element);
    echo "  ✓ Added: {$element['header']}\n";
}

echo "\nDemo content added successfully!\n";
echo "Visit: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/\n";

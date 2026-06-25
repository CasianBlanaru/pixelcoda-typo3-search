<?php
/**
 * Setup script for TYPO3 Headless on Railway
 * Run with: php deployment/setup-railway.php
 */

// Bootstrap TYPO3
$classLoader = require __DIR__ . '/../vendor/autoload.php';
\TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::run(1, \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::REQUESTTYPE_CLI);
\TYPO3\CMS\Core\Core\Bootstrap::init($classLoader)->get(\TYPO3\CMS\Core\Core\ApplicationContext::class);

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Cache\CacheManager;

echo "Setting up TYPO3 Headless on Railway...\n";

// Get database connection
$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_template');

// Check if template exists
$existingTemplate = $connection->select(['uid'], 'sys_template', ['pid' => 2])->fetchAssociative();

if ($existingTemplate) {
    echo "TypoScript template already exists (UID: {$existingTemplate['uid']})\n";
    
    // Update existing template
    $connection->update(
        'sys_template',
        [
            'title' => 'Main Template',
            'root' => 1,
            'clear' => 3,
            'include_static_file' => 'EXT:headless/Configuration/TypoScript/,EXT:pixelcoda_sitepackage/Configuration/TypoScript/',
        ],
        ['uid' => $existingTemplate['uid']]
    );
    echo "Updated existing template\n";
} else {
    // Insert new template
    $connection->insert('sys_template', [
        'pid' => 2,
        'title' => 'Main Template',
        'root' => 1,
        'clear' => 3,
        'include_static_file' => 'EXT:headless/Configuration/TypoScript/,EXT:pixelcoda_sitepackage/Configuration/TypoScript/',
        'crdate' => time(),
        'tstamp' => time(),
    ]);
    echo "Created new TypoScript template (UID: {$connection->lastInsertId()})\n";
}

// Flush caches
echo "Flushing caches...\n";
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->flushCaches();

echo "✓ Setup completed successfully!\n";
echo "TYPO3 Headless should now output content via JSON API.\n";

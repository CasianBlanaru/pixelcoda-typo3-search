<?php
/**
 * Update site configuration URLs for Railway production
 * Run after database import: php deployment/update-site-urls.php
 */

require __DIR__ . '/../vendor/autoload.php';

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;
use Symfony\Component\Yaml\Yaml;

echo "Updating site configuration URLs for Railway production...\n\n";

$configPath = Environment::getConfigPath() . '/sites/main/config.yaml';

if (!file_exists($configPath)) {
    echo "ERROR: Site configuration not found at: $configPath\n";
    exit(1);
}

// Read current configuration
$config = Yaml::parseFile($configPath);

echo "Current configuration:\n";
echo "  base: {$config['base']}\n";
echo "  frontendBase: {$config['frontendBase']}\n";
echo "  languages[0].frontendBase: {$config['languages'][0]['frontendBase']}\n\n";

// Get URLs from environment or use defaults
$siteBase = getenv('TYPO3_SITE_BASE') ?: 'https://web-production-581b4.up.railway.app/';
$frontendBase = getenv('TYPO3_FRONTEND_BASE') ?: 'https://nextjs-front-end-for-typo3-headless-production.up.railway.app/';

echo "Updating to:\n";
echo "  base: $siteBase\n";
echo "  frontendBase: $frontendBase\n\n";

// Update configuration
$config['base'] = $siteBase;
$config['frontendBase'] = $frontendBase;
$config['languages'][0]['frontendBase'] = $frontendBase;

// Write updated configuration
file_put_contents($configPath, Yaml::dump($config, 10, 2));

echo "✓ Site configuration updated\n\n";

// Flush caches
echo "Flushing caches...\n";
$bootstrap = \TYPO3\CMS\Core\Core\Bootstrap::init(
    require __DIR__ . '/../vendor/autoload.php'
);
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->flushCaches();

echo "✓ Caches flushed\n\n";
echo "Configuration update completed successfully!\n";

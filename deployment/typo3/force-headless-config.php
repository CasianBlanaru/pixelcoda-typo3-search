<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

require '/var/www/html/vendor/autoload.php';

$siteConfigPath = '/var/www/html/config/sites/main/config.yaml';

if (!file_exists($siteConfigPath)) {
    echo "Site config not found at $siteConfigPath\n";
    exit(1);
}

$config = Yaml::parseFile($siteConfigPath);

$config['headless'] = 1;
$config['customConfiguration'] = ['renderingMode' => 'headless'];

$railwayDomain = (string)(getenv('RAILWAY_PUBLIC_DOMAIN') ?: '');
$siteBase = '' !== $railwayDomain ? 'https://' . $railwayDomain . '/' : '/';
$frontendBase = (string)(getenv('TYPO3_FRONTEND_BASE') ?: $siteBase);

$config['base'] = $siteBase;
$config['frontendBase'] = $frontendBase;

if (isset($config['languages']) && is_array($config['languages'])) {
    foreach ($config['languages'] as &$lang) {
        $lang['frontendBase'] = $frontendBase;
    }
    unset($lang);
}

// Remove sets to prevent TypoScript conflicts in headless mode
unset($config['sets']);

// Use dependencies instead of sets for headless mode
$config['dependencies'] = [
    'friendsoftypo3/headless',
];

file_put_contents(
    $siteConfigPath,
    Yaml::dump($config, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
);

echo "Site config updated successfully:\n";
echo "  headless: 1\n";
echo "  renderingMode: headless\n";
echo "  base: $siteBase\n";
echo "  frontendBase: $frontendBase\n";
echo "  dependencies: only friendsoftypo3/headless\n";

// Clear all cache locations
exec('rm -rf /var/www/html/var/cache/* /data/var/cache/* 2>/dev/null');
exec('rm -rf /var/www/html/public/typo3temp/* 2>/dev/null');
echo "All caches cleared.\n";

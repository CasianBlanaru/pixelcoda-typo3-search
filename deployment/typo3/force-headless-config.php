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

$config['dependencies'] = array_values(array_unique(array_merge(
    $config['dependencies'] ?? [],
    [
        'typo3/fluid-styled-content',
        'friendsoftypo3/headless',
        'pixelcoda/sitepackage',
        'pixelcoda/fe-editor',
        'pixelcoda/typo3-search',
    ]
)));

file_put_contents(
    $siteConfigPath,
    Yaml::dump($config, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
);

echo "Site config updated successfully:\n";
echo "  headless: 1\n";
echo "  renderingMode: headless\n";
echo "  base: $siteBase\n";
echo "  frontendBase: $frontendBase\n";

exec('rm -rf /var/www/html/var/cache/*');
echo "Cache cleared.\n";

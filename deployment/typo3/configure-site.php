<?php

declare(strict_types=1);

use Symfony\Component\Yaml\Yaml;

require '/var/www/html/vendor/autoload.php';

$siteConfigurationFiles = glob('/var/www/html/config/sites/*/config.yaml') ?: [];
$requiredSets = [
    'typo3/fluid-styled-content',
    'pixelcoda/sitepackage',
    'pixelcoda/content-gsap-animation',
    'pixelcoda/fe-editor',
    'pixelcoda/typo3-search',
];

foreach ($siteConfigurationFiles as $siteConfigurationFile) {
    $configuration = Yaml::parseFile($siteConfigurationFile);
    if (!is_array($configuration)) {
        continue;
    }

    $dependencies = is_array($configuration['dependencies'] ?? null)
        ? $configuration['dependencies']
        : [];
    $configuration['dependencies'] = array_values(array_unique([...$dependencies, ...$requiredSets]));

    file_put_contents(
        $siteConfigurationFile,
        Yaml::dump($configuration, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK),
    );
}

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

$configuredRootPageIds = [];
$siteConfigurationFiles = glob('/var/www/html/config/sites/*/config.yaml') ?: [];
foreach ($siteConfigurationFiles as $siteConfigurationFile) {
    $configuration = Yaml::parseFile($siteConfigurationFile);
    if (is_array($configuration) && isset($configuration['rootPageId'])) {
        $configuredRootPageIds[] = (int)$configuration['rootPageId'];
    }
}

$databaseHost = (string)getenv('TYPO3_DB_HOST');
$databaseName = (string)getenv('TYPO3_DB_DBNAME');
$databaseUser = (string)getenv('TYPO3_DB_USERNAME');
$databasePassword = (string)getenv('TYPO3_DB_PASSWORD');
$databasePort = (int)(getenv('TYPO3_DB_PORT') ?: 3306);

if ('' !== $databaseHost && '' !== $databaseName && '' !== $databaseUser) {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $databaseHost, $databasePort, $databaseName),
        $databaseUser,
        $databasePassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    if ((string)(getenv('PIXELCODA_PRUNE_DUPLICATE_ROOTS') ?: '1') === '1') {
        $rootPages = $pdo->query(
            'SELECT uid, title, slug FROM pages WHERE pid = 0 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC'
        )->fetchAll(PDO::FETCH_ASSOC);
        $seenRootKeys = [];
        foreach ($rootPages as $rootPage) {
            $rootPageId = (int)$rootPage['uid'];
            $rootKey = (string)($rootPage['title'] ?? '') . "\n" . (string)($rootPage['slug'] ?? '');
            if (!isset($seenRootKeys[$rootKey])) {
                $seenRootKeys[$rootKey] = $rootPageId;
                continue;
            }

            if (in_array($rootPageId, $configuredRootPageIds, true)) {
                $previousRootPageId = $seenRootKeys[$rootKey];
                $pdo->prepare('UPDATE pages SET deleted = 1, hidden = 1 WHERE uid = :uid')
                    ->execute(['uid' => $previousRootPageId]);
                $pdo->prepare('UPDATE tt_content SET deleted = 1 WHERE pid = :pid')
                    ->execute(['pid' => $previousRootPageId]);
                $seenRootKeys[$rootKey] = $rootPageId;
                continue;
            }

            $pdo->prepare('UPDATE pages SET deleted = 1, hidden = 1 WHERE uid = :uid')
                ->execute(['uid' => $rootPageId]);
            $pdo->prepare('UPDATE tt_content SET deleted = 1 WHERE pid = :pid')
                ->execute(['pid' => $rootPageId]);
        }
    }

    if ((string)(getenv('PIXELCODA_CREATE_PREVIEW_SITES') ?: '0') !== '1') {
        foreach (glob('/var/www/html/config/sites/preview-root-*', GLOB_ONLYDIR) ?: [] as $previewSiteDirectory) {
            foreach (glob($previewSiteDirectory . '/*') ?: [] as $previewSiteFile) {
                if (is_file($previewSiteFile)) {
                    unlink($previewSiteFile);
                }
            }
            rmdir($previewSiteDirectory);
        }
        exit(0);
    }

    $rootPages = $pdo->query(
        'SELECT uid, title FROM pages WHERE pid = 0 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rootPages as $rootPage) {
        $rootPageId = (int)$rootPage['uid'];
        if (in_array($rootPageId, $configuredRootPageIds, true)) {
            continue;
        }

        $identifier = 'preview-root-' . $rootPageId;
        $siteDirectory = '/var/www/html/config/sites/' . $identifier;
        if (!is_dir($siteDirectory)) {
            mkdir($siteDirectory, 0775, true);
        }

        $configuration = [
            'base' => '/preview-' . $rootPageId . '/',
            'dependencies' => $requiredSets,
            'errorHandling' => [],
            'languages' => [
                [
                    'title' => 'English',
                    'enabled' => true,
                    'languageId' => 0,
                    'base' => '/',
                    'locale' => 'en_US.UTF-8',
                    'navigationTitle' => 'English',
                    'flag' => 'us',
                ],
            ],
            'rootPageId' => $rootPageId,
            'routes' => [],
        ];

        file_put_contents(
            $siteDirectory . '/config.yaml',
            Yaml::dump($configuration, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
        );
    }
}

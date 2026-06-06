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
$siteBase = normalizeSiteBase();

function normalizeSiteBase(): string
{
    $base = (string)(getenv('TYPO3_SITE_BASE') ?: '');
    if ('' === $base) {
        $railwayDomain = (string)(getenv('RAILWAY_PUBLIC_DOMAIN') ?: '');
        $base = '' !== $railwayDomain ? 'https://' . $railwayDomain : '/';
    }

    if ('/' !== $base && !str_ends_with($base, '/')) {
        $base .= '/';
    }

    return $base;
}

function writeSiteConfiguration(string $siteConfigurationFile, array $configuration): void
{
    file_put_contents(
        $siteConfigurationFile,
        Yaml::dump($configuration, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK),
    );
}

foreach ($siteConfigurationFiles as $siteConfigurationFile) {
    $configuration = Yaml::parseFile($siteConfigurationFile);
    if (!is_array($configuration)) {
        continue;
    }

    $dependencies = is_array($configuration['dependencies'] ?? null)
        ? $configuration['dependencies']
        : [];
    $configuration['dependencies'] = array_values(array_unique([...$dependencies, ...$requiredSets]));

    writeSiteConfiguration($siteConfigurationFile, $configuration);
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

    $activeRootPages = $pdo->query(
        'SELECT uid, title, slug FROM pages WHERE pid = 0 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    if ([] !== $activeRootPages) {
        $activeRootPageIds = array_map(static fn(array $page): int => (int)$page['uid'], $activeRootPages);
        $primaryRootPageId = (int)$activeRootPages[0]['uid'];
        $siteConfigurationFiles = glob('/var/www/html/config/sites/*/config.yaml') ?: [];
        foreach ($siteConfigurationFiles as $siteConfigurationFile) {
            $configuration = Yaml::parseFile($siteConfigurationFile);
            if (!is_array($configuration)) {
                continue;
            }

            $configuredRootPageId = (int)($configuration['rootPageId'] ?? 0);
            if (!in_array($configuredRootPageId, $activeRootPageIds, true)) {
                $configuration['rootPageId'] = $primaryRootPageId;
                $configuration['dependencies'] = array_values(array_unique([
                    ...(is_array($configuration['dependencies'] ?? null) ? $configuration['dependencies'] : []),
                    ...$requiredSets,
                ]));
            }

            if ((int)($configuration['rootPageId'] ?? 0) === $primaryRootPageId) {
                $configuration['base'] = $siteBase;
            }

            writeSiteConfiguration($siteConfigurationFile, $configuration);
        }

        $configuredRootPageIds = [];
        foreach (glob('/var/www/html/config/sites/*/config.yaml') ?: [] as $siteConfigurationFile) {
            $configuration = Yaml::parseFile($siteConfigurationFile);
            if (is_array($configuration) && isset($configuration['rootPageId'])) {
                $configuredRootPageIds[] = (int)$configuration['rootPageId'];
            }
        }
    }

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
        createDemoPages($pdo);
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

    createDemoPages($pdo);
}

function createDemoPages(PDO $pdo): void
{
    $rootPageId = (int)$pdo->query(
        'SELECT uid FROM pages WHERE pid = 0 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC LIMIT 1'
    )->fetchColumn();

    if ($rootPageId <= 0) {
        return;
    }

    ensurePixelcodaPageTemplate($pdo, $rootPageId);

    $pageDefinitions = [
        ['title' => 'Search Demo', 'slug' => '/search-demo', 'header' => 'Pixelcoda Search Demo', 'body' => 'Suche mit Autocomplete, Suggestions, Facetten, Ergebnissen und Pagination testen.', 'ctype' => 'pixelcodasearch_search'],
        ['title' => 'Facetten Suche', 'slug' => '/search-facets-demo', 'header' => 'Facetten und Filter testen', 'body' => 'Demo-Suche mit Kategorien, Inhaltstypen, Ergebnislisten und Seitenwechsel.', 'ctype' => 'pixelcodasearch_search'],
        ['title' => 'KI Antwort Suche', 'slug' => '/search-ai-demo', 'header' => 'KI-Antworten mit Quellen testen', 'body' => 'Fragen an den TYPO3 Suchindex stellen und Antworten mit Quellen pruefen.', 'ctype' => 'pixelcodasearch_search'],
        ['title' => 'GSAP Demo', 'slug' => '/gsap-demo', 'header' => 'Pixelcoda GSAP Demo', 'body' => 'ScrollTrigger, Reduced Motion und Headless-ready Animationsdaten testen.', 'ctype' => 'textmedia'],
        ['title' => 'Frontend Editing Demo', 'slug' => '/frontend-editing-demo', 'header' => 'Pixelcoda Frontend Editing Demo', 'body' => 'Inline Editing, Drag-and-drop und Redakteur-Workflow testen.', 'ctype' => 'pc_demo'],
    ];

    foreach ($pageDefinitions as $position => $definition) {
        $pageStatement = $pdo->prepare(
            'SELECT uid FROM pages WHERE pid = :pid AND slug = :slug AND deleted = 0 ORDER BY uid ASC LIMIT 1'
        );
        $pageStatement->execute(['pid' => $rootPageId, 'slug' => $definition['slug']]);
        $pageId = (int)$pageStatement->fetchColumn();

        if ($pageId <= 0) {
            $insertPage = $pdo->prepare(
                'INSERT INTO pages (pid, title, slug, doktype, hidden, deleted, sorting, crdate, tstamp)
                 VALUES (:pid, :title, :slug, 1, 0, 0, :sorting, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())'
            );
            $insertPage->execute([
                'pid' => $rootPageId,
                'title' => $definition['title'],
                'slug' => $definition['slug'],
                'sorting' => 256 + ($position * 256),
            ]);
            $pageId = (int)$pdo->lastInsertId();
        }

        $contentStatement = $pdo->prepare(
            'SELECT uid FROM tt_content WHERE pid = :pid AND CType = :ctype AND deleted = 0 ORDER BY uid ASC LIMIT 1'
        );
        $contentStatement->execute(['pid' => $pageId, 'ctype' => $definition['ctype']]);
        if ((int)$contentStatement->fetchColumn() > 0) {
            continue;
        }

        $insertContent = $pdo->prepare(
            'INSERT INTO tt_content (pid, CType, header, bodytext, colPos, hidden, deleted, sorting, crdate, tstamp)
             VALUES (:pid, :ctype, :header, :bodytext, 0, 0, 0, 256, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())'
        );
        $insertContent->execute([
            'pid' => $pageId,
            'ctype' => $definition['ctype'],
            'header' => $definition['header'],
            'bodytext' => $definition['body'],
        ]);
    }
}

function ensurePixelcodaPageTemplate(PDO $pdo, int $rootPageId): void
{
    $constants = <<<'TYPOSCRIPT'
pixelcoda.search.apiUrl = /search-api
pixelcoda.search.readApiKey = pc_read_dev_key
pixelcoda.search.projectId = typo3
plugin.tx_pixelcodasearch_search.settings.mode = classic
plugin.tx_pixelcodasearch_search.settings.placeholder = Website durchsuchen...
plugin.tx_pixelcodasearch_search.settings.resultsPerPage = 10
plugin.tx_pixelcodasearch_search.settings.collections = pages,tt_content
plugin.tx_pixelcodasearch_search.settings.enableSuggestions = 1
plugin.tx_pixelcodasearch_search.settings.enableAsk = 1
plugin.tx_pixelcodasearch_search.settings.enableFacets = 1
TYPOSCRIPT;

    $setup = <<<'TYPOSCRIPT'
page = PAGE
page {
  typeNum = 0

  config {
    doctype = html5
    admPanel = 1
    removeDefaultJS = 0
    prefixLocalAnchors = all
  }

  meta {
    viewport = width=device-width, initial-scale=1
    robots = index,follow
    description = Pixelcoda TYPO3 Suite Demo mit Search, Frontend Editing und GSAP Animation.
  }

  10 = PAGEVIEW
  10 {
    paths.10 = EXT:pixelcoda_sitepackage/Resources/Private/PageView/
  }

  includeCSS {
    pixelcoda = EXT:pixelcoda_sitepackage/Resources/Public/Css/site.css
  }

  includeJSFooter {
    pixelcoda = EXT:pixelcoda_sitepackage/Resources/Public/JavaScript/site.js
  }
}

lib.pixelcodaDemoLoginNotice = TEXT
lib.pixelcodaDemoLoginNotice.value (
  <section class="pixelcoda-demo-login-notice" aria-labelledby="pixelcoda-demo-login-title">
    <div>
      <span class="pixelcoda-demo-login-notice__eyebrow">Demo-Modus</span>
      <h2 id="pixelcoda-demo-login-title">Plugins im TYPO3 Backend testen</h2>
      <p>Melde dich als Redakteur an, um pixelcoda Search, Frontend Editing und GSAP-Animationen direkt in der Testumgebung auszuprobieren.</p>
      <dl>
        <div><dt>Benutzer</dt><dd>pixelcoda-editor</dd></div>
        <div><dt>Rolle</dt><dd>Redakteur, kein Admin</dd></div>
      </dl>
    </div>
    <a href="/typo3/login?redirect=tools_PixelcodaSearchM1" class="pixelcoda-demo-login-notice__button">Zum TYPO3 Login</a>
  </section>
)

[backend.user.isLoggedIn]
lib.pixelcodaDemoLoginNotice >
[END]

lib.dynamicContent = COA
lib.dynamicContent {
  10 = LOAD_REGISTER
  10 {
    colPos.cObject = TEXT
    colPos.cObject {
      field = colPos
      ifEmpty = 0
    }
  }

  20 = CONTENT
  20 {
    table = tt_content
    select {
      orderBy = sorting
      where = {#colPos}={register:colPos}
      where.insertData = 1
    }
  }

  90 = RESTORE_REGISTER
}
TYPOSCRIPT;

    $statement = $pdo->prepare(
        'SELECT uid FROM sys_template WHERE pid = :pid AND deleted = 0 ORDER BY root DESC, uid ASC LIMIT 1'
    );
    $statement->execute(['pid' => $rootPageId]);
    $templateId = (int)$statement->fetchColumn();

    if ($templateId > 0) {
        $update = $pdo->prepare(
            'UPDATE sys_template
             SET title = :title, sitetitle = :sitetitle, root = 1, clear = 3, constants = :constants,
                 config = :config, include_static_file = :includeStaticFile, tstamp = UNIX_TIMESTAMP()
             WHERE uid = :uid'
        );
        $update->execute([
            'title' => 'Pixelcoda TYPO3 Suite',
            'sitetitle' => 'Pixelcoda TYPO3 Suite',
            'constants' => $constants,
            'config' => $setup,
            'includeStaticFile' => 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:pixelcoda_search/Configuration/TypoScript/,EXT:content_gsap_animation/Configuration/TypoScript/FluidStyledContent/',
            'uid' => $templateId,
        ]);
        return;
    }

    $insert = $pdo->prepare(
        'INSERT INTO sys_template (pid, title, sitetitle, root, clear, constants, config, include_static_file, hidden, deleted, crdate, tstamp)
         VALUES (:pid, :title, :sitetitle, 1, 3, :constants, :config, :includeStaticFile, 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())'
    );
    $insert->execute([
        'pid' => $rootPageId,
        'title' => 'Pixelcoda TYPO3 Suite',
        'sitetitle' => 'Pixelcoda TYPO3 Suite',
        'constants' => $constants,
        'config' => $setup,
        'includeStaticFile' => 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:pixelcoda_search/Configuration/TypoScript/,EXT:content_gsap_animation/Configuration/TypoScript/FluidStyledContent/',
    ]);
}

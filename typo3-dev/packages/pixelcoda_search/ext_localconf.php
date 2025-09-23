<?php
defined('TYPO3') || die();

use PixelCoda\PixelcodaSearch\Hook\DatamapHook;
use PixelCoda\PixelcodaSearch\Command\IndexCommand;
use PixelCoda\PixelcodaSearch\Command\ReindexCommand;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Register DataHandler hooks for automatic indexing
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DatamapHook::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = DatamapHook::class;

// Register CLI commands
$GLOBALS['TYPO3_CONF_VARS']['CONSOLE']['commands']['pixelcoda:search:index'] = [
    'class' => IndexCommand::class
];
$GLOBALS['TYPO3_CONF_VARS']['CONSOLE']['commands']['pixelcoda:search:reindex'] = [
    'class' => ReindexCommand::class
];

// Register Frontend Plugin
ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'Search',
    [
        \PixelCoda\PixelcodaSearch\Controller\SearchController::class => 'index,search,ask,results'
    ],
    [
        \PixelCoda\PixelcodaSearch\Controller\SearchController::class => 'search,ask'
    ]
);

// Register AJAX endpoints for search suggestions
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_suggest'] = 
    \PixelCoda\PixelcodaSearch\Eid\SuggestEid::class . '::processRequest';

// Register page type for JSON API (headless mode)
$GLOBALS['TYPO3_CONF_VARS']['FE']['PageTypesToNoCache'][1699] = true;

// Extension configuration with environment fallbacks
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] = [
    'api_url' => $_ENV['PIXELCODA_API_URL'] ?? 'http://host.docker.internal:8787',
    'api_key' => $_ENV['PIXELCODA_API_KEY'] ?? 'pc_write_dev_key',
    'hmac_secret' => $_ENV['PIXELCODA_HMAC_SECRET'] ?? 'dev_hmac_secret_key',
    'project_id' => $_ENV['PIXELCODA_PROJECT_ID'] ?? 'typo3-dev',
    'typo3_headless_url' => $_ENV['TYPO3_HEADLESS_URL'] ?? 'http://localhost:8080/api',
    'enabled_tables' => ['pages', 'tt_content', 'tx_news_domain_model_news'],
    'default_mode' => 'classic', // classic|headless
    'enable_auto_index' => true,
    'enable_vector_search' => true,
    'batch_size' => 50,
    'timeout' => 30,
    'debug_mode' => true
];

// Static TypoScript files are now added in ext_tables.php

// Add page TSconfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:pixelcoda_search/Configuration/TsConfig/Page/All.tsconfig"'
);

// Register icon for backend module
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$iconRegistry->registerIcon(
    'pixelcoda-search',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:pixelcoda_search/Resources/Public/Icons/Extension.svg']
);

<?php

declare(strict_types=1);

use PixelCoda\PixelcodaSearch\Controller\Api\PluginConfigController;
use PixelCoda\PixelcodaSearch\Controller\SearchController;
use PixelCoda\PixelcodaSearch\Eid\SuggestEid;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || exit();

// ExtensionUtility removed - not needed anymore

// Register DataHandler hooks for automatic indexing (temporarily disabled due to signature issues)
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DatamapHook::class;
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = DatamapHook::class;

// CLI commands are now registered via Configuration/Services.yaml

// Auto-include TypoScript setup
ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:pixelcoda_search/Configuration/TypoScript/setup.typoscript"'
);

// Auto-include TypoScript constants
ExtensionManagementUtility::addTypoScriptConstants(
    '@import "EXT:pixelcoda_search/Configuration/TypoScript/constants.typoscript"'
);

// Plugin registration removed - using Content Element only (TYPO3 Best Practice)
// The search functionality is provided as a Content Element (CType: pixelcodasearch_search)
// This prevents duplicate registration and follows TYPO3 Headless best practices

// Register AJAX endpoints for search suggestions
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_suggest']
    = SuggestEid::class . '::processRequest';

// Register API routes for plugin configuration
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_config']
    = PluginConfigController::class . '::getPluginConfig';

// Register page type for JSON API (headless mode)
$GLOBALS['TYPO3_CONF_VARS']['FE']['PageTypesToNoCache'][1699] = true;

// Environment variables provide defaults without overwriting values configured
// through TYPO3's extension configuration.
$extensionConfiguration = &$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'];
$extensionConfiguration['api_url'] ??= getenv('PIXELCODA_API_URL') ?: 'http://localhost:8787';
$extensionConfiguration['api_key'] ??= getenv('PIXELCODA_API_KEY') ?: '';
$extensionConfiguration['read_api_key'] ??= getenv('PIXELCODA_READ_API_KEY') ?: '';
$extensionConfiguration['hmac_secret'] ??= getenv('PIXELCODA_HMAC_SECRET') ?: '';
$extensionConfiguration['project_id'] ??= getenv('PIXELCODA_PROJECT_ID') ?: 'typo3';
$extensionConfiguration['typo3_headless_url'] ??= getenv('TYPO3_HEADLESS_URL') ?: '';
$extensionConfiguration['cors_origins'] ??= getenv('PIXELCODA_CORS_ORIGINS') ?: '';
$extensionConfiguration['enabled_tables'] ??= ['pages', 'tt_content', 'tx_news_domain_model_news'];
$extensionConfiguration['default_mode'] ??= 'classic';
$extensionConfiguration['enable_auto_index'] ??= true;
$extensionConfiguration['enable_vector_search'] ??= true;
$extensionConfiguration['enable_metrics'] ??= false;
$extensionConfiguration['batch_size'] ??= 50;
$extensionConfiguration['timeout'] ??= 30;
$extensionConfiguration['debug_mode'] ??= false;

// Static TypoScript files are now added in ext_tables.php

// Register the search plugin for the search results page
ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'SearchResults',
    [
        SearchController::class => 'search,suggest',
    ],
    // non-cacheable actions
    [
        SearchController::class => 'search,suggest',
    ]
);

// Register EID handler for AJAX autocomplete
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['search_suggest']
    = SuggestEid::class . '::processRequest';

// Register webhook endpoint
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_webhook']
    = PixelCoda\PixelcodaSearch\Controller\WebhookController::class . '::indexAction';

<?php
defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Add static TypoScript for plugin configuration
ExtensionManagementUtility::addStaticFile(
    'pixelcoda_search',
    'Configuration/TypoScript',
    'pixelcoda Search'
);

// Add plugin to content element wizard
ExtensionManagementUtility::addPlugin(
    [
        'LLL:EXT:pixelcoda_search/Resources/Private/Language/locallang_db.xlf:plugin.search.title',
        'pixelcodasearch_search',
        'EXT:pixelcoda_search/Resources/Public/Icons/ContentElement.svg'
    ],
    'CType',
    'pixelcoda_search'
);

// Add FlexForm configuration
$GLOBALS['TCA']['tt_content']['types']['pixelcodasearch_search'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
            pi_flexform,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    '
];

// Add FlexForm configuration
$GLOBALS['TCA']['tt_content']['types']['pixelcodasearch_search']['columnsOverrides'] = [
    'pi_flexform' => [
        'config' => [
            'ds' => [
                'default' => 'FILE:EXT:pixelcoda_search/Configuration/FlexForms/Search.xml'
            ]
        ]
    ]
];

// Register backend module
ExtensionManagementUtility::addModule(
    'tools',
    'PixelcodaSearchM1',
    '',
    '',
    [
        'routeTarget' => \PixelCoda\PixelcodaSearch\Controller\Backend\SearchModuleController::class . '::handleRequest',
        'access' => 'user,group',
        'name' => 'tools_PixelcodaSearchM1',
        'icon' => 'EXT:pixelcoda_search/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:pixelcoda_search/Resources/Private/Language/locallang_mod.xlf'
    ]
);

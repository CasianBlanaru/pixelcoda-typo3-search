<?php

declare(strict_types=1);

defined('TYPO3') || exit();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Add header_class field to tt_content table
// This field is required by Bootstrap Package for header styling
$tempColumns = [
    'header_class' => [
        'exclude' => true,
        'label' => 'LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.default', ''],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h1', 'h1'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h2', 'h2'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h3', 'h3'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h4', 'h4'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h5', 'h5'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.h6', 'h6'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.display-1', 'display-1'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.display-2', 'display-2'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.display-3', 'display-3'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.display-4', 'display-4'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.lead', 'lead'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-muted', 'text-muted'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-primary', 'text-primary'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-secondary', 'text-secondary'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-success', 'text-success'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-info', 'text-info'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-warning', 'text-warning'],
                ['LLL:EXT:site_package/Resources/Private/Language/locallang_db.xlf:tt_content.header_class.text-danger', 'text-danger'],
            ],
            'default' => '',
        ],
    ],
];

// Add the field to the TCA
ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

// Add the field to the headers palette for all content elements
ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'headers',
    'header_class',
    'after:header_layout'
);

// Ensure the field is available in all content element types
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--palette--;;headers',
    '',
    'replace:header'
);

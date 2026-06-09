<?php

declare(strict_types=1);

defined('TYPO3') || exit();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'pixelcoda_search',
    'Configuration/TypoScript',
    'Pixelcoda Search'
);

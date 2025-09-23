<?php
// Bootstrap TYPO3
define('TYPO3_MODE', 'BE');
require_once __DIR__ . '/vendor/autoload.php';

// Set environment
putenv('TYPO3_CONTEXT=Development');

try {
    // Bootstrap TYPO3
    $classLoader = require __DIR__ . '/vendor/autoload.php';
    \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::run(1, \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::REQUESTTYPE_BE);
    \TYPO3\CMS\Core\Core\Bootstrap::init($classLoader);
    
    // Get extension management utility
    $extMgmt = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::class;
    
    echo "Registering pixelcoda_search extension...\n";
    
    // Check if extension exists
    $extPath = 'typo3conf/ext/pixelcoda_search/';
    if (file_exists(__DIR__ . '/public/' . $extPath . 'ext_emconf.php')) {
        echo "Extension files found at: " . $extPath . "\n";
        
        // Force reload package states
        $packageManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class);
        $packageManager->scanAvailablePackages();
        
        echo "Extension registration completed!\n";
    } else {
        echo "ERROR: Extension files not found!\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>

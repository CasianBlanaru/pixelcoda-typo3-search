<?php

defined('TYPO3') or die('Access denied.');

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Form configuration
ExtensionManagementUtility::addTypoScriptSetup('
  module.tx_form.settings.yamlConfigurations.100 = EXT:site_package/Configuration/Form/CustomFormSetup.yaml
  plugin.tx_form.settings.yamlConfigurations.100 = EXT:site_package/Configuration/Form/CustomFormSetup.yaml
');

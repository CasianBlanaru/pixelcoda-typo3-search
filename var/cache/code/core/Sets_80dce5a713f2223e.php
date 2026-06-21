<?php
return array (
  'orderedSets' => 
  array (
    'friendsoftypo3/headless' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'friendsoftypo3/headless',
       'label' => 'TYPO3 Headless',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:headless/Configuration/Sets/Headless/',
       'pagets' => 'EXT:headless/Configuration/Sets/Headless/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'typo3/fluid-styled-content' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'typo3/fluid-styled-content',
       'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:label',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.defaultHeaderType',
           'type' => 'int',
           'default' => 2,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.defaultHeaderType',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.defaultHeaderType',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.shortcut.tables',
           'type' => 'string',
           'default' => 'tt_content',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.shortcut.tables',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.shortcut.tables',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.image.lazyLoading',
           'type' => 'string',
           'default' => 'lazy',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.lazyLoading',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.image.lazyLoading',
           'readonly' => false,
           'enum' => 
          array (
            'lazy' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.lazyLoading.enum.lazy',
            'eager' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.lazyLoading.enum.eager',
            'auto' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.lazyLoading.enum.auto',
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        3 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.image.imageDecoding',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.imageDecoding',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.image.imageDecoding',
           'readonly' => false,
           'enum' => 
          array (
            'sync' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.imageDecoding.enum.sync',
            'async' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.imageDecoding.enum.async',
            'auto' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.image.imageDecoding.enum.auto',
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        4 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.maxW',
           'type' => 'int',
           'default' => 600,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.maxW',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.maxW',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        5 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.maxWInText',
           'type' => 'int',
           'default' => 300,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.maxWInText',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.maxWInText',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        6 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.columnSpacing',
           'type' => 'int',
           'default' => 10,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.columnSpacing',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.columnSpacing',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        7 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.rowSpacing',
           'type' => 'int',
           'default' => 10,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.rowSpacing',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.rowSpacing',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        8 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.textMargin',
           'type' => 'int',
           'default' => 10,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.textMargin',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.textMargin',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        9 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.borderColor',
           'type' => 'color',
           'default' => '#000000',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.borderColor',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.borderColor',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        10 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.borderWidth',
           'type' => 'int',
           'default' => 2,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.borderWidth',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.borderWidth',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        11 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.borderPadding',
           'type' => 'int',
           'default' => 0,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.borderPadding',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.borderPadding',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        12 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.linkWrap.width',
           'type' => 'string',
           'default' => '800m',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.linkWrap.width',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.linkWrap.width',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        13 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.linkWrap.height',
           'type' => 'string',
           'default' => '600m',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.linkWrap.height',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.linkWrap.height',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        14 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.linkWrap.newWindow',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.linkWrap.newWindow',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.linkWrap.newWindow',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        15 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.linkWrap.lightboxEnabled',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.linkWrap.lightboxEnabled',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.linkWrap.lightboxEnabled',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        16 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.linkWrap.lightboxCssClass',
           'type' => 'string',
           'default' => 'lightbox',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.linkWrap.lightboxCssClass',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.linkWrap.lightboxCssClass',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        17 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.content.textmedia.linkWrap.lightboxRelAttribute',
           'type' => 'string',
           'default' => 'lightbox[{field:uid}]',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.content.textmedia.linkWrap.lightboxRelAttribute',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.content.textmedia.linkWrap.lightboxRelAttribute',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.content',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        18 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.templates.templateRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.templates.templateRootPath',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.templates.templateRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        19 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.templates.partialRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.templates.partialRootPath',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.templates.partialRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        20 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'styles.templates.layoutRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.styles.templates.layoutRootPath',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:settings.description.styles.templates.layoutRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'fsc.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
      ),
       'categoryDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'fsc',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:categories.fsc',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:categories.description.fsc',
           'icon' => NULL,
           'parent' => NULL,
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'fsc.templates',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:categories.fsc.templates',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:categories.description.fsc.templates',
           'icon' => NULL,
           'parent' => 'fsc',
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'fsc.content',
           'label' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:categories.fsc.content',
           'description' => 'LLL:EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/labels.xlf:categories.description.fsc.content',
           'icon' => NULL,
           'parent' => 'fsc',
        )),
      ),
       'typoscript' => 'EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/',
       'pagets' => 'EXT:fluid_styled_content/Configuration/Sets/FluidStyledContent/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'pixelcoda/fe-editor' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'pixelcoda/fe-editor',
       'label' => 'PixelCoda FE Editor',
       'dependencies' => 
      array (
        0 => 'typo3/fluid-styled-content',
        1 => 'friendsoftypo3/headless',
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:pixelcoda_fe_editor/Configuration/Sets/PixelcodaFeEditor/',
       'pagets' => 'EXT:pixelcoda_fe_editor/Configuration/Sets/PixelcodaFeEditor/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'pixelcoda/content-gsap-animation' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'pixelcoda/content-gsap-animation',
       'label' => 'Pixelcoda Content GSAP Animation',
       'dependencies' => 
      array (
        0 => 'typo3/fluid-styled-content',
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:content_gsap_animation/Configuration/Sets/ContentGsapAnimation/',
       'pagets' => 'EXT:content_gsap_animation/Configuration/Sets/ContentGsapAnimation/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'pixelcoda/sitepackage' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'pixelcoda/sitepackage',
       'label' => 'Pixelcoda Sitepackage',
       'dependencies' => 
      array (
        0 => 'typo3/fluid-styled-content',
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:pixelcoda_sitepackage/Configuration/Sets/Pixelcoda/',
       'pagets' => 'EXT:pixelcoda_sitepackage/Configuration/Sets/Pixelcoda/page.tsconfig',
       'settings' => 
      array (
        'styles' => 
        array (
          'content' => 
          array (
            'defaultHeaderType' => 2,
          ),
        ),
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'pixelcoda/typo3-search' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'pixelcoda/typo3-search',
       'label' => 'Pixelcoda Search',
       'dependencies' => 
      array (
        0 => 'typo3/fluid-styled-content',
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:pixelcoda_search/Configuration/Sets/PixelcodaSearch/',
       'pagets' => 'EXT:pixelcoda_search/Configuration/Sets/PixelcodaSearch/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'typo3/fluid-styled-content-css' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'typo3/fluid-styled-content-css',
       'label' => 'Fluid Styled Content CSS',
       'dependencies' => 
      array (
        0 => 'typo3/fluid-styled-content',
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:fluid_styled_content/Configuration/Sets/FluidStyledContentCss/',
       'pagets' => 'EXT:fluid_styled_content/Configuration/Sets/FluidStyledContentCss/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'friendsoftypo3/headless-mixed' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'friendsoftypo3/headless-mixed',
       'label' => 'TYPO3 Headless Mixed',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
      ),
       'categoryDefinitions' => 
      array (
      ),
       'typoscript' => 'EXT:headless/Configuration/Sets/HeadlessMixed/',
       'pagets' => 'EXT:headless/Configuration/Sets/HeadlessMixed/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'typo3/email' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'typo3/email',
       'label' => 'TYPO3 Email Configuration',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'email.format',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.format',
           'description' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.description.email.format',
           'readonly' => false,
           'enum' => 
          array (
            '' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.format.enum.',
            'html' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.format.enum.html',
            'plain' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.format.enum.plain',
            'both' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.format.enum.both',
          ),
           'category' => 'email',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'email.templateRootPaths',
           'type' => 'stringlist',
           'default' => 
          array (
            0 => '',
          ),
           'label' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.templateRootPaths',
           'description' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.description.email.templateRootPaths',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'email',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'email.layoutRootPaths',
           'type' => 'stringlist',
           'default' => 
          array (
            0 => '',
          ),
           'label' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.layoutRootPaths',
           'description' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.description.email.layoutRootPaths',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'email',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        3 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'email.partialRootPaths',
           'type' => 'stringlist',
           'default' => 
          array (
            0 => '',
          ),
           'label' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.email.partialRootPaths',
           'description' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:settings.description.email.partialRootPaths',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'email',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
      ),
       'categoryDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'email',
           'label' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:categories.email',
           'description' => 'LLL:EXT:core/Configuration/Sets/Email/labels.xlf:categories.description.email',
           'icon' => NULL,
           'parent' => NULL,
        )),
      ),
       'typoscript' => 'EXT:core/Configuration/Sets/Email/',
       'pagets' => 'EXT:core/Configuration/Sets/Email/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'typo3/felogin' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'typo3/felogin',
       'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:label',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.pid',
           'type' => 'string',
           'default' => '0',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.pid',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.pid',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.recursive',
           'type' => 'string',
           'default' => '0',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.recursive',
           'readonly' => false,
           'enum' => 
          array (
            0 => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive.enum.0',
            1 => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive.enum.1',
            2 => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive.enum.2',
            3 => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive.enum.3',
            4 => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive.enum.4',
            255 => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.recursive.enum.255',
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.showForgotPassword',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.showForgotPassword',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.showForgotPassword',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        3 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.showPermaLogin',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.showPermaLogin',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.showPermaLogin',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        4 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.showLogoutFormAfterLogin',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.showLogoutFormAfterLogin',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.showLogoutFormAfterLogin',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        5 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.emailFrom',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.emailFrom',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.emailFrom',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        6 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.emailFromName',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.emailFromName',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.emailFromName',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        7 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.replyToEmail',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.replyToEmail',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.replyToEmail',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        8 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.dateFormat',
           'type' => 'string',
           'default' => 'Y-m-d H:i',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.dateFormat',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.dateFormat',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        9 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.email.layoutRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.email.layoutRootPath',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.email.layoutRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        10 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.email.templateRootPath',
           'type' => 'string',
           'default' => 'EXT:felogin/Resources/Private/Email/Templates/',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.email.templateRootPath',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.email.templateRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        11 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.email.partialRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.email.partialRootPath',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.email.partialRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        12 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.email.templateName',
           'type' => 'string',
           'default' => 'PasswordRecovery',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.email.templateName',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.email.templateName',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        13 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.redirectMode',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.redirectMode',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.redirectMode',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        14 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.redirectFirstMethod',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.redirectFirstMethod',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.redirectFirstMethod',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        15 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.redirectPageLogin',
           'type' => 'int',
           'default' => 0,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.redirectPageLogin',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.redirectPageLogin',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        16 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.redirectPageLoginError',
           'type' => 'int',
           'default' => 0,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.redirectPageLoginError',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.redirectPageLoginError',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        17 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.redirectPageLogout',
           'type' => 'int',
           'default' => 0,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.redirectPageLogout',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.redirectPageLogout',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        18 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.redirectDisable',
           'type' => 'bool',
           'default' => false,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.redirectDisable',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.redirectDisable',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        19 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.forgotLinkHashValidTime',
           'type' => 'int',
           'default' => 12,
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.forgotLinkHashValidTime',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.forgotLinkHashValidTime',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        20 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.domains',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.domains',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.domains',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        21 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.view.templateRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.view.templateRootPath',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.view.templateRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        22 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.view.partialRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.view.partialRootPath',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.view.partialRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        23 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'felogin.view.layoutRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.felogin.view.layoutRootPath',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:settings.description.felogin.view.layoutRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'felogin',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
      ),
       'categoryDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'felogin',
           'label' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:categories.felogin',
           'description' => 'LLL:EXT:felogin/Configuration/Sets/Felogin/labels.xlf:categories.description.felogin',
           'icon' => NULL,
           'parent' => NULL,
        )),
      ),
       'typoscript' => 'EXT:felogin/Configuration/Sets/Felogin/',
       'pagets' => 'EXT:felogin/Configuration/Sets/Felogin/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'typo3/form' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'typo3/form',
       'label' => 'Form Framework',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'form.templates.templateRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.form.templates.templateRootPath',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.description.form.templates.templateRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'form.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'form.templates.partialRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.form.templates.partialRootPath',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.description.form.templates.partialRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'form.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'form.templates.layoutRootPath',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.form.templates.layoutRootPath',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.description.form.templates.layoutRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'form.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        3 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'form.translation.translationFile',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.form.translation.translationFile',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:settings.description.form.translation.translationFile',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'form.translation',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
      ),
       'categoryDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'form',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:categories.form',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:categories.description.form',
           'icon' => NULL,
           'parent' => NULL,
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'form.templates',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:categories.form.templates',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:categories.description.form.templates',
           'icon' => NULL,
           'parent' => 'form',
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'form.translation',
           'label' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:categories.form.translation',
           'description' => 'LLL:EXT:form/Configuration/Sets/Form/labels.xlf:categories.description.form.translation',
           'icon' => NULL,
           'parent' => 'form',
        )),
      ),
       'typoscript' => 'EXT:form/Configuration/Sets/Form/',
       'pagets' => 'EXT:form/Configuration/Sets/Form/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
      ),
    )),
    'typo3/seo-sitemap' => 
    \TYPO3\CMS\Core\Site\Set\SetDefinition::__set_state(array(
       'name' => 'typo3/seo-sitemap',
       'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:label',
       'dependencies' => 
      array (
      ),
       'optionalDependencies' => 
      array (
      ),
       'settingsDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'seo.sitemap.view.templateRootPath',
           'type' => 'string',
           'default' => 'EXT:seo/Resources/Private/Templates/',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.seo.sitemap.view.templateRootPath',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.description.seo.sitemap.view.templateRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'seo.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'seo.sitemap.view.partialRootPath',
           'type' => 'string',
           'default' => 'EXT:seo/Resources/Private/Partials/',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.seo.sitemap.view.partialRootPath',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.description.seo.sitemap.view.partialRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'seo.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        2 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'seo.sitemap.view.layoutRootPath',
           'type' => 'string',
           'default' => 'EXT:seo/Resources/Private/Layouts/',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.seo.sitemap.view.layoutRootPath',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.description.seo.sitemap.view.layoutRootPath',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'seo.templates',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        3 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'seo.sitemap.pages.excludedDoktypes',
           'type' => 'string',
           'default' => '3, 4, 6, 7, 199, 254',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.seo.sitemap.pages.excludedDoktypes',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.description.seo.sitemap.pages.excludedDoktypes',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'seo',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        4 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'seo.sitemap.pages.excludePagesRecursive',
           'type' => 'string',
           'default' => '',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.seo.sitemap.pages.excludePagesRecursive',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.description.seo.sitemap.pages.excludePagesRecursive',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'seo',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
        5 => 
        \TYPO3\CMS\Core\Settings\SettingDefinition::__set_state(array(
           'key' => 'seo.sitemap.pages.additionalWhere',
           'type' => 'string',
           'default' => '{#no_index} = 0 AND {#canonical_link} = \'\'',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.seo.sitemap.pages.additionalWhere',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:settings.description.seo.sitemap.pages.additionalWhere',
           'readonly' => false,
           'enum' => 
          array (
          ),
           'category' => 'seo',
           'tags' => 
          array (
          ),
           'options' => 
          array (
          ),
        )),
      ),
       'categoryDefinitions' => 
      array (
        0 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'seo',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:categories.seo',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:categories.description.seo',
           'icon' => NULL,
           'parent' => NULL,
        )),
        1 => 
        \TYPO3\CMS\Core\Settings\CategoryDefinition::__set_state(array(
           'key' => 'seo.templates',
           'label' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:categories.seo.templates',
           'description' => 'LLL:EXT:seo/Configuration/Sets/Sitemap/labels.xlf:categories.description.seo.templates',
           'icon' => NULL,
           'parent' => 'seo',
        )),
      ),
       'typoscript' => 'EXT:seo/Configuration/Sets/Sitemap/',
       'pagets' => 'EXT:seo/Configuration/Sets/Sitemap/page.tsconfig',
       'settings' => 
      array (
      ),
       'hidden' => false,
       'routeEnhancers' => 
      array (
        'PageTypeSuffix' => 
        array (
          'type' => 'PageType',
          'map' => 
          array (
            'sitemap.xml' => 1533906435,
          ),
        ),
        'Sitemap' => 
        array (
          'type' => 'Simple',
          'routePath' => 'sitemap-type/{sitemap}',
          'aspects' => 
          array (
            'sitemap' => 
            array (
              'type' => 'StaticValueMapper',
              'map' => 
              array (
                'pages' => 'pages',
              ),
            ),
          ),
          '_arguments' => 
          array (
            'sitemap' => 'tx_seo/sitemap',
          ),
        ),
      ),
    )),
  ),
  'invalidSets' => 
  array (
  ),
);
#
<?php
return array (
  'main' => 
  array (
    'base' => 'https://api.typo3-inst.localhost/',
    'rootPageId' => 2,
    'websiteTitle' => 'TYPO3 Headless',
    'customConfiguration' => 
    array (
      'renderingMode' => 'headless',
    ),
    'dependencies' => 
    array (
      0 => 'friendsoftypo3/headless',
    ),
    'frontendBase' => 'https://typo3-inst.localhost/',
    'headless' => 1,
    'languages' => 
    array (
      0 => 
      array (
        'title' => 'English',
        'enabled' => true,
        'languageId' => 0,
        'base' => '/',
        'locale' => 'en_US.UTF-8',
        'navigationTitle' => 'English',
        'flag' => 'us',
        'frontendBase' => 'https://typo3-inst.localhost/',
      ),
    ),
    'routeEnhancers' => 
    array (
      'PageTypeSuffix' => 
      array (
        'type' => 'PageType',
        'map' => 
        array (
          '.json' => 834,
        ),
      ),
      'PixelcodaSearch' => 
      array (
        'type' => 'Simple',
        'routePath' => '/suche',
        '_arguments' => 
        array (
          'q' => 'q',
          'collections' => 'collections',
        ),
      ),
    ),
  ),
);
#
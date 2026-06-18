<?php
return array (
  'main' => 
  array (
    'base' => 'https://api.typo3-inst.localhost/',
    'customConfiguration' => 
    array (
      'renderingMode' => 'headless',
    ),
    'dependencies' => 
    array (
      0 => 'typo3/fluid-styled-content',
      1 => 'friendsoftypo3/headless',
      2 => 'friendsoftypo3/headless-mixed',
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
      ),
    ),
    'rootPageId' => 2,
    'websiteTitle' => '',
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
        'limitToPages' => 
        array (
          0 => 2,
        ),
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
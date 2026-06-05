<?php

declare(strict_types=1);

use PixelCoda\PixelcodaSearch\Controller\Backend\SearchModuleController;

return [
    'tools_PixelcodaSearchM1' => [
        'parent' => 'tools',
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/tools/pixelcoda-search',
        'iconIdentifier' => 'pixelcoda-search',
        'labels' => 'LLL:EXT:pixelcoda_search/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => SearchModuleController::class . '::handleRequest',
            ],
        ],
    ],
];

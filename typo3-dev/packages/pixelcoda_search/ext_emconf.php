<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pixelcoda Search',
    'description' => 'Accessible TYPO3 site search with autocomplete, faceted filters, classic and headless rendering, Meilisearch integration and optional AI answers.',
    'category' => 'plugin',
    'version' => '2.2.10',
    'state' => 'stable',
    'author' => 'Casian Blanaru',
    'author_email' => 'casianus@me.com',
    'author_company' => 'Pixelcoda by Casian Blanaru',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-14.9.99',
            'php' => '8.1.0-8.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'headless' => '4.0.0-4.99.99',
            'news' => '11.0.0-11.99.99',
            'form' => '12.4.0-12.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'PixelCoda\\PixelcodaSearch\\' => 'Classes/',
        ],
    ],
];

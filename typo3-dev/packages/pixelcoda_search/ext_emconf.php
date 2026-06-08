<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pixelcoda Search',
    'description' => 'Premium TYPO3 Search extension with autocomplete, suggestions, pagination, faceted filters, Meilisearch integration, headless JSON API, React-ready widgets, accessibility-first markup and optional AI/RAG answers for TYPO3 12, 13 and 14.',
    'category' => 'plugin',
    'version' => '2.2.11',
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

<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pixelcoda Sitepackage',
    'description' => 'TYPO3 sitepackage for Pixelcoda.',
    'category' => 'templates',
    'author' => 'Pixelcoda',
    'author_email' => '',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '14.3.0-14.9.99',
            'fluid_styled_content' => '14.3.0-14.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

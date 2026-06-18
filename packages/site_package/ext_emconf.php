<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Headless And PWA Demo Site Package',
    'description' => 'Provides site package for TYPO3 Headless And PWA Demo',
    'state' => 'stable',
    'author' => 'Łukasz Uznański',
    'author_email' => 'extensions@macopedia.pl',
    'category' => 'fe',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '14.3.0-14.99.99',
            'headless' => '4.0.0-5.99.99',
            'bootstrap_package' => '15.0.0-15.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

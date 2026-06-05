<?php

declare(strict_types=1);

$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'] = [
    'charset' => 'utf8mb4',
    'dbname' => (string)getenv('TYPO3_DB_DBNAME'),
    'defaultTableOptions' => [
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    'driver' => (string)(getenv('TYPO3_DB_DRIVER') ?: 'mysqli'),
    'host' => (string)getenv('TYPO3_DB_HOST'),
    'password' => (string)getenv('TYPO3_DB_PASSWORD'),
    'port' => (int)(getenv('TYPO3_DB_PORT') ?: 3306),
    'user' => (string)getenv('TYPO3_DB_USERNAME'),
];

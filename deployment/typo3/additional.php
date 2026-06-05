<?php

declare(strict_types=1);

$trustedHosts = array_filter([
    'localhost',
    '127.0.0.1',
    (string)getenv('RAILWAY_PUBLIC_DOMAIN'),
]);
$GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = sprintf(
    '^(?:%s)(?::\d+)?$',
    implode('|', array_map(
        static fn(string $host): string => preg_quote($host, '/'),
        $trustedHosts,
    )),
);

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

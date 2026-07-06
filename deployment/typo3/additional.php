<?php

declare(strict_types=1);

$backendBase = (string)(getenv('TYPO3_BACKEND_BASE') ?: getenv('RAILWAY_PUBLIC_DOMAIN') ?: 'localhost');
$frontendBase = (string)(getenv('TYPO3_FRONTEND_BASE') ?: '');

// Build trusted hosts from all known domains
$trustedHostCandidates = array_filter([
    'localhost',
    '127.0.0.1',
    (string)getenv('RAILWAY_PUBLIC_DOMAIN'),
    parse_url($backendBase, PHP_URL_HOST) ?: '',
    parse_url($frontendBase, PHP_URL_HOST) ?: '',
]);
$GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = sprintf(
    '^(?:%s)(?::\d+)?$',
    implode('|', array_map(
        static fn(string $host): string => preg_quote($host, '/'),
        array_unique(array_values($trustedHostCandidates)),
    )),
);
$GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyIP'] = '*';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxySSL'] = '*';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyHeaderMultiValue'] = 'first';

// Allow be_typo_user cookie to be sent cross-site (frontend on different domain)
$GLOBALS['TYPO3_CONF_VARS']['BE']['cookieSameSite'] = 'none';
$GLOBALS['TYPO3_CONF_VARS']['BE']['cookieSecure'] = 1;

$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'] = [
    'charset' => 'utf8mb4',
    'dbname' => (string)(getenv('TYPO3_DB_DBNAME') ?: getenv('MYSQLDATABASE')),
    'defaultTableOptions' => [
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    'driver' => (string)(getenv('TYPO3_DB_DRIVER') ?: 'mysqli'),
    'host' => (string)(getenv('TYPO3_DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1'),
    'password' => (string)(getenv('TYPO3_DB_PASSWORD') ?: getenv('MYSQLPASSWORD')),
    'port' => (int)(getenv('TYPO3_DB_PORT') ?: getenv('MYSQLPORT') ?: 3306),
    'user' => (string)(getenv('TYPO3_DB_USERNAME') ?: getenv('MYSQLUSER')),
];

$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] = array_replace(
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [],
    [
        'api_url' => (string)(getenv('PIXELCODA_API_URL') ?: 'http://127.0.0.1:8787'),
        'api_key' => (string)(getenv('PIXELCODA_API_KEY') ?: getenv('API_WRITE_KEY') ?: 'pc_write_dev_key'),
        'read_api_key' => (string)(getenv('PIXELCODA_READ_API_KEY') ?: getenv('API_READ_KEY') ?: 'pc_read_dev_key'),
        'project_id' => (string)(getenv('PIXELCODA_PROJECT_ID') ?: 'typo3'),
        'cors_origins' => (string)(getenv('PIXELCODA_CORS_ORIGINS') ?: '*'),
        'default_mode' => (string)(getenv('PIXELCODA_DEFAULT_MODE') ?: 'classic'),
        'enable_auto_index' => true,
        'enabled_tables' => 'pages,tt_content',
        'enable_vector_search' => true,
        'batch_size' => 50,
        'timeout' => 30,
        'enable_metrics' => false,
        'debug_mode' => false,
    ],
);

$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['content_gsap_animation'] = array_replace(
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['content_gsap_animation'] ?? [],
    [
        'disableAddAnimationsTab' => false,
        'extendedAnimationSettings' => true,
        'hideFooterAnimationLabel' => false,
    ],
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = 0;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '';

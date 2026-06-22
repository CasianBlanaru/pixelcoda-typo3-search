<?php

declare(strict_types=1);

header('Content-Type: application/json');

$settingsPath = dirname(__DIR__) . '/config/system/settings.php';
$installed = is_file($settingsPath);
$databaseConnected = false;

if ($installed && extension_loaded('mysqli')) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = mysqli_init();
    $connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
    $connected = @$connection->real_connect(
        (string)getenv('TYPO3_DB_HOST'),
        (string)getenv('TYPO3_DB_USERNAME'),
        (string)getenv('TYPO3_DB_PASSWORD'),
        (string)getenv('TYPO3_DB_DBNAME'),
        (int)(getenv('TYPO3_DB_PORT') ?: 3306),
    );
    $databaseConnected = $connected && $connection->ping();
    $connection->close();
}

http_response_code(200);
echo json_encode([
    'ok' => true,
    'service' => 'pixelcoda-typo3-suite',
    'installed' => $installed,
    'databaseConnected' => $databaseConnected,
], JSON_THROW_ON_ERROR);

<?php

declare(strict_types=1);

header('Content-Type: application/json');

$settingsPath = dirname(__DIR__) . '/config/system/settings.php';
$installed = is_file($settingsPath);
$databaseConnected = false;

if ($installed && extension_loaded('mysqli')) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = @new mysqli(
        (string)getenv('TYPO3_DB_HOST'),
        (string)getenv('TYPO3_DB_USERNAME'),
        (string)getenv('TYPO3_DB_PASSWORD'),
        (string)getenv('TYPO3_DB_DBNAME'),
        (int)(getenv('TYPO3_DB_PORT') ?: 3306),
    );
    $databaseConnected = $connection->connect_errno === 0 && $connection->ping();
    $connection->close();
}

$healthy = $installed && $databaseConnected;

http_response_code($healthy ? 200 : 503);
echo json_encode([
    'ok' => $healthy,
    'service' => 'pixelcoda-typo3-suite',
    'installed' => $installed,
    'databaseConnected' => $databaseConnected,
], JSON_THROW_ON_ERROR);

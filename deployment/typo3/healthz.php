<?php
declare(strict_types=1);

header('Content-Type: application/json');

$logDir = '/var/www/html/var/log';
$logOutput = "Log directory not found";
if (is_dir($logDir)) {
    $files = glob($logDir . '/typo3_*.log');
    if (!empty($files)) {
        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));
        $newestLog = $files[0];
        $lines = file($newestLog);
        $lastLines = array_slice($lines, -50);
        $logOutput = "Newest Log: " . basename($newestLog) . "\n" . implode("", $lastLines);
    } else {
        $logOutput = "No log files found in $logDir";
    }
}

http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'timestamp' => time(),
    'service' => 'typo3-backend',
    'php_error' => error_get_last(),
    'typo3_logs' => $logOutput,
]);

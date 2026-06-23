<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

echo "=== TYPO3 DIAGNOSTICS ===\n";

$logDir = '/var/www/html/var/log';
if (!is_dir($logDir)) {
    echo "Log directory not found: $logDir\n";
    exit;
}

$files = glob($logDir . '/typo3_*.log');
if (empty($files)) {
    echo "No log files found in $logDir\n";
} else {
    // Sort files by modification time descending
    usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));
    $newestLog = $files[0];
    echo "Reading newest log file: " . basename($newestLog) . "\n\n";
    
    $lines = file($newestLog);
    $lastLines = array_slice($lines, -50);
    echo implode("", $lastLines);
}

echo "\n=== PHP error_get_last() ===\n";
print_r(error_get_last());

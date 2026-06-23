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

$siteConfigData = "File not found";
$siteConfigPath = '/data/config/sites/main/config.yaml';
if (file_exists($siteConfigPath)) {
    $siteConfigData = file_get_contents($siteConfigPath);
}

$siteSetupData = "File not found";
$siteSetupPath = '/data/config/sites/main/setup.typoscript';
if (file_exists($siteSetupPath)) {
    $siteSetupData = file_get_contents($siteSetupPath);
}

$buildSiteConfigData = "File not found";
$buildSiteConfigPath = '/var/www/html/config/sites/main/config.yaml';
if (file_exists($buildSiteConfigPath)) {
    $buildSiteConfigData = file_get_contents($buildSiteConfigPath);
}

$sitesDirList = is_dir('/data/config/sites/main') ? scandir('/data/config/sites/main') : [];
$systemDirList = is_dir('/data/config/system') ? scandir('/data/config/system') : [];

http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'timestamp' => time(),
    'service' => 'typo3-backend',
    'php_error' => error_get_last(),
    'typo3_logs' => $logOutput,
    'data_site_config' => $siteConfigData,
    'data_setup_typoscript' => $siteSetupData,
    'build_site_config' => $buildSiteConfigData,
    'data_sites_dir' => $sitesDirList,
    'data_system_dir' => $systemDirList,
]);

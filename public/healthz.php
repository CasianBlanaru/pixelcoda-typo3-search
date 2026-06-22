<?php
declare(strict_types=1);

// Lightweight healthcheck for Railway deployment
http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'timestamp' => time(),
    'service' => 'typo3-backend'
]);
exit;

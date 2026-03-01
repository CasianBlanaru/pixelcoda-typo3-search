<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Authentication Service for HMAC and API Key validation.
 */
class AuthenticationService
{
    private array $config;

    public function __construct()
    {
        $this->config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [];
    }

    /**
     * Validate HMAC signature for webhook requests.
     */
    public function validateHmacSignature(string $payload, string $signature, ?string $secret = null): bool
    {
        $secret = $secret ?? $this->config['hmac_secret'] ?? '';

        if (empty($secret)) {
            return false;
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Generate HMAC signature for outgoing requests.
     */
    public function generateHmacSignature(string $payload, ?string $secret = null): string
    {
        $secret = $secret ?? $this->config['hmac_secret'] ?? '';

        if (empty($secret)) {
            return '';
        }

        return 'sha256=' . hash_hmac('sha256', $payload, $secret);
    }

    /**
     * Validate API key.
     */
    public function validateApiKey(string $apiKey, string $type = 'read'): bool
    {
        $validKeys = $this->getValidApiKeys();

        if ('read' === $type) {
            return in_array($apiKey, $validKeys['read'], true);
        }

        if ('write' === $type) {
            return in_array($apiKey, $validKeys['write'], true);
        }

        return false;
    }

    /**
     * Get valid API keys from configuration.
     */
    private function getValidApiKeys(): array
    {
        return [
            'read' => [
                $this->config['read_api_key'] ?? '',
                'pc_read_dev_key', // Default development key
            ],
            'write' => [
                $this->config['api_key'] ?? '',
                'pc_write_dev_key', // Default development key
            ],
        ];
    }

    /**
     * Validate CORS origin.
     */
    public function validateCorsOrigin(string $origin): bool
    {
        $allowedOrigins = $this->getAllowedOrigins();

        if (empty($allowedOrigins)) {
            return true; // Allow all if not configured
        }

        return in_array($origin, $allowedOrigins, true);
    }

    /**
     * Get allowed CORS origins.
     */
    private function getAllowedOrigins(): array
    {
        $origins = $this->config['cors_origins'] ?? [];

        if (is_string($origins)) {
            $origins = array_map('trim', explode(',', $origins));
        }

        // Add common development origins
        $origins = array_merge($origins, [
            'http://localhost:3000',
            'http://localhost:8080',
            'http://127.0.0.1:3000',
            'http://127.0.0.1:8080',
        ]);

        return array_filter($origins);
    }

    /**
     * Get authentication headers for outgoing requests.
     */
    public function getAuthHeaders(?string $apiKey = null): array
    {
        $apiKey = $apiKey ?? $this->config['api_key'] ?? '';
        $projectId = $this->config['project_id'] ?? '';

        return [
            'Authorization' => 'Bearer ' . $apiKey,
            'X-Project-ID' => $projectId,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Validate request authentication.
     */
    public function validateRequest(array $headers, string $body = ''): array
    {
        $result = [
            'valid' => false,
            'type' => 'none',
            'api_key' => null,
            'error' => null,
        ];

        // Check for API key authentication
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            $apiKey = $matches[1];

            if ($this->validateApiKey($apiKey, 'read')) {
                $result['valid'] = true;
                $result['type'] = 'api_key';
                $result['api_key'] = $apiKey;
                return $result;
            }

            if ($this->validateApiKey($apiKey, 'write')) {
                $result['valid'] = true;
                $result['type'] = 'api_key_write';
                $result['api_key'] = $apiKey;
                return $result;
            }

            $result['error'] = 'Invalid API key';
            return $result;
        }

        // Check for HMAC authentication
        $signature = $headers['X-Hub-Signature-256'] ?? $headers['x-hub-signature-256'] ?? '';
        if (!empty($signature) && !empty($body)) {
            if ($this->validateHmacSignature($body, $signature)) {
                $result['valid'] = true;
                $result['type'] = 'hmac';
                return $result;
            }

            $result['error'] = 'Invalid HMAC signature';
            return $result;
        }

        $result['error'] = 'No valid authentication found';
        return $result;
    }

    /**
     * Log authentication attempt.
     */
    public function logAuthAttempt(string $ip, string $userAgent, bool $success, ?string $error = null): void
    {
        if (!$this->config['enable_metrics'] ?? false) {
            return;
        }

        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $ip,
            'user_agent' => $userAgent,
            'success' => $success,
            'error' => $error,
        ];

        // Log to TYPO3 log
        GeneralUtility::sysLog(
            json_encode($logData),
            'pixelcoda_search',
            $success ? 0 : 2
        );
    }
}

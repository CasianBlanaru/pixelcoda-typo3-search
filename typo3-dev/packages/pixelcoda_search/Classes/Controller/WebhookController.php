<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Controller;

use PixelCoda\PixelcodaSearch\Service\AuthenticationService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Webhook Controller for handling incoming webhooks.
 */
class WebhookController extends ActionController
{
    private AuthenticationService $authService;

    public function __construct()
    {
        $this->authService = GeneralUtility::makeInstance(AuthenticationService::class);
    }

    /**
     * Handle incoming webhook from pixelcoda API.
     */
    public function indexAction(): ResponseInterface
    {
        $request = $this->request->getServerRequest();
        $headers = $request->getHeaders();
        $body = (string) $request->getBody();

        // Validate HMAC signature
        $signature = $headers['X-Hub-Signature-256'][0] ?? '';
        if (!$this->authService->validateHmacSignature($body, $signature)) {
            return new JsonResponse(['error' => 'Invalid signature'], 401);
        }

        // Parse webhook data
        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        // Process webhook based on event type
        $eventType = $data['event'] ?? 'unknown';
        $result = $this->processWebhook($eventType, $data);

        if ($result['success']) {
            return new JsonResponse(['status' => 'success', 'message' => $result['message']]);
        }

        return new JsonResponse(['error' => $result['error']], 500);
    }

    /**
     * Process webhook based on event type.
     */
    private function processWebhook(string $eventType, array $data): array
    {
        switch ($eventType) {
            case 'indexing.completed':
                return $this->handleIndexingCompleted($data);

            case 'indexing.failed':
                return $this->handleIndexingFailed($data);

            case 'search.analytics':
                return $this->handleSearchAnalytics($data);

            case 'webhook.test':
                return $this->handleWebhookTest($data);

            default:
                return [
                    'success' => false,
                    'error' => 'Unknown event type: ' . $eventType,
                ];
        }
    }

    /**
     * Handle indexing completed event.
     */
    private function handleIndexingCompleted(array $data): array
    {
        $projectId = $data['project_id'] ?? '';
        $documentCount = $data['document_count'] ?? 0;

        // Log the event
        GeneralUtility::sysLog(
            "Indexing completed for project {$projectId}: {$documentCount} documents",
            'pixelcoda_search',
            0
        );

        // Optionally trigger cache clearing or other actions
        $this->clearSearchCache();

        return [
            'success' => true,
            'message' => 'Indexing completed event processed',
        ];
    }

    /**
     * Handle indexing failed event.
     */
    private function handleIndexingFailed(array $data): array
    {
        $projectId = $data['project_id'] ?? '';
        $error = $data['error'] ?? 'Unknown error';

        // Log the error
        GeneralUtility::sysLog(
            "Indexing failed for project {$projectId}: {$error}",
            'pixelcoda_search',
            2
        );

        return [
            'success' => true,
            'message' => 'Indexing failed event processed',
        ];
    }

    /**
     * Handle search analytics event.
     */
    private function handleSearchAnalytics(array $data): array
    {
        $analytics = $data['analytics'] ?? [];

        // Store analytics data (implement your storage logic)
        $this->storeAnalytics($analytics);

        return [
            'success' => true,
            'message' => 'Search analytics processed',
        ];
    }

    /**
     * Handle webhook test event.
     */
    private function handleWebhookTest(array $data): array
    {
        $testMessage = $data['message'] ?? 'Test webhook received';

        GeneralUtility::sysLog(
            "Webhook test received: {$testMessage}",
            'pixelcoda_search',
            0
        );

        return [
            'success' => true,
            'message' => 'Webhook test successful',
        ];
    }

    /**
     * Clear search cache.
     */
    private function clearSearchCache(): void
    {
        // Clear TYPO3 cache if needed
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCachesInGroup('pages');
    }

    /**
     * Store analytics data.
     */
    private function storeAnalytics(array $analytics): void
    {
        // Implement analytics storage logic
        // This could store to database, file, or external service
        GeneralUtility::sysLog(
            'Analytics data received: ' . json_encode($analytics),
            'pixelcoda_search',
            0
        );
    }
}

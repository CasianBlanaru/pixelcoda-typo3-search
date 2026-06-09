<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Controller;

use PixelCoda\PixelcodaSearch\Service\AuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Webhook Controller for handling incoming webhooks.
 */
class WebhookController extends ActionController
{
    public function __construct(
        private readonly AuthenticationService $authService,
        private readonly CacheManager $cacheManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Handle incoming webhook from pixelcoda API.
     */
    public function indexAction(): ResponseInterface
    {
        $request = $this->request;
        $headers = $request->getHeaders();
        $body = (string) $request->getBody();

        // Validate HMAC signature
        $signature = $headers['X-Hub-Signature-256'][0] ?? '';
        if (!$this->authService->validateHmacSignature($body, $signature)) {
            return new JsonResponse(['error' => 'Invalid signature'], 401);
        }

        // Parse webhook data
        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
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
        return match ($eventType) {
            'indexing.completed' => $this->handleIndexingCompleted($data),
            'indexing.failed' => $this->handleIndexingFailed($data),
            'search.analytics' => $this->handleSearchAnalytics($data),
            'webhook.test' => $this->handleWebhookTest($data),
            default => [
                'success' => false,
                'error' => 'Unknown event type: ' . $eventType,
            ],
        };
    }

    /**
     * Handle indexing completed event.
     */
    private function handleIndexingCompleted(array $data): array
    {
        $projectId = $data['project_id'] ?? '';
        $documentCount = $data['document_count'] ?? 0;

        // Log the event
        $this->logger->info('Search indexing completed', [
            'projectId' => $projectId,
            'documentCount' => $documentCount,
        ]);

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
        $this->logger->error('Search indexing failed', [
            'projectId' => $projectId,
            'error' => $error,
        ]);

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

        $this->logger->info('Search webhook test received', ['message' => $testMessage]);

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
        $this->cacheManager->flushCachesInGroup('pages');
    }

    /**
     * Store analytics data.
     */
    private function storeAnalytics(array $analytics): void
    {
        // Implement analytics storage logic
        // This could store to database, file, or external service
        $this->logger->info('Search analytics received', ['analytics' => $analytics]);
    }
}

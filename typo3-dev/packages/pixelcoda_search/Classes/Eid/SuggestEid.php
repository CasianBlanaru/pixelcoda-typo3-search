<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Eid;

use PixelCoda\PixelcodaSearch\Service\SearchService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * eID script for AJAX search suggestions
 * 
 * This provides fast autocomplete suggestions for the search input
 */
class SuggestEid
{
    private SearchService $searchService;

    public function __construct()
    {
        $this->searchService = GeneralUtility::makeInstance(SearchService::class);
    }

    /**
     * Process the AJAX request for search suggestions
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public static function processRequest(ServerRequestInterface $request): ResponseInterface
    {
        $instance = GeneralUtility::makeInstance(self::class);
        return $instance->handleRequest($request);
    }

    /**
     * Handle the suggestion request
     * 
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        // Set CORS headers for frontend requests
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With',
        ];

        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, 200, $headers);
        }

        try {
            // Get query parameters
            $queryParams = $request->getQueryParams();
            $query = trim($queryParams['q'] ?? '');
            $limit = min((int)($queryParams['limit'] ?? 5), 20); // Max 20 suggestions
            $collections = $queryParams['collections'] ?? 'pages,tt_content';

            // Validate query
            if (empty($query) || strlen($query) < 2) {
                return new JsonResponse([
                    'suggestions' => [],
                    'meta' => [
                        'query' => $query,
                        'count' => 0,
                        'message' => 'Query too short (minimum 2 characters)'
                    ]
                ], 200, $headers);
            }

            // Get suggestions from search service
            $suggestions = $this->searchService->getSuggestions($query, $limit, $collections);

            // Format response
            $response = [
                'suggestions' => $suggestions,
                'meta' => [
                    'query' => $query,
                    'count' => count($suggestions),
                    'limit' => $limit,
                    'collections' => $collections
                ]
            ];

            return new JsonResponse($response, 200, $headers);

        } catch (\Exception $e) {
            // Log error but don't expose internal details
            $errorMessage = 'Suggestion request failed';
            
            if ($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search']['debug_mode'] ?? false) {
                $errorMessage .= ': ' . $e->getMessage();
            }

            return new JsonResponse([
                'suggestions' => [],
                'meta' => [
                    'query' => $query ?? '',
                    'count' => 0,
                    'error' => $errorMessage
                ]
            ], 500, $headers);
        }
    }

    /**
     * Initialize TYPO3 frontend if needed
     * This is required for proper database access and configuration
     */
    private function initializeFrontend(): void
    {
        if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
            // Basic frontend initialization for eID scripts
            $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
                TypoScriptFrontendController::class,
                null,
                0,
                0
            );
        }
    }
}

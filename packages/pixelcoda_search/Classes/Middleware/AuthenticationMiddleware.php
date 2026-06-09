<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Middleware;

use PixelCoda\PixelcodaSearch\Service\AuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Middleware for API authentication.
 */
class AuthenticationMiddleware implements MiddlewareInterface
{
    private readonly AuthenticationService $authService;

    public function __construct()
    {
        $this->authService = GeneralUtility::makeInstance(AuthenticationService::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Skip authentication for certain endpoints
        $path = $request->getUri()->getPath();
        if ($this->shouldSkipAuth($path)) {
            return $handler->handle($request);
        }

        // Get headers
        $headers = $request->getHeaders();
        $headersArray = [];
        foreach ($headers as $name => $values) {
            $headersArray[strtolower((string) $name)] = $values[0] ?? '';
        }

        // Get request body
        $body = (string) $request->getBody();

        // Validate authentication
        $authResult = $this->authService->validateRequest($headersArray, $body);

        if (!$authResult['valid']) {
            // Log failed attempt
            $this->authService->logAuthAttempt(
                $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
                $request->getServerParams()['HTTP_USER_AGENT'] ?? 'unknown',
                false,
                $authResult['error']
            );

            return new JsonResponse([
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Unauthorized',
                        'detail' => $authResult['error'] ?? 'Authentication required',
                    ],
                ],
            ], 401);
        }

        // Log successful attempt
        $this->authService->logAuthAttempt(
            $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
            $request->getServerParams()['HTTP_USER_AGENT'] ?? 'unknown',
            true
        );

        // Add authentication info to request attributes
        $request = $request->withAttribute('auth_type', $authResult['type'])
            ->withAttribute('api_key', $authResult['api_key']);

        return $handler->handle($request);
    }

    private function shouldSkipAuth(string $path): bool
    {
        $skipPaths = [
            '/v1/health',
            '/v1/status',
        ];

        foreach ($skipPaths as $skipPath) {
            if (str_contains($path, $skipPath)) {
                return true;
            }
        }

        return false;
    }
}

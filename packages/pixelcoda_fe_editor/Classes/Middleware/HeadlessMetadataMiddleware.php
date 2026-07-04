<?php

declare(strict_types=1);

namespace PixelCoda\FeEditor\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HeadlessMetadataMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $contentType = $response->getHeaderLine('Content-Type');
        if (!str_contains($contentType, 'application/json')) {
            return $response;
        }

        $context = GeneralUtility::makeInstance(Context::class);
        $isBeUserLoggedIn = $context->getPropertyFromAspect('backend.user', 'isLoggedIn', false);

        if (!$isBeUserLoggedIn) {
            return $response;
        }

        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        if (!is_array($data)) {
            return $response;
        }

        $data['_pixelcoda_editing_enabled'] = true;

        $newBody = new Stream('php://temp', 'rw');
        $newBody->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $response->withBody($newBody);
    }
}

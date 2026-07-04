<?php

declare(strict_types=1);

namespace PixelCoda\FeEditor\Api;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

final class SessionController
{
    public function handle(ServerRequestInterface $request): JsonResponse
    {
        $beUser = $GLOBALS['BE_USER'] ?? null;
        if (!$beUser instanceof BackendUserAuthentication || empty($beUser->user)) {
            return new JsonResponse(['hasSession' => false], 401);
        }

        $assetHash = $this->discoverAssetHash();

        return new JsonResponse([
            'hasSession' => true,
            'assetHash' => $assetHash,
            'userId' => (int)($beUser->user['uid'] ?? 0),
        ]);
    }

    private function discoverAssetHash(): ?string
    {
        $absolutePath = GeneralUtility::getFileAbsFileName('EXT:pixelcoda_fe_editor/Resources/Public/editor.js');
        if (!file_exists($absolutePath)) {
            return null;
        }

        $webPath = PathUtility::getAbsoluteWebPath($absolutePath);
        if (preg_match('#/_assets/([a-f0-9]{32})/#', $webPath, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

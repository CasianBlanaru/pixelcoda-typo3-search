<?php

declare(strict_types=1);

namespace PixelCoda\FeEditor\Api;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

final class SessionController
{
    public function __construct(
        private readonly FormProtectionFactory $formProtectionFactory,
        private readonly UriBuilder $uriBuilder,
    ) {}

    public function handle(ServerRequestInterface $request): JsonResponse
    {
        $beUser = $GLOBALS['BE_USER'] ?? null;
        if (!$beUser instanceof BackendUserAuthentication || empty($beUser->user)) {
            return new JsonResponse(['hasSession' => false], 401);
        }

        $assetHash = $this->discoverAssetHash();
        $feEditorToken = $this->formProtectionFactory
            ->createForType('backend')
            ->generateToken('pixelcoda-fe-editor', 'fe-editor-action');

        return new JsonResponse([
            'hasSession' => true,
            'assetHash' => $assetHash,
            'userId' => (int)($beUser->user['uid'] ?? 0),
            'feEditorToken' => $feEditorToken,
            'ajaxUrls' => [
                'fe_editor_save' => (string)$this->uriBuilder->buildUriFromRoute('ajax_fe_editor_save'),
                'fe_editor_ai' => (string)$this->uriBuilder->buildUriFromRoute('ajax_fe_editor_ai'),
            ],
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

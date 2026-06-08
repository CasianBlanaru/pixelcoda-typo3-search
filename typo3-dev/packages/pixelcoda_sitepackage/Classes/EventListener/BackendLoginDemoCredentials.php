<?php

declare(strict_types=1);

namespace Pixelcoda\Sitepackage\EventListener;

use TYPO3\CMS\Backend\LoginProvider\Event\ModifyPageLayoutOnLoginProviderSelectionEvent;

final class BackendLoginDemoCredentials
{
    public function __invoke(ModifyPageLayoutOnLoginProviderSelectionEvent $event): void
    {
        $event->getView()->assign(
            'loginFootnote',
            'Demo-Redakteur: pixelcoda-editor / PixelcodaDemo2026! Kein Admin-Zugang.'
        );
    }
}

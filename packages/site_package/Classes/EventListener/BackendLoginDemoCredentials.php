<?php

declare(strict_types=1);

namespace Pwademo\SitePackage\EventListener;

use TYPO3\CMS\Backend\LoginProvider\Event\ModifyPageLayoutOnLoginProviderSelectionEvent;

final class BackendLoginDemoCredentials
{
    public function __invoke(ModifyPageLayoutOnLoginProviderSelectionEvent $event): void
    {
        $event->getView()->assign(
            'loginFootnote',
            'Demo-Redakteur: pixelcoda-editor / PixelcodaDemo-2026! Kein Admin-Zugang. Zum Testen der Pixelcoda Extensions anmelden.'
        );
    }
}

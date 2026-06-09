<?php

declare(strict_types=1);

namespace Pixelcoda\Sitepackage\EventListener;

use TYPO3\CMS\Backend\LoginProvider\Event\ModifyPageLayoutOnLoginProviderSelectionEvent;

final class BackendLoginDemoCredentials
{
    public function __invoke(ModifyPageLayoutOnLoginProviderSelectionEvent $event): void
    {
        $password = (string) getenv('PIXELCODA_DEMO_EDITOR_PASSWORD');
        $message = 'Demo-Redakteur: pixelcoda-editor. Kein Admin-Zugang. Zum Testen der Pixelcoda Extensions anmelden.';
        if ('' !== trim($password)) {
            $message = 'Demo-Redakteur: pixelcoda-editor / Passwort aus Railway Demo-Konfiguration. Kein Admin-Zugang.';
        }

        $event->getView()->assign(
            'loginFootnote',
            $message
        );
    }
}

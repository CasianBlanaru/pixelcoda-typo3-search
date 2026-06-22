<?php

declare(strict_types=1);

call_user_func(static function (): void {
    $classLoader = require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
    \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::run(
        1,
        \TYPO3\CMS\Core\Core\SystemEnvironmentBuilder::REQUESTTYPE_INSTALL,
    );
    \TYPO3\CMS\Core\Core\Bootstrap::init($classLoader, true)
        ->get(\TYPO3\CMS\Install\Http\Application::class)
        ->run();
});

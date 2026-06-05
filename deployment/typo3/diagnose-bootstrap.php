<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Console\CommandApplication;
use TYPO3\CMS\Core\Core\BootService;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;

$root = '/var/www/html';

fwrite(STDERR, "TYPO3 bootstrap diagnostics\n");
fwrite(STDERR, sprintf(
    "php=%s app=%s root=%s context=%s\n",
    PHP_VERSION,
    getenv('TYPO3_PATH_APP') ?: '(unset)',
    getenv('TYPO3_PATH_ROOT') ?: '(unset)',
    getenv('TYPO3_CONTEXT') ?: '(unset)',
));

foreach ([
    'vendor/autoload.php',
    'vendor/typo3/PackageArtifact.php',
    'vendor/typo3/alias-loader-include.php',
    'vendor/typo3/cms-core/Classes/ServiceProvider.php',
    'config/system/settings.php',
] as $relativePath) {
    $path = $root . '/' . $relativePath;
    fwrite(STDERR, sprintf(
        "%s exists=%s readable=%s\n",
        $relativePath,
        file_exists($path) ? 'yes' : 'no',
        is_readable($path) ? 'yes' : 'no',
    ));
}

try {
    $classLoader = require $root . '/vendor/autoload.php';
    SystemEnvironmentBuilder::run(1, SystemEnvironmentBuilder::REQUESTTYPE_CLI);

    fwrite(STDERR, sprintf(
        "composerMode=%s commandClass=%s\n",
        Environment::isComposerMode() ? 'yes' : 'no',
        class_exists(CommandApplication::class) ? 'yes' : 'no',
    ));

    $failsafeContainer = Bootstrap::init($classLoader, true);
    fwrite(STDERR, sprintf(
        "failsafe=%s hasCommand=%s hasBootService=%s\n",
        get_debug_type($failsafeContainer),
        $failsafeContainer->has(CommandApplication::class) ? 'yes' : 'no',
        $failsafeContainer->has(BootService::class) ? 'yes' : 'no',
    ));

    $container = $failsafeContainer->get(BootService::class)->getContainer(false);
    fwrite(STDERR, sprintf(
        "container=%s hasCommand=%s\n",
        get_debug_type($container),
        $container->has(CommandApplication::class) ? 'yes' : 'no',
    ));
} catch (Throwable $exception) {
    do {
        fwrite(STDERR, sprintf(
            "%s: %s in %s:%d\n%s\n",
            $exception::class,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString(),
        ));
    } while ($exception = $exception->getPrevious());
}

<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Controller\Backend;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Configuration\Writer\YamlFileWriter;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Backend module controller for pixelcoda Search administration.
 */
class SearchModuleController
{
    private const MODULE_ROUTE = 'tools_PixelcodaSearchM1';

    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected UriBuilder $backendUriBuilder,
        protected ConfigurationManager $configurationManager,
        protected RequestFactory $requestFactory,
    ) {
    }

    /**
     * Main entry point for the backend module.
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getQueryParams()['action'] ?? 'index';

        return match ($action) {
            'switchMode' => $this->switchModeAction($request),
            'clearCache' => $this->clearCacheAction($request),
            'testConnection' => $this->testConnectionAction($request),
            default => $this->indexAction($request),
        };
    }

    /**
     * Main dashboard view.
     */
    protected function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($request);

        // Get current configuration
        $config = $this->getCurrentConfiguration();
        $siteConfig = $this->getSiteConfiguration();

        // Get current TYPO3 mode
        $currentMode = $this->getCurrentTYPO3Mode();
        $pluginMode = $config['default_mode'] ?? 'classic';

        // Check if modes are synchronized
        $modesSynchronized = $this->areModesSync($currentMode, $pluginMode);

        // Get system status
        $systemStatus = $this->getSystemStatus();

        $moduleTemplate->assignMultiple([
            'config' => $config,
            'siteConfig' => $siteConfig,
            'currentMode' => $currentMode,
            'pluginMode' => $pluginMode,
            'modesSynchronized' => $modesSynchronized,
            'systemStatus' => $systemStatus,
            'availableModes' => [
                'headless' => 'Headless (JSON API)',
                'standard' => 'Standard (HTML Templates)',
            ],
            'pluginModes' => [
                'headless' => 'Headless Mode',
                'classic' => 'Classic Mode',
            ],
            'moduleUrls' => $this->getModuleUrls(),
        ]);

        $moduleTemplate->setTitle('pixelcoda Search - Administration');

        return $moduleTemplate->renderResponse('Backend/Index');
    }

    /**
     * Switch TYPO3 and plugin mode.
     */
    protected function switchModeAction(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        $newMode = $parsedBody['mode'] ?? '';

        if (!in_array($newMode, ['headless', 'standard'], true)) {
            $this->addFlashMessage(
                'Ungültiger Modus ausgewählt.',
                'Fehler',
                ContextualFeedbackSeverity::ERROR
            );

            return $this->redirectToModule($request);
        }

        try {
            // 1. Update TYPO3 site configuration
            $this->updateSiteConfiguration($newMode);

            // 2. Update plugin configuration
            $pluginMode = 'headless' === $newMode ? 'headless' : 'classic';
            $this->updatePluginConfiguration($pluginMode);

            // 3. Switch PackageStates.php if files exist
            $this->switchPackageStates($newMode);

            // 4. Clear caches
            $this->clearAllCaches();

            $modeLabel = 'headless' === $newMode ? 'Headless' : 'Standard';
            $this->addFlashMessage(
                sprintf('TYPO3 wurde erfolgreich auf %s Modus umgestellt.', $modeLabel),
                'Modus geändert',
                ContextualFeedbackSeverity::OK
            );

            $this->addFlashMessage(
                'Alle Caches wurden geleert. Bitte lade deine Seite neu.',
                'Caches geleert',
                ContextualFeedbackSeverity::INFO
            );

        } catch (Exception $exception) {
            $this->addFlashMessage(
                'Fehler beim Umschalten des Modus: ' . $exception->getMessage(),
                'Fehler',
                ContextualFeedbackSeverity::ERROR
            );
        }

        return $this->redirectToModule($request);
    }

    /**
     * Clear all caches.
     */
    protected function clearCacheAction(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $this->clearAllCaches();

            $this->addFlashMessage(
                'Alle Caches wurden erfolgreich geleert.',
                'Caches geleert',
                ContextualFeedbackSeverity::OK
            );
        } catch (Exception $exception) {
            $this->addFlashMessage(
                'Fehler beim Leeren der Caches: ' . $exception->getMessage(),
                'Fehler',
                ContextualFeedbackSeverity::ERROR
            );
        }

        return $this->redirectToModule($request);
    }

    /**
     * Test API connection.
     */
    protected function testConnectionAction(ServerRequestInterface $request): ResponseInterface
    {
        $config = $this->getCurrentConfiguration();
        $apiUrl = $config['api_url'] ?? '';
        $apiKey = $config['api_key'] ?? '';

        if (empty($apiUrl) || empty($apiKey)) {
            $this->addFlashMessage(
                'API URL oder API Key nicht konfiguriert.',
                'Konfigurationsfehler',
                ContextualFeedbackSeverity::WARNING
            );

            return $this->redirectToModule($request);
        }

        try {
            $response = $this->requestFactory->request(
                rtrim((string) $apiUrl, '/') . '/health',
                'GET',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Accept' => 'application/json',
                    ],
                    'timeout' => 10,
                ]
            );

            if (200 === $response->getStatusCode()) {
                $this->addFlashMessage(
                    'API-Verbindung erfolgreich getestet.',
                    'Verbindung OK',
                    ContextualFeedbackSeverity::OK
                );
            } else {
                $this->addFlashMessage(
                    'API-Verbindung fehlgeschlagen. HTTP Code: ' . $response->getStatusCode(),
                    'Verbindungsfehler',
                    ContextualFeedbackSeverity::ERROR
                );
            }
        } catch (Exception $exception) {
            $this->addFlashMessage(
                'API nicht erreichbar: ' . $exception->getMessage(),
                'Verbindungsfehler',
                ContextualFeedbackSeverity::ERROR
            );
        }

        return $this->redirectToModule($request);
    }

    /**
     * Get current TYPO3 configuration.
     */
    protected function getCurrentConfiguration(): array
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [];
    }

    /**
     * Get current TYPO3 mode from site configuration.
     */
    protected function getCurrentTYPO3Mode(): string
    {
        $siteConfig = $this->getSiteConfiguration();
        return $siteConfig['customConfiguration']['renderingMode'] ?? 'standard';
    }

    /**
     * Get site configuration.
     */
    protected function getSiteConfiguration(): array
    {
        $configPath = $this->getSiteConfigPath();
        if (!file_exists($configPath)) {
            return [];
        }

        try {
            return GeneralUtility::makeInstance(YamlFileLoader::class)->load($configPath);
        } catch (Exception) {
            return [];
        }
    }

    /**
     * Check if TYPO3 mode and plugin mode are synchronized.
     */
    protected function areModesSync(string $typo3Mode, string $pluginMode): bool
    {
        return ('headless' === $typo3Mode && 'headless' === $pluginMode)
            || ('standard' === $typo3Mode && 'classic' === $pluginMode);
    }

    /**
     * Get system status information.
     */
    protected function getSystemStatus(): array
    {
        $config = $this->getCurrentConfiguration();

        return [
            'api_configured' => !empty($config['api_url']) && !empty($config['api_key']),
            'headless_extension' => ExtensionManagementUtility::isLoaded('headless'),
            'cache_status' => $this->getCacheStatus(),
            'last_index' => $this->getLastIndexTime(),
        ];
    }

    /**
     * Get cache status.
     */
    protected function getCacheStatus(): string
    {
        $cacheDir = Environment::getVarPath() . '/cache';

        if (!is_dir($cacheDir)) {
            return 'empty';
        }

        $files = glob($cacheDir . '/*');

        return [] === $files || false === $files ? 'empty' : 'populated';
    }

    /**
     * Get last index time (placeholder).
     */
    protected function getLastIndexTime(): ?string
    {
        // This would need to be implemented based on your indexing strategy
        return null;
    }

    /**
     * Update site configuration.
     */
    protected function updateSiteConfiguration(string $mode): void
    {
        $configPath = $this->getSiteConfigPath();
        if (!file_exists($configPath)) {
            throw new Exception('Site configuration file not found at ' . $configPath);
        }
        
        $loader = GeneralUtility::makeInstance(YamlFileLoader::class);
        $writer = GeneralUtility::makeInstance(YamlFileWriter::class);
        
        $config = $loader->load($configPath);
        if (!isset($config['customConfiguration']) || !is_array($config['customConfiguration'])) {
            $config['customConfiguration'] = [];
        }
        $config['customConfiguration']['renderingMode'] = $mode;

        try {
            $writer->write($configPath, $config);
        } catch (Exception $e) {
            throw new Exception('Failed to update site configuration: ' . $e->getMessage());
        }
    }

    /**
     * Resolves the absolute path to the site configuration.
     */
    protected function getSiteConfigPath(): string
    {
        // Use ProjectPath as primary for configuration files
        $path = Environment::getProjectPath() . '/config/sites/main/config.yaml';
        if (!file_exists($path)) {
            $path = Environment::getPublicPath() . '/../config/sites/main/config.yaml';
        }
        return $path;
    }

    /**
     * Update plugin configuration.
     */
    protected function updatePluginConfiguration(string $mode): void
    {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [];
        $config['default_mode'] = $mode;

        $this->configurationManager->setLocalConfigurationValueByPath(
            'EXTENSIONS/pixelcoda_search/default_mode',
            $mode
        );
    }

    /**
     * Switch PackageStates.php files.
     */
    protected function switchPackageStates(string $mode): void
    {
        $configDir = Environment::getProjectPath() . '/config/system';
        if (!is_dir($configDir)) {
            $configDir = Environment::getPublicPath() . '/../config/system';
        }
        $sourceFile = $configDir . '/PackageStates.php.' . $mode;
        $targetFile = $configDir . '/PackageStates.php';

        if (file_exists($sourceFile) && !copy($sourceFile, $targetFile)) {
            throw new Exception('Failed to switch PackageStates.php');
        }
    }

    /**
     * Clear all caches.
     */
    protected function clearAllCaches(): void
    {
        $this->ensureRuntimeDirectories();

        // Clear TYPO3 caches
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCaches();

        // Clear var/cache directory
        $cacheDir = Environment::getVarPath() . '/cache';
        if (is_dir($cacheDir)) {
            $this->removeDirectory($cacheDir);
        }

        // Clear typo3temp
        $tempVarDir = Environment::getPublicPath() . '/typo3temp/var';
        if (is_dir($tempVarDir)) {
            $this->removeDirectory($tempVarDir);
        }

        $this->ensureRuntimeDirectories();
    }

    protected function redirectToModule(ServerRequestInterface $request): RedirectResponse
    {
        return new RedirectResponse((string) $this->backendUriBuilder->buildUriFromRoute(self::MODULE_ROUTE));
    }

    protected function getModuleUrls(): array
    {
        return [
            'index' => (string) $this->backendUriBuilder->buildUriFromRoute(self::MODULE_ROUTE),
            'switchMode' => (string) $this->backendUriBuilder->buildUriFromRoute(self::MODULE_ROUTE, ['action' => 'switchMode']),
            'testConnection' => (string) $this->backendUriBuilder->buildUriFromRoute(self::MODULE_ROUTE, ['action' => 'testConnection']),
            'clearCache' => (string) $this->backendUriBuilder->buildUriFromRoute(self::MODULE_ROUTE, ['action' => 'clearCache']),
        ];
    }

    protected function ensureRuntimeDirectories(): void
    {
        $directories = [
            Environment::getVarPath(),
            Environment::getVarPath() . '/cache',
            Environment::getVarPath() . '/cache/code',
            Environment::getVarPath() . '/cache/code/core',
            Environment::getVarPath() . '/cache/code/core/tmp',
            Environment::getVarPath() . '/cache/data',
            Environment::getVarPath() . '/cache/data/database_schema',
            Environment::getVarPath() . '/cache/data/database_schema/tmp',
            Environment::getPublicPath() . '/typo3temp',
            Environment::getPublicPath() . '/typo3temp/assets',
            Environment::getPublicPath() . '/typo3temp/assets/css',
            Environment::getPublicPath() . '/typo3temp/assets/js',
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                GeneralUtility::mkdir_deep($directory);
            }
        }
    }

    /**
     * Remove directory recursively.
     */
    protected function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }

    /**
     * Add flash message.
     */
    protected function addFlashMessage(string $message, string $title = '', ContextualFeedbackSeverity $severity = ContextualFeedbackSeverity::INFO): void
    {
        $flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity, true);
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($flashMessage);
    }
}

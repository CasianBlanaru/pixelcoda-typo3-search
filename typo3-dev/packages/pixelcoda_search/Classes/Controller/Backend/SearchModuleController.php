<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Controller\Backend;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Backend module controller for pixelcoda Search administration.
 */
class SearchModuleController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    protected ConfigurationManager $configurationManager;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        ConfigurationManager $configurationManager
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->configurationManager = $configurationManager;
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
        $view = $this->createStandaloneView('Index');

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

        $view->assignMultiple([
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
        ]);

        $moduleTemplate->setContent($view->render());
        $moduleTemplate->setTitle('pixelcoda Search - Administration');

        return new HtmlResponse($moduleTemplate->renderContent());
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

            return new RedirectResponse($request->getUri()->getPath());
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
                "TYPO3 wurde erfolgreich auf {$modeLabel} Modus umgestellt.",
                'Modus geändert',
                ContextualFeedbackSeverity::OK
            );

            $this->addFlashMessage(
                'Alle Caches wurden geleert. Bitte lade deine Seite neu.',
                'Caches geleert',
                ContextualFeedbackSeverity::INFO
            );

        } catch (Exception $e) {
            $this->addFlashMessage(
                'Fehler beim Umschalten des Modus: ' . $e->getMessage(),
                'Fehler',
                ContextualFeedbackSeverity::ERROR
            );
        }

        return new RedirectResponse($request->getUri()->getPath());
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
        } catch (Exception $e) {
            $this->addFlashMessage(
                'Fehler beim Leeren der Caches: ' . $e->getMessage(),
                'Fehler',
                ContextualFeedbackSeverity::ERROR
            );
        }

        return new RedirectResponse($request->getUri()->getPath());
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

            return new RedirectResponse($request->getUri()->getPath());
        }

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => rtrim($apiUrl, '/') . '/v1/health',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (false !== $response && 200 === $httpCode) {
                $this->addFlashMessage(
                    'API-Verbindung erfolgreich getestet.',
                    'Verbindung OK',
                    ContextualFeedbackSeverity::OK
                );
            } else {
                $this->addFlashMessage(
                    "API-Verbindung fehlgeschlagen. HTTP Code: {$httpCode}",
                    'Verbindungsfehler',
                    ContextualFeedbackSeverity::ERROR
                );
            }
        } catch (Exception $e) {
            $this->addFlashMessage(
                'Fehler beim Testen der API-Verbindung: ' . $e->getMessage(),
                'Verbindungsfehler',
                ContextualFeedbackSeverity::ERROR
            );
        }

        return new RedirectResponse($request->getUri()->getPath());
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
        $configPath = Environment::getPublicPath() . '/../config/sites/main/config.yaml';

        if (!file_exists($configPath)) {
            return 'unknown';
        }

        $content = file_get_contents($configPath);
        if (preg_match('/renderingMode:\s*(\w+)/', $content, $matches)) {
            return $matches[1];
        }

        return 'standard'; // default
    }

    /**
     * Get site configuration.
     */
    protected function getSiteConfiguration(): array
    {
        $configPath = Environment::getPublicPath() . '/../config/sites/main/config.yaml';

        if (!file_exists($configPath)) {
            return [];
        }

        try {
            return yaml_parse_file($configPath) ?: [];
        } catch (Exception $e) {
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

        return empty($files) ? 'empty' : 'populated';
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
        $configPath = Environment::getPublicPath() . '/../config/sites/main/config.yaml';

        if (!file_exists($configPath)) {
            throw new Exception('Site configuration file not found');
        }

        $content = file_get_contents($configPath);
        $content = preg_replace('/renderingMode:\s*\w+/', "renderingMode: {$mode}", $content);

        if (false === file_put_contents($configPath, $content)) {
            throw new Exception('Failed to update site configuration');
        }
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
        $configDir = Environment::getPublicPath() . '/../config/system';
        $sourceFile = $configDir . '/PackageStates.php.' . $mode;
        $targetFile = $configDir . '/PackageStates.php';

        if (file_exists($sourceFile)) {
            if (!copy($sourceFile, $targetFile)) {
                throw new Exception('Failed to switch PackageStates.php');
            }
        }
    }

    /**
     * Clear all caches.
     */
    protected function clearAllCaches(): void
    {
        // Clear TYPO3 caches
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCaches();

        // Clear var/cache directory
        $cacheDir = Environment::getVarPath() . '/cache';
        if (is_dir($cacheDir)) {
            $this->removeDirectory($cacheDir);
        }

        // Clear typo3temp
        $tempDir = Environment::getPublicPath() . '/typo3temp';
        if (is_dir($tempDir)) {
            $this->removeDirectory($tempDir . '/var');
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
     * Create standalone view for templates.
     */
    protected function createStandaloneView(string $templateName): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            'EXT:pixelcoda_search/Resources/Private/Templates/Backend/' . $templateName . '.html'
        );
        $view->setLayoutRootPaths([
            'EXT:pixelcoda_search/Resources/Private/Layouts/Backend/',
        ]);
        $view->setPartialRootPaths([
            'EXT:pixelcoda_search/Resources/Private/Partials/Backend/',
        ]);

        return $view;
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

<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CLI command to switch between headless and standard mode.
 * Similar to the switch-typo3-mode.sh script but integrated into TYPO3.
 */
class SwitchModeCommand extends Command
{
    protected ConfigurationManager $configurationManager;

    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Switch between Headless and Standard rendering mode')
            ->setHelp('This command allows you to switch TYPO3 between Headless (JSON) and Standard (HTML) mode, similar to the switch-typo3-mode.sh script.')
            ->addArgument(
                'mode',
                InputArgument::OPTIONAL,
                'Target mode: headless or standard'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force mode switch without confirmation'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('🔄 TYPO3 Complete Mode Switcher');
        $io->text('Switches between Headless and Standard mode');
        $io->newLine();

        // Get current mode
        $currentMode = $this->getCurrentMode();
        $io->text("Current mode: <info>{$currentMode}</info>");
        $io->newLine();

        // Get target mode
        $targetMode = $input->getArgument('mode');
        
        if (!$targetMode) {
            $targetMode = $io->choice(
                'Select new mode:',
                [
                    'headless' => 'Headless (JSON output)',
                    'standard' => 'Standard (HTML output)'
                ],
                $currentMode === 'headless' ? 'standard' : 'headless'
            );
        }

        if (!in_array($targetMode, ['headless', 'standard'], true)) {
            $io->error('Invalid mode. Use "headless" or "standard".');
            return Command::FAILURE;
        }

        if ($currentMode === $targetMode) {
            $io->success("Already in {$targetMode} mode. Nothing to do.");
            return Command::SUCCESS;
        }

        // Confirm switch
        if (!$input->getOption('force')) {
            $modeLabel = $targetMode === 'headless' ? 'Headless (JSON API)' : 'Standard (HTML Templates)';
            if (!$io->confirm("Switch to {$modeLabel} mode?", true)) {
                $io->text('Mode switch cancelled.');
                return Command::SUCCESS;
            }
        }

        try {
            $io->section("🚀 Switching to {$targetMode} mode...");
            
            // Step 1: Update site configuration
            $io->text('📝 Updating site configuration...');
            $this->updateSiteConfiguration($targetMode);
            $io->text('✅ Site configuration updated');

            // Step 2: Update plugin configuration
            $io->text('⚙️  Updating plugin configuration...');
            $pluginMode = $targetMode === 'headless' ? 'headless' : 'classic';
            $this->updatePluginConfiguration($pluginMode);
            $io->text('✅ Plugin configuration updated');

            // Step 3: Switch PackageStates.php if available
            $io->text('📦 Switching PackageStates.php...');
            if ($this->switchPackageStates($targetMode)) {
                $io->text('✅ PackageStates.php switched');
            } else {
                $io->text('⚠️  PackageStates.php files not found, skipping');
            }

            // Step 4: Clear caches
            $io->text('🧹 Clearing all caches...');
            $this->clearAllCaches();
            $io->text('✅ All caches cleared');

            $io->newLine();
            $io->success("✨ Successfully switched to {$targetMode} mode!");
            $io->newLine();

            // Show mode-specific information
            if ($targetMode === 'headless') {
                $io->section('📌 Headless Mode Active:');
                $io->listing([
                    'JSON API: https://pixelcoda-typo3-dev.ddev.site/',
                    'All pages return JSON',
                    'Connect your React/Vue/Next.js frontend'
                ]);
            } else {
                $io->section('📌 Standard Mode Active:');
                $io->listing([
                    'HTML output: https://pixelcoda-typo3-dev.ddev.site/',
                    'Traditional TYPO3 with Fluid templates',
                    'SEO-friendly HTML pages'
                ]);
            }

            $io->note('🔄 Please reload your browser to see the changes!');

        } catch (\Exception $e) {
            $io->error('Failed to switch mode: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Get current TYPO3 mode from site configuration.
     */
    protected function getCurrentMode(): string
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
     * Update site configuration.
     */
    protected function updateSiteConfiguration(string $mode): void
    {
        $configPath = Environment::getPublicPath() . '/../config/sites/main/config.yaml';
        
        if (!file_exists($configPath)) {
            throw new \Exception('Site configuration file not found');
        }
        
        $content = file_get_contents($configPath);
        $content = preg_replace('/renderingMode:\s*\w+/', "renderingMode: {$mode}", $content);
        
        if (file_put_contents($configPath, $content) === false) {
            throw new \Exception('Failed to update site configuration');
        }
    }

    /**
     * Update plugin configuration.
     */
    protected function updatePluginConfiguration(string $mode): void
    {
        $this->configurationManager->setLocalConfigurationValueByPath(
            'EXTENSIONS/pixelcoda_search/default_mode',
            $mode
        );
    }

    /**
     * Switch PackageStates.php files.
     */
    protected function switchPackageStates(string $mode): bool
    {
        $configDir = Environment::getPublicPath() . '/../config/system';
        $sourceFile = $configDir . '/PackageStates.php.' . $mode;
        $targetFile = $configDir . '/PackageStates.php';
        
        if (!file_exists($sourceFile)) {
            return false;
        }
        
        if (!copy($sourceFile, $targetFile)) {
            throw new \Exception('Failed to switch PackageStates.php');
        }
        
        return true;
    }

    /**
     * Clear all caches.
     */
    protected function clearAllCaches(): void
    {
        // Clear TYPO3 caches
        $cacheManager = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
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
}

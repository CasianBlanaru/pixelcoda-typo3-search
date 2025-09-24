<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Hook;

use PixelCoda\PixelcodaSearch\Service\SearchService;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * DataHandler hook for automatic content indexing
 *
 * This hook listens to TYPO3 DataHandler operations and automatically
 * indexes content when it's created, updated, or deleted.
 */
class DatamapHook implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private SearchService $searchService;

    public function __construct()
    {
        $this->searchService = GeneralUtility::makeInstance(SearchService::class);
    }

    /**
     * Hook that is called before the datamap is processed
     *
     * @param DataHandler $dataHandler
     */
    public function processDatamap_beforeStart(DataHandler $dataHandler): void
    {
        // Initialize any pre-processing if needed
        $this->logger?->debug('pixelcoda Search: DataHandler processing started');
    }

    /**
     * Hook that is called after a record has been processed
     *
     * @param string $status The status of the operation (new, update)
     * @param string $table The table name
     * @param string|int $id The record ID
     * @param array $fieldArray The field values
     * @param DataHandler $dataHandler
     */
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        $id,
        array $fieldArray,
        DataHandler $dataHandler
    ): void {
        // Only index supported tables
        $enabledTables = $this->getEnabledTables();

        if (!in_array($table, $enabledTables, true)) {
            return;
        }

        try {
            switch ($status) {
                case 'new':
                    // Get the actual ID for new records
                    $actualId = $dataHandler->substNEWwithIDs[$id] ?? $id;
                    $this->indexRecord($table, (int)$actualId, 'create');
                    break;

                case 'update':
                    $this->indexRecord($table, (int)$id, 'update');
                    break;
            }
        } catch (\Exception $e) {
            $this->logger?->error('pixelcoda Search indexing failed', [
                'table' => $table,
                'id' => $id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Hook that is called after all commands have been processed
     *
     * @param DataHandler $dataHandler
     */
    public function processCmdmap_afterFinish(DataHandler $dataHandler): void
    {
        $enabledTables = $this->getEnabledTables();

        // Process all commands that were executed
        foreach ($dataHandler->cmdmap as $table => $commands) {
            if (!in_array($table, $enabledTables, true)) {
                continue;
            }

            foreach ($commands as $id => $commandData) {
                foreach ($commandData as $command => $value) {
                    try {
                        switch ($command) {
                            case 'delete':
                                $this->deleteFromIndex($table, (int)$id);
                                break;

                            case 'copy':
                                // Index the copied record
                                if (isset($dataHandler->copyMappingArray[$table][$id])) {
                                    $newId = $dataHandler->copyMappingArray[$table][$id];
                                    $this->indexRecord($table, (int)$newId, 'create');
                                }
                                break;

                            case 'move':
                                // Re-index moved record (URL might have changed)
                                $this->indexRecord($table, (int)$id, 'update');
                                break;
                        }
                    } catch (\Exception $e) {
                        $this->logger?->error('pixelcoda Search command processing failed', [
                            'table' => $table,
                            'id' => $id,
                            'command' => $command,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Index a single record
     *
     * @param string $table
     * @param int $id
     * @param string $action
     */
    private function indexRecord(string $table, int $id, string $action): void
    {
        if (!$this->isAutoIndexingEnabled()) {
            return;
        }

        $this->logger?->info('pixelcoda Search: Indexing record', [
            'table' => $table,
            'id' => $id,
            'action' => $action
        ]);

        // Delegate to SearchService for actual indexing
        $this->searchService->indexRecord($table, $id, $action);
    }

    /**
     * Remove a record from the search index
     *
     * @param string $table
     * @param int $id
     */
    private function deleteFromIndex(string $table, int $id): void
    {
        if (!$this->isAutoIndexingEnabled()) {
            return;
        }

        $this->logger?->info('pixelcoda Search: Deleting from index', [
            'table' => $table,
            'id' => $id
        ]);

        $this->searchService->deleteRecord($table, $id);
    }

    /**
     * Get enabled tables from extension configuration
     *
     * @return array
     */
    private function getEnabledTables(): array
    {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [];
        return $config['enabled_tables'] ?? ['pages', 'tt_content'];
    }

    /**
     * Check if auto-indexing is enabled
     *
     * @return bool
     */
    private function isAutoIndexingEnabled(): bool
    {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [];
        return (bool)($config['enable_auto_index'] ?? true);
    }
}

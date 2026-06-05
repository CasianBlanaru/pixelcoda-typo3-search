<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Service;

use Exception;
use RuntimeException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Search Service - communicates with pixelcoda Search API.
 */
class SearchService
{
    protected RequestFactory $requestFactory;

    protected Logger $logger;

    protected array $config;

    public function __construct(
        ?RequestFactory $requestFactory = null,
        ?ExtensionConfiguration $extensionConfiguration = null,
        private readonly ?ConnectionPool $connectionPool = null
    ) {
        $this->requestFactory = $requestFactory ?? GeneralUtility::makeInstance(RequestFactory::class);
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);

        $extConfig = $extensionConfiguration ?? GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->config = $extConfig->get('pixelcoda_search') ?? [];
    }

    /**
     * Perform search via API.
     */
    public function search(array $params): array
    {
        $apiUrl = rtrim($this->config['api_url'] ?? '', '/');
        $projectId = $this->config['project_id'] ?? 'typo3';
        $apiKey = $this->config['api_key'] ?? '';

        if ('' === $apiUrl || '0' === $apiUrl || empty($apiKey)) {
            throw new RuntimeException('pixelcoda Search API not configured');
        }

        $url = sprintf('%s/v1/search/%s', $apiUrl, $projectId);

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'Accept' => 'application/vnd.api+json',
                'User-Agent' => 'TYPO3-pixelcoda-Search/2.0',
            ],
            'body' => json_encode($params),
            'timeout' => $this->config['timeout'] ?? 30,
        ];

        try {
            $response = $this->requestFactory->request($url, 'POST', $requestOptions);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException(
                    sprintf('Search API returned status %s: ', $response->getStatusCode())
                    . $response->getBody()->getContents()
                );
            }

            $result = json_decode((string) $response->getBody()->getContents(), true);

            if (!$result) {
                throw new RuntimeException('Invalid JSON response from Search API');
            }

            $this->logger->info('Search completed', [
                'query' => $params['q'],
                'results' => count($result['data'] ?? []),
                'response_time' => $result['meta']['search']['response_time_ms'] ?? 0,
            ]);

            return $result;

        } catch (Exception $exception) {
            $this->logger->error('Search API error', [
                'query' => $params['q'],
                'error' => $exception->getMessage(),
                'url' => $url,
            ]);

            throw $exception;
        }
    }

    /**
     * Ask AI-powered question via API.
     */
    public function ask(array $params): array
    {
        $apiUrl = rtrim($this->config['api_url'] ?? '', '/');
        $projectId = $this->config['project_id'] ?? 'typo3';
        $apiKey = $this->config['api_key'] ?? '';

        if ('' === $apiUrl || '0' === $apiUrl || empty($apiKey)) {
            throw new RuntimeException('pixelcoda Search API not configured');
        }

        $url = sprintf('%s/v1/ask/%s', $apiUrl, $projectId);

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'Accept' => 'application/vnd.api+json',
                'User-Agent' => 'TYPO3-pixelcoda-Search/2.0',
            ],
            'body' => json_encode($params),
            'timeout' => ($this->config['timeout'] ?? 30) * 2, // Longer timeout for AI
        ];

        try {
            $response = $this->requestFactory->request($url, 'POST', $requestOptions);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException(
                    sprintf('Ask API returned status %s: ', $response->getStatusCode())
                    . $response->getBody()->getContents()
                );
            }

            $result = json_decode((string) $response->getBody()->getContents(), true);

            if (!$result) {
                throw new RuntimeException('Invalid JSON response from Ask API');
            }

            $this->logger->info('Ask completed', [
                'question' => $params['q'],
                'citations' => count($result['included'] ?? []),
                'response_time' => $result['meta']['generation']['response_time_ms'] ?? 0,
            ]);

            return $result;

        } catch (Exception $exception) {
            $this->logger->error('Ask API error', [
                'question' => $params['q'],
                'error' => $exception->getMessage(),
                'url' => $url,
            ]);

            throw $exception;
        }
    }

    /**
     * Get search suggestions via API.
     */
    public function suggest(array $params): array
    {
        $apiUrl = rtrim($this->config['api_url'] ?? '', '/');
        $projectId = $this->config['project_id'] ?? 'typo3';
        $apiKey = $this->config['api_key'] ?? '';

        if ('' === $apiUrl || '0' === $apiUrl || empty($apiKey)) {
            return ['data' => []]; // Return empty suggestions if not configured
        }

        $url = sprintf('%s/v1/suggest/%s', $apiUrl, $projectId);

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'Accept' => 'application/vnd.api+json',
                'User-Agent' => 'TYPO3-pixelcoda-Search/2.0',
            ],
            'body' => json_encode($params),
            'timeout' => 10, // Short timeout for suggestions
        ];

        try {
            $response = $this->requestFactory->request($url, 'POST', $requestOptions);

            if (200 !== $response->getStatusCode()) {
                return ['data' => []]; // Return empty on error
            }

            $result = json_decode((string) $response->getBody()->getContents(), true);

            return $result ?? ['data' => []];

        } catch (Exception $exception) {
            $this->logger->warning('Suggest API error', [
                'query' => $params['q'] ?? '',
                'error' => $exception->getMessage(),
            ]);

            return ['data' => []];
        }
    }

    /**
     * Log click metrics.
     */
    public function logClick(string $query, string $documentId, int $position, ?string $url = null): void
    {
        if (!($this->config['enable_metrics'] ?? true)) {
            return;
        }

        $apiUrl = rtrim($this->config['api_url'] ?? '', '/');
        $projectId = $this->config['project_id'] ?? 'typo3';
        $apiKey = $this->config['api_key'] ?? '';

        if ('' === $apiUrl || '0' === $apiUrl || empty($apiKey)) {
            return;
        }

        $metricsUrl = sprintf('%s/v1/metrics/click/%s', $apiUrl, $projectId);

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-Key' => $apiKey,
                'User-Agent' => 'TYPO3-pixelcoda-Search/2.0',
            ],
            'body' => json_encode([
                'query' => $query,
                'document_id' => $documentId,
                'position' => $position,
                'url' => $url,
            ]),
            'timeout' => 5,
        ];

        try {
            $this->requestFactory->request($metricsUrl, 'POST', $requestOptions);
        } catch (Exception $exception) {
            $this->logger->warning('Failed to log click metrics', [
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Check API health.
     */
    public function checkApiHealth(): array
    {
        $apiUrl = rtrim($this->config['api_url'] ?? '', '/');

        if ('' === $apiUrl || '0' === $apiUrl) {
            return ['status' => 'not_configured', 'message' => 'API URL not configured'];
        }

        try {
            $response = $this->requestFactory->request($apiUrl . '/health', 'GET', [
                'timeout' => 5,
            ]);

            if (200 === $response->getStatusCode()) {
                return ['status' => 'healthy', 'message' => 'API is responding'];
            }

            return ['status' => 'unhealthy', 'message' => 'API returned status ' . $response->getStatusCode()];

        } catch (Exception $exception) {
            return ['status' => 'error', 'message' => $exception->getMessage()];
        }
    }

    /**
     * Get search suggestions.
     */
    public function getSuggestions(string $query, int $limit = 5, string $collections = 'pages,tt_content'): array
    {
        $params = [
            'q' => $query,
            'limit' => $limit,
            'collections' => explode(',', $collections),
        ];

        $result = $this->suggest($params);

        return $result['data'] ?? [];
    }

    /**
     * Index a single record.
     */
    public function indexRecord(string $table, int $id, string $action = 'update', bool $force = false): bool
    {
        if ('delete' === $action) {
            return $this->deleteRecord($table, $id);
        }

        $records = $this->fetchRecords($table, $id);
        if ([] === $records) {
            return false;
        }

        $this->sendDocuments($table, $records);

        return true;
    }

    /**
     * Delete a record from the search index.
     */
    public function deleteRecord(string $table, int $id): bool
    {
        $this->requestIndexApi($table, 'DELETE', ['ids' => [(string) $id]]);

        return true;
    }

    /**
     * Index all records from a table.
     */
    public function indexTable(string $table, bool $force = false): int
    {
        $records = $this->fetchRecords($table);
        $batchSize = max(1, (int) ($this->config['batch_size'] ?? 50));
        foreach (array_chunk($records, $batchSize) as $batch) {
            $this->sendDocuments($table, $batch);
        }

        return count($records);
    }

    /**
     * Get record count for a table.
     */
    public function getTableRecordCount(string $table): int
    {
        return count($this->fetchRecords($table));
    }

    /**
     * Clear index for a specific table.
     */
    public function clearTableIndex(string $table): bool
    {
        $this->requestIndexApi($table, 'DELETE', ['all' => true]);

        return true;
    }

    /**
     * Clear all indexes.
     */
    public function clearAllIndexes(): bool
    {
        foreach ($this->getAllowedTables() as $table) {
            $this->clearTableIndex($table);
        }

        return true;
    }

    /**
     * Get index statistics.
     */
    public function getIndexStatistics(): ?array
    {
        $total = 0;
        foreach ($this->getAllowedTables() as $table) {
            $result = $this->requestIndexApi($table, 'GET');
            $total += (int) ($result['documents'] ?? 0);
        }

        return ['total_documents' => $total];
    }

    private function getConnectionPool(): ConnectionPool
    {
        return $this->connectionPool ?? GeneralUtility::makeInstance(ConnectionPool::class);
    }

    private function getAllowedTables(): array
    {
        return ['pages', 'tt_content'];
    }

    private function fetchRecords(string $table, ?int $id = null): array
    {
        if (!in_array($table, $this->getAllowedTables(), true)) {
            throw new RuntimeException('Unsupported index table: ' . $table);
        }

        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();
        $fields = 'pages' === $table
            ? ['uid', 'pid', 'title', 'slug', 'abstract', 'keywords', 'tstamp']
            : ['uid', 'pid', 'header', 'bodytext', 'CType', 'tstamp'];
        $queryBuilder->select(...$fields)
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq('deleted', 0),
                $queryBuilder->expr()->eq('hidden', 0)
            );
        if (null !== $id) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id)));
        }

        return array_map(
            fn (array $record): array => $this->mapRecord($table, $record),
            $queryBuilder->executeQuery()->fetchAllAssociative()
        );
    }

    private function mapRecord(string $table, array $record): array
    {
        if ('pages' === $table) {
            return [
                'id' => (string) $record['uid'],
                'title' => (string) $record['title'],
                'summary' => trim(strip_tags((string) $record['abstract'])),
                'content' => trim(strip_tags((string) $record['abstract'])),
                'keywords' => (string) $record['keywords'],
                'url' => (string) ($record['slug'] ?: '/?id=' . $record['uid']),
                'language' => 'de',
            ];
        }

        return [
            'id' => (string) $record['uid'],
            'title' => (string) ($record['header'] ?: 'Inhalt ' . $record['uid']),
            'summary' => trim(strip_tags((string) $record['bodytext'])),
            'content' => trim(strip_tags((string) $record['bodytext'])),
            'url' => '/?id=' . $record['pid'] . '#c' . $record['uid'],
            'language' => 'de',
        ];
    }

    private function sendDocuments(string $table, array $documents): void
    {
        $this->requestIndexApi($table, 'POST', ['documents' => $documents]);
    }

    private function requestIndexApi(string $table, string $method, array $payload = []): array
    {
        $apiUrl = rtrim((string) ($this->config['api_url'] ?? ''), '/');
        $projectId = (string) ($this->config['project_id'] ?? 'typo3');
        $apiKey = (string) ($this->config['api_key'] ?? '');
        if ('' === $apiUrl || '' === $apiKey) {
            throw new RuntimeException('pixelcoda Search write API is not configured');
        }

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ],
            'timeout' => (int) ($this->config['timeout'] ?? 30),
        ];
        if ([] !== $payload) {
            $options['body'] = json_encode($payload, JSON_THROW_ON_ERROR);
        }

        $response = $this->requestFactory->request(
            sprintf('%s/v1/index/%s/%s', $apiUrl, rawurlencode($projectId), rawurlencode($table)),
            $method,
            $options
        );
        if ($response->getStatusCode() >= 300) {
            throw new RuntimeException('Index API returned status ' . $response->getStatusCode());
        }

        return json_decode((string) $response->getBody(), true) ?: [];
    }
}

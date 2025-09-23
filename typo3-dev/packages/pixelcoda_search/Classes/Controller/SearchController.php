<?php
declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use PixelCoda\PixelcodaSearch\Service\SearchService;
use PixelCoda\PixelcodaSearch\Service\ConfigurationService;

/**
 * Search Controller for both Classic (Fluid) and Headless (JSON:API) modes
 */
class SearchController extends ActionController
{
    protected SearchService $searchService;
    protected ConfigurationService $configurationService;

    public function __construct(
        SearchService $searchService,
        ConfigurationService $configurationService
    ) {
        $this->searchService = $searchService;
        $this->configurationService = $configurationService;
    }

    /**
     * Initialize action - setup common variables
     */
    public function initializeAction(): void
    {
        parent::initializeAction();
        
        // Get plugin configuration
        $this->settings = $this->configurationService->getPluginSettings($this->settings);
        
        // Note: setFormat() is deprecated in TYPO3 v12
        // Format is handled in individual actions
    }

    /**
     * Main search form action
     */
    public function indexAction(): ResponseInterface
    {
        $mode = $this->settings['mode'] ?? 'classic';
        $query = $this->request->hasArgument('q') ? $this->request->getArgument('q') : '';
        
        // Prepare view variables
        $this->view->assignMultiple([
            'mode' => $mode,
            'query' => $query,
            'settings' => $this->settings,
            'placeholder' => $this->getLocalizedPlaceholder(),
            'languages' => $this->getAvailableLanguages(),
            'collections' => $this->getAvailableCollections()
        ]);

        if ($this->isHeadlessMode()) {
            return $this->createJsonResponse([
                'data' => [
                    'type' => 'searchForm',
                    'id' => 'search-form',
                    'attributes' => [
                        'mode' => $mode,
                        'query' => $query,
                        'placeholder' => $this->getLocalizedPlaceholder(),
                        'settings' => $this->settings
                    ]
                ],
                'meta' => [
                    'mode' => 'headless',
                    'version' => '2.0.0'
                ]
            ]);
        }

        return $this->htmlResponse();
    }

    /**
     * Search action - performs actual search
     */
    public function searchAction(): ResponseInterface
    {
        $query = $this->request->hasArgument('q') ? trim($this->request->getArgument('q')) : '';
        $page = max(1, (int)($this->request->hasArgument('page') ? $this->request->getArgument('page') : 1));
        $collections = $this->request->hasArgument('collections') ? $this->request->getArgument('collections') : [];
        
        if (empty($query)) {
            if ($this->isHeadlessMode()) {
                return $this->createJsonResponse([
                    'errors' => [[
                        'status' => '400',
                        'title' => 'Bad Request',
                        'detail' => 'Search query is required'
                    ]]
                ], 400);
            }
            
            $this->addFlashMessage(
                $this->translate('search.error.emptyQuery', 'Please enter a search term'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            return $this->redirect('index');
        }

        try {
            $searchResults = $this->searchService->search([
                'q' => $query,
                'page' => $page,
                'limit' => (int)($this->settings['resultsPerPage'] ?? 10),
                'collections' => is_array($collections) ? $collections : [],
                'lang' => $this->getCurrentLanguage()
            ]);

            if ($this->isHeadlessMode()) {
                return $this->createJsonResponse($searchResults);
            }

            // Classic mode - assign to Fluid template
            $this->view->assignMultiple([
                'query' => $query,
                'results' => $searchResults,
                'pagination' => $this->buildPagination($searchResults, $page),
                'settings' => $this->settings,
                'totalResults' => $searchResults['meta']['pagination']['total'] ?? 0,
                'responseTime' => $searchResults['meta']['search']['response_time_ms'] ?? 0
            ]);

            return $this->htmlResponse();

        } catch (\Exception $e) {
            if ($this->isHeadlessMode()) {
                return $this->createJsonResponse([
                    'errors' => [[
                        'status' => '500',
                        'title' => 'Search Error',
                        'detail' => $e->getMessage()
                    ]]
                ], 500);
            }

            $this->addFlashMessage(
                $this->translate('search.error.general', 'Search failed. Please try again.'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            
            return $this->redirect('index');
        }
    }

    /**
     * Ask action - AI-powered answers
     */
    public function askAction(): ResponseInterface
    {
        $question = $this->request->hasArgument('q') ? trim($this->request->getArgument('q')) : '';
        $collections = $this->request->hasArgument('collections') ? $this->request->getArgument('collections') : [];
        
        if (empty($question)) {
            if ($this->isHeadlessMode()) {
                return $this->createJsonResponse([
                    'errors' => [[
                        'status' => '400',
                        'title' => 'Bad Request',
                        'detail' => 'Question is required'
                    ]]
                ], 400);
            }
            
            $this->addFlashMessage(
                $this->translate('ask.error.emptyQuestion', 'Please enter a question'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            return $this->redirect('index');
        }

        try {
            $askResults = $this->searchService->ask([
                'q' => $question,
                'collections' => is_array($collections) ? $collections : [],
                'lang' => $this->getCurrentLanguage(),
                'maxPassages' => (int)($this->settings['maxPassages'] ?? 6),
                'includeDebug' => (bool)($this->settings['showDebug'] ?? false)
            ]);

            if ($this->isHeadlessMode()) {
                return $this->createJsonResponse($askResults);
            }

            // Classic mode - assign to Fluid template
            $this->view->assignMultiple([
                'question' => $question,
                'answer' => $askResults,
                'settings' => $this->settings
            ]);

            return $this->htmlResponse();

        } catch (\Exception $e) {
            if ($this->isHeadlessMode()) {
                return $this->createJsonResponse([
                    'errors' => [[
                        'status' => '500',
                        'title' => 'Ask Error',
                        'detail' => $e->getMessage()
                    ]]
                ], 500);
            }

            $this->addFlashMessage(
                $this->translate('ask.error.general', 'Failed to generate answer. Please try again.'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            
            return $this->redirect('index');
        }
    }

    /**
     * Results action - display search results (classic mode only)
     */
    public function resultsAction(): ResponseInterface
    {
        // This action is used for AJAX result updates in classic mode
        $query = $this->request->hasArgument('q') ? trim($this->request->getArgument('q')) : '';
        $page = max(1, (int)($this->request->hasArgument('page') ? $this->request->getArgument('page') : 1));
        
        if (empty($query)) {
            $this->view->assign('error', $this->translate('search.error.emptyQuery', 'Please enter a search term'));
            return $this->htmlResponse();
        }

        try {
            $searchResults = $this->searchService->search([
                'q' => $query,
                'page' => $page,
                'limit' => (int)($this->settings['resultsPerPage'] ?? 10),
                'lang' => $this->getCurrentLanguage()
            ]);

            $this->view->assignMultiple([
                'query' => $query,
                'results' => $searchResults,
                'pagination' => $this->buildPagination($searchResults, $page),
                'totalResults' => $searchResults['meta']['pagination']['total'] ?? 0,
                'responseTime' => $searchResults['meta']['search']['response_time_ms'] ?? 0
            ]);

        } catch (\Exception $e) {
            $this->view->assign('error', $this->translate('search.error.general', 'Search failed. Please try again.'));
        }

        return $this->htmlResponse();
    }

    /**
     * Check if running in headless mode
     */
    protected function isHeadlessMode(): bool
    {
        // Check FlexForm setting first
        if (isset($this->settings['mode'])) {
            return $this->settings['mode'] === 'headless';
        }

        // Check TypoScript setting
        $mode = $this->settings['defaultMode'] ?? 'classic';
        return $mode === 'headless';
    }

    /**
     * Get current language
     */
    protected function getCurrentLanguage(): string
    {
        $languageAspect = $this->request->getAttribute('language');
        $languageId = $languageAspect ? $languageAspect->getId() : 0;
        
        // Simple language mapping
        $languageMap = [
            0 => 'de',
            1 => 'en',
            2 => 'fr',
            3 => 'es'
        ];
        
        return $languageMap[$languageId] ?? 'de';
    }

    /**
     * Get available languages for frontend
     */
    protected function getAvailableLanguages(): array
    {
        // This would typically come from site configuration
        return [
            ['code' => 'de', 'title' => 'Deutsch'],
            ['code' => 'en', 'title' => 'English']
        ];
    }

    /**
     * Get available collections
     */
    protected function getAvailableCollections(): array
    {
        return [
            ['key' => 'pages', 'title' => $this->translate('collections.pages', 'Pages')],
            ['key' => 'news', 'title' => $this->translate('collections.news', 'News')],
            ['key' => 'tt_content', 'title' => $this->translate('collections.content', 'Content Elements')]
        ];
    }

    /**
     * Get localized placeholder text
     */
    protected function getLocalizedPlaceholder(): string
    {
        return $this->translate('search.placeholder', 'Website durchsuchen...');
    }

    /**
     * Build pagination array for Fluid template
     */
    protected function buildPagination(array $searchResults, int $currentPage): array
    {
        $meta = $searchResults['meta']['pagination'] ?? [];
        $totalPages = $meta['pages'] ?? 1;
        
        $pagination = [
            'current' => $currentPage,
            'total' => $totalPages,
            'hasNext' => $currentPage < $totalPages,
            'hasPrev' => $currentPage > 1,
            'next' => min($currentPage + 1, $totalPages),
            'prev' => max($currentPage - 1, 1),
            'pages' => []
        ];

        // Generate page numbers (show 5 pages around current)
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $pagination['pages'][] = [
                'number' => $i,
                'isCurrent' => $i === $currentPage
            ];
        }

        return $pagination;
    }

    /**
     * Create JSON response for headless mode
     */
    protected function createJsonResponse(array $data, int $statusCode = 200): ResponseInterface
    {
        return new JsonResponse($data, $statusCode, [
            'Content-Type' => 'application/vnd.api+json',
            'X-Powered-By' => 'pixelcoda Search v2.0'
        ]);
    }

    /**
     * Translate label with fallback
     */
    protected function translate(string $key, string $fallback = ''): string
    {
        $translated = $this->getLanguageService()->sL('LLL:EXT:pixelcoda_search/Resources/Private/Language/locallang.xlf:' . $key);
        return $translated ?: $fallback;
    }

    /**
     * Get language service
     */
    protected function getLanguageService(): \TYPO3\CMS\Core\Localization\LanguageService
    {
        return $GLOBALS['LANG'] ?? GeneralUtility::makeInstance(\TYPO3\CMS\Core\Localization\LanguageService::class);
    }
}

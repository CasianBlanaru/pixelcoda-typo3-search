<?php
declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Search Controller for handling search requests
 */
class SearchController extends ActionController
{
    /**
     * Display search form
     */
    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * Handle search and display results with pagination
     */
    public function searchAction(): ResponseInterface
    {
        $searchQuery = $this->request->getQueryParams()['q'] ?? '';
        $searchQuery = trim($searchQuery);
        $currentPage = (int)($this->request->getQueryParams()['page'] ?? 1);
        
        // Get settings from FlexForm or fallback to defaults
        $resultsPerPage = (int)($this->settings['resultsPerPage'] ?? 10);
        $minQueryLength = (int)($this->settings['minQueryLength'] ?? 3);
        $sortOrder = $this->settings['sortOrder'] ?? 'relevance';
        
        $results = [];
        $message = '';
        $totalResults = 0;
        $pagination = [];
        
        if (strlen($searchQuery) < $minQueryLength) {
            $message = 'Bitte geben Sie mindestens ' . $minQueryLength . ' Zeichen ein.';
        } else {
            // Search in pages with enhanced features
            $allResults = $this->searchInPagesEnhanced($searchQuery, $sortOrder);
            $totalResults = count($allResults);
            
            // Calculate pagination
            $totalPages = ceil($totalResults / $resultsPerPage);
            $currentPage = max(1, min($currentPage, $totalPages));
            $offset = ($currentPage - 1) * $resultsPerPage;
            
            // Get results for current page
            $results = array_slice($allResults, $offset, $resultsPerPage);
            
            if (empty($results)) {
                $message = 'Keine Ergebnisse für "' . htmlspecialchars($searchQuery) . '" gefunden.';
            } else {
                $message = $totalResults . ' Ergebnis(se) für "' . htmlspecialchars($searchQuery) . '" gefunden.';
            }
            
            // Build pagination array
            if ($totalPages > 1) {
                $pagination = [
                    'current' => $currentPage,
                    'total' => $totalPages,
                    'prev' => $currentPage > 1 ? $currentPage - 1 : null,
                    'next' => $currentPage < $totalPages ? $currentPage + 1 : null,
                    'pages' => range(1, $totalPages)
                ];
            }
        }
        
        $this->view->assignMultiple([
            'searchQuery' => htmlspecialchars($searchQuery),
            'results' => $results,
            'message' => $message,
            'pagination' => $pagination,
            'totalResults' => $totalResults,
            'settings' => $this->settings
        ]);
        
        return $this->htmlResponse();
    }
    
    /**
     * Search in pages table
     */
    protected function searchInPages(string $query): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');
        
        $searchTerms = '%' . $queryBuilder->escapeLikeWildcards($query) . '%';
        
        $statement = $queryBuilder
            ->select('uid', 'title', 'abstract', 'keywords')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->like('title', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('abstract', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('keywords', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('description', $queryBuilder->createNamedParameter($searchTerms))
                ),
                $queryBuilder->expr()->eq('deleted', 0),
                $queryBuilder->expr()->eq('hidden', 0)
            )
            ->setMaxResults(20)
            ->execute();
        
        $results = [];
        while ($row = $statement->fetchAssociative()) {
            // Build URL for the page
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $conf = [
                'parameter' => $row['uid'],
                'returnLast' => 'url',
                'forceAbsoluteUrl' => 0
            ];
            $url = $cObj->typoLink_URL($conf);
            
            $results[] = [
                'title' => $row['title'],
                'abstract' => $row['abstract'] ?: 'Keine Beschreibung verfügbar.',
                'url' => '/' . ltrim($url, '/')
            ];
        }
        
        // Also search in tt_content
        $contentResults = $this->searchInContent($query);
        $results = array_merge($results, $contentResults);
        
        return $results;
    }
    
    /**
     * Search in content elements
     */
    protected function searchInContent(string $query): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        
        $searchTerms = '%' . $queryBuilder->escapeLikeWildcards($query) . '%';
        
        $statement = $queryBuilder
            ->select('tt_content.uid', 'tt_content.header', 'tt_content.bodytext', 'tt_content.pid', 'pages.title as page_title')
            ->from('tt_content')
            ->leftJoin(
                'tt_content',
                'pages',
                'pages',
                $queryBuilder->expr()->eq('tt_content.pid', $queryBuilder->quoteIdentifier('pages.uid'))
            )
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->like('tt_content.header', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('tt_content.bodytext', $queryBuilder->createNamedParameter($searchTerms))
                ),
                $queryBuilder->expr()->eq('tt_content.deleted', 0),
                $queryBuilder->expr()->eq('tt_content.hidden', 0),
                $queryBuilder->expr()->eq('pages.deleted', 0),
                $queryBuilder->expr()->eq('pages.hidden', 0)
            )
            ->setMaxResults(10)
            ->execute();
        
        $results = [];
        while ($row = $statement->fetchAssociative()) {
            // Build URL for the parent page
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $conf = [
                'parameter' => $row['pid'],
                'returnLast' => 'url',
                'forceAbsoluteUrl' => 0
            ];
            
            $abstract = strip_tags($row['bodytext'] ?? '');
            $abstract = mb_substr($abstract, 0, 150) . (mb_strlen($abstract) > 150 ? '...' : '');
            
            $url = $cObj->typoLink_URL($conf);
            
            $results[] = [
                'title' => $row['header'] ?: $row['page_title'],
                'abstract' => $abstract ?: 'Keine Beschreibung verfügbar.',
                'url' => '/' . ltrim($url, '/'),
                'page' => $row['page_title']
            ];
        }
        
        return $results;
    }
    
    /**
     * Enhanced search with images, tags, categories
     */
    protected function searchInPagesEnhanced(string $query, string $sortOrder = 'relevance'): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');
        
        $searchTerms = '%' . $queryBuilder->escapeLikeWildcards($query) . '%';
        
        // Build query with enhanced fields
        $queryBuilder
            ->select(
                'pages.uid',
                'pages.title',
                'pages.abstract',
                'pages.keywords',
                'pages.description',
                'pages.media',
                'pages.categories',
                'pages.lastUpdated',
                'pages.crdate'
            )
            ->from('pages')
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->like('title', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('abstract', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('keywords', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('description', $queryBuilder->createNamedParameter($searchTerms))
                ),
                $queryBuilder->expr()->eq('deleted', 0),
                $queryBuilder->expr()->eq('hidden', 0)
            );
        
        // Apply sorting
        switch ($sortOrder) {
            case 'date_desc':
                $queryBuilder->orderBy('lastUpdated', 'DESC');
                break;
            case 'date_asc':
                $queryBuilder->orderBy('lastUpdated', 'ASC');
                break;
            case 'title':
                $queryBuilder->orderBy('title', 'ASC');
                break;
            default: // relevance - no specific order
                break;
        }
        
        $statement = $queryBuilder->execute();
        
        $results = [];
        while ($row = $statement->fetchAssociative()) {
            // Build URL for the page
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $conf = [
                'parameter' => $row['uid'],
                'returnLast' => 'url',
                'forceAbsoluteUrl' => 0
            ];
            $url = $cObj->typoLink_URL($conf);
            
            // Get first image if available
            $image = null;
            if ($row['media']) {
                // Get file reference
                $fileRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
                $fileReferences = $fileRepository->findByRelation('pages', 'media', $row['uid']);
                if (!empty($fileReferences)) {
                    $fileReference = $fileReferences[0];
                    $image = [
                        'url' => $fileReference->getPublicUrl(),
                        'alt' => $fileReference->getAlternative() ?: $row['title']
                    ];
                }
            }
            
            // Parse keywords as tags
            $tags = [];
            if ($row['keywords']) {
                $tags = array_map('trim', explode(',', $row['keywords']));
            }
            
            // Format date
            $date = null;
            if ($row['lastUpdated']) {
                $date = date('d.m.Y', $row['lastUpdated']);
            } elseif ($row['crdate']) {
                $date = date('d.m.Y', $row['crdate']);
            }
            
            $results[] = [
                'title' => $row['title'],
                'abstract' => $row['abstract'] ?: $row['description'] ?: 'Keine Beschreibung verfügbar.',
                'url' => '/' . ltrim($url, '/'),
                'image' => $image,
                'tags' => $tags,
                'date' => $date,
                'categories' => $this->getPageCategories($row['uid'])
            ];
        }
        
        // Also search in content elements with enhanced features
        $contentResults = $this->searchInContentEnhanced($query, $sortOrder);
        $results = array_merge($results, $contentResults);
        
        return $results;
    }
    
    /**
     * Enhanced search in content elements
     */
    protected function searchInContentEnhanced(string $query, string $sortOrder = 'relevance'): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');
        
        $searchTerms = '%' . $queryBuilder->escapeLikeWildcards($query) . '%';
        
        $queryBuilder
            ->select(
                'tt_content.uid',
                'tt_content.header',
                'tt_content.bodytext',
                'tt_content.pid',
                'tt_content.image',
                'tt_content.categories',
                'tt_content.crdate',
                'pages.title as page_title'
            )
            ->from('tt_content')
            ->leftJoin(
                'tt_content',
                'pages',
                'pages',
                $queryBuilder->expr()->eq('tt_content.pid', $queryBuilder->quoteIdentifier('pages.uid'))
            )
            ->where(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->like('tt_content.header', $queryBuilder->createNamedParameter($searchTerms)),
                    $queryBuilder->expr()->like('tt_content.bodytext', $queryBuilder->createNamedParameter($searchTerms))
                ),
                $queryBuilder->expr()->eq('tt_content.deleted', 0),
                $queryBuilder->expr()->eq('tt_content.hidden', 0),
                $queryBuilder->expr()->eq('pages.deleted', 0),
                $queryBuilder->expr()->eq('pages.hidden', 0)
            );
        
        // Apply sorting
        switch ($sortOrder) {
            case 'date_desc':
                $queryBuilder->orderBy('tt_content.crdate', 'DESC');
                break;
            case 'date_asc':
                $queryBuilder->orderBy('tt_content.crdate', 'ASC');
                break;
            case 'title':
                $queryBuilder->orderBy('tt_content.header', 'ASC');
                break;
        }
        
        $statement = $queryBuilder->setMaxResults(20)->execute();
        
        $results = [];
        while ($row = $statement->fetchAssociative()) {
            // Build URL for the parent page
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $conf = [
                'parameter' => $row['pid'],
                'returnLast' => 'url',
                'forceAbsoluteUrl' => 0
            ];
            $url = $cObj->typoLink_URL($conf);
            
            // Get first image if available
            $image = null;
            if ($row['image']) {
                $fileRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
                $fileReferences = $fileRepository->findByRelation('tt_content', 'image', $row['uid']);
                if (!empty($fileReferences)) {
                    $fileReference = $fileReferences[0];
                    $image = [
                        'url' => $fileReference->getPublicUrl(),
                        'alt' => $fileReference->getAlternative() ?: $row['header']
                    ];
                }
            }
            
            $abstract = strip_tags($row['bodytext'] ?? '');
            $abstract = mb_substr($abstract, 0, 150) . (mb_strlen($abstract) > 150 ? '...' : '');
            
            $results[] = [
                'title' => $row['header'] ?: $row['page_title'],
                'abstract' => $abstract ?: 'Keine Beschreibung verfügbar.',
                'url' => '/' . ltrim($url, '/'),
                'page' => $row['page_title'],
                'image' => $image,
                'tags' => [],
                'date' => $row['crdate'] ? date('d.m.Y', $row['crdate']) : null,
                'categories' => $this->getContentCategories($row['uid'])
            ];
        }
        
        return $results;
    }
    
    /**
     * Get categories for a page
     */
    protected function getPageCategories(int $pageUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        
        $statement = $queryBuilder
            ->select('sys_category.title')
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('mm.uid_local'))
            )
            ->where(
                $queryBuilder->expr()->eq('mm.uid_foreign', $pageUid),
                $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('pages'))
            )
            ->execute();
        
        $categories = [];
        while ($row = $statement->fetchAssociative()) {
            $categories[] = $row['title'];
        }
        
        return $categories;
    }
    
    /**
     * Get categories for content
     */
    protected function getContentCategories(int $contentUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        
        $statement = $queryBuilder
            ->select('sys_category.title')
            ->from('sys_category')
            ->join(
                'sys_category',
                'sys_category_record_mm',
                'mm',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('mm.uid_local'))
            )
            ->where(
                $queryBuilder->expr()->eq('mm.uid_foreign', $contentUid),
                $queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->createNamedParameter('tt_content'))
            )
            ->execute();
        
        $categories = [];
        while ($row = $statement->fetchAssociative()) {
            $categories[] = $row['title'];
        }
        
        return $categories;
    }
}
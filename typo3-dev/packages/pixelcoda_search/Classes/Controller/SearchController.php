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
     * Handle search and display results
     */
    public function searchAction(): ResponseInterface
    {
        $searchQuery = $this->request->getQueryParams()['q'] ?? '';
        $searchQuery = trim($searchQuery);
        
        $results = [];
        $message = '';
        
        if (strlen($searchQuery) < 3) {
            $message = 'Bitte geben Sie mindestens 3 Zeichen ein.';
        } else {
            // Search in pages
            $results = $this->searchInPages($searchQuery);
            
            if (empty($results)) {
                $message = 'Keine Ergebnisse für "' . htmlspecialchars($searchQuery) . '" gefunden.';
            } else {
                $message = count($results) . ' Ergebnis(se) für "' . htmlspecialchars($searchQuery) . '" gefunden.';
            }
        }
        
        $this->view->assignMultiple([
            'searchQuery' => htmlspecialchars($searchQuery),
            'results' => $results,
            'message' => $message
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
            
            $results[] = [
                'title' => $row['title'],
                'abstract' => $row['abstract'] ?: 'Keine Beschreibung verfügbar.',
                'url' => $cObj->typoLink('', $conf)
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
            
            $results[] = [
                'title' => $row['header'] ?: $row['page_title'],
                'abstract' => $abstract ?: 'Keine Beschreibung verfügbar.',
                'url' => $cObj->typoLink('', $conf),
                'page' => $row['page_title']
            ];
        }
        
        return $results;
    }
}
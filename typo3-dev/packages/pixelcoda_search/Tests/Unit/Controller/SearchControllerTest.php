<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PixelCoda\PixelcodaSearch\Controller\SearchController;
use ReflectionClass;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for SearchController.
 */
class SearchControllerTest extends UnitTestCase
{
    protected MockObject $subject;

    protected MockObject $viewMock;

    protected MockObject $requestMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getMockBuilder(SearchController::class)
            ->onlyMethods(['htmlResponse', 'createJsonResponse'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->viewMock = $this->createMock(ViewInterface::class);
        $this->requestMock = $this->createMock(ServerRequest::class);

        // Inject mocks
        $this->inject($this->subject, 'view', $this->viewMock);
        $this->inject($this->subject, 'request', $this->requestMock);
    }

    /**
     * @test
     */
    public function indexActionReturnsHtmlResponse(): void
    {
        $this->subject->expects($this->once())
            ->method('htmlResponse');

        $this->subject->indexAction();
    }

    /**
     * @test
     */
    public function suggestActionReturnsEmptyJsonForShortQuery(): void
    {
        $this->requestMock->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['q' => 'a']);

        $this->subject->expects($this->once())
            ->method('createJsonResponse')
            ->with([]);

        $this->subject->suggestAction();
    }

    /**
     * @test
     */
    public function suggestActionCallsGetSuggestionsForValidQuery(): void
    {
        $this->requestMock->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['q' => 'test']);

        $this->subject = $this->getMockBuilder(SearchController::class)
            ->onlyMethods(['createJsonResponse', 'getSuggestions'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->inject($this->subject, 'request', $this->requestMock);

        $expectedSuggestions = [
            ['title' => 'Test Page', 'url' => '/test', 'type' => 'page'],
        ];

        $this->subject->expects($this->once())
            ->method('getSuggestions')
            ->with('test')
            ->willReturn($expectedSuggestions);

        $this->subject->expects($this->once())
            ->method('createJsonResponse')
            ->with($expectedSuggestions);

        $this->subject->suggestAction();
    }

    /**
     * @test
     */
    public function searchActionHandlesPagination(): void
    {
        $this->requestMock->expects($this->once())
            ->method('getQueryParams')
            ->willReturn([
                'q' => 'search term',
                'page' => '2',
            ]);

        $this->viewMock->expects($this->once())
            ->method('assignMultiple')
            ->with($this->callback(static fn($data): bool => isset($data['searchQuery'])
                && isset($data['results'], $data['pagination'], $data['filters'])));

        $this->subject->searchAction();
    }

    /**
     * @test
     *
     * @dataProvider filterDataProvider
     */
    public function searchActionProcessesFiltersCorrectly(array $params, array $expectedFilters): void
    {
        $this->requestMock->expects($this->once())
            ->method('getQueryParams')
            ->willReturn($params);

        $this->viewMock->expects($this->once())
            ->method('assignMultiple')
            ->with($this->callback(static fn($data): bool => $data['filters'] === $expectedFilters));

        $this->subject->searchAction();
    }

    public function filterDataProvider(): array
    {
        return [
            'with category filter' => [
                ['q' => 'test', 'category' => '5'],
                [
                    'category' => '5',
                    'dateFrom' => '',
                    'dateTo' => '',
                    'contentType' => 'all',
                    'searchIn' => ['pages' => true, 'content' => true, 'news' => false],
                    'sort' => 'relevance',
                ],
            ],
            'with date range' => [
                ['q' => 'test', 'date_from' => '2024-01-01', 'date_to' => '2024-12-31'],
                [
                    'category' => '',
                    'dateFrom' => '2024-01-01',
                    'dateTo' => '2024-12-31',
                    'contentType' => 'all',
                    'searchIn' => ['pages' => true, 'content' => true, 'news' => false],
                    'sort' => 'relevance',
                ],
            ],
            'with sort order' => [
                ['q' => 'test', 'sort' => 'date_desc'],
                [
                    'category' => '',
                    'dateFrom' => '',
                    'dateTo' => '',
                    'contentType' => 'all',
                    'searchIn' => ['pages' => true, 'content' => true, 'news' => false],
                    'sort' => 'date_desc',
                ],
            ],
        ];
    }

    /**
     * Helper method to inject properties.
     *
     * @param mixed $object
     * @param mixed $value
     */
    protected function inject($object, string $property, $value): void
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setValue($object, $value);
    }
}

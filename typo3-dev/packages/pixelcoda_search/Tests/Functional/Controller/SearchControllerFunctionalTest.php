<?php

declare(strict_types=1);

namespace PixelCoda\PixelcodaSearch\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Functional tests for the classic frontend content element.
 */
final class SearchControllerFunctionalTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pixelcoda_search',
    ];

    protected array $coreExtensionsToLoad = [
        'fluid',
        'fluid_styled_content',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/pages.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tt_content.csv');
        $this->setUpFrontendRootPage(
            1,
            ['EXT:pixelcoda_search/Tests/Functional/Fixtures/TypoScript/setup.typoscript']
        );

        $siteConfiguration = [
            'rootPageId' => 1,
            'base' => 'https://example.test/',
            'languages' => [
                [
                    'title' => 'English',
                    'enabled' => true,
                    'languageId' => 0,
                    'base' => '/',
                    'locale' => 'en_US.UTF-8',
                    'navigationTitle' => 'English',
                    'flag' => 'us',
                ],
            ],
            'errorHandling' => [],
            'routes' => [],
        ];
        $sitePath = $this->instancePath . '/typo3conf/sites/test';
        GeneralUtility::mkdir_deep($sitePath);
        GeneralUtility::writeFile($sitePath . '/config.yaml', Yaml::dump($siteConfiguration, 99, 2), true);
    }

    #[Test]
    public function searchContentElementRendersOnRootPage(): void
    {
        $content = $this->renderRootPage();

        self::assertStringContainsString('pixelcoda-search-container', $content);
        self::assertStringContainsString('Website durchsuchen', $content);
        self::assertStringContainsString('Suchergebnisse', $content);
    }

    #[Test]
    public function searchContentElementExposesHeadlessApiConfiguration(): void
    {
        $content = $this->renderRootPage();

        self::assertStringContainsString('data-api-url="http://localhost:8787"', $content);
        self::assertStringContainsString('data-project="typo3"', $content);
        self::assertStringContainsString('data-collections="pages,tt_content"', $content);
    }

    #[Test]
    public function searchContentElementRendersAccessibleControls(): void
    {
        $content = $this->renderRootPage();

        self::assertStringContainsString('aria-label="Suchbegriff eingeben"', $content);
        self::assertStringContainsString('role="status"', $content);
        self::assertStringContainsString('aria-live="polite"', $content);
        self::assertStringContainsString('<label for="ai-input-', $content);
    }

    #[Test]
    public function searchContentElementLoadsFrontendAssets(): void
    {
        $content = $this->renderRootPage();

        self::assertStringContainsString('/typo3conf/ext/pixelcoda_search/Resources/Public/Css/search.min.css', $content);
        self::assertStringContainsString('/typo3conf/ext/pixelcoda_search/Resources/Public/JavaScript/search.min.js', $content);
    }

    private function renderRootPage(): string
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://example.test/')
        );

        self::assertSame(200, $response->getStatusCode());

        return (string) $response->getBody();
    }
}

# Development Guidelines

## Code Quality Standards

### PHP Code Style (TYPO3)
- **Type Declarations**: Strict types enabled with `declare(strict_types=1)` at file start (5/5 files)
- **Visibility**: All properties and methods have explicit visibility modifiers (public/protected/private)
- **Return Types**: Full return type declarations on all methods
- **Namespaces**: PSR-4 autoloading with vendor prefixes (PixelCoda, TYPO3)
- **Constructor Property Promotion**: PHP 8+ promoted constructor parameters used consistently
- **Final Classes**: Generated code and service classes often marked final

### JavaScript/TypeScript Code Style
- **Module System**: ES6 imports/exports, ESM with `import.meta.url` (3/5 files)
- **Async/Await**: Modern async patterns over callbacks
- **Const/Let**: Prefer `const` for immutable bindings, `let` for mutable
- **Arrow Functions**: Used extensively for callbacks and inline functions
- **Strict Mode**: `"use client"` directive for Next.js client components
- **Template Literals**: String interpolation with backticks

### Naming Conventions
- **PHP Classes**: PascalCase (e.g., `SearchModuleController`, `DependencyInjectionContainer`)
- **PHP Methods**: camelCase (e.g., `handleRequest`, `indexAction`, `addFlashMessage`)
- **PHP Constants**: SCREAMING_SNAKE_CASE (e.g., `MODULE_ROUTE`, `MAX_REQUEST_BYTES`)
- **JavaScript Functions**: camelCase (e.g., `checkAuth`, `searchDocuments`, `buildFacets`)
- **JavaScript Variables**: camelCase (e.g., `searchIndex`, `queryTokens`, `displayedResults`)
- **React Components**: PascalCase (e.g., `DevTools`, `PixelcodaSearchElement`, `Renderer`)

### File Organization
- **Separation of Concerns**: Controllers, services, utilities in dedicated files
- **Single Responsibility**: Each class/component handles one primary concern
- **Directory Structure**: Namespaced folders mirroring class namespaces (PHP), feature folders (React)

## Semantic Patterns

### Dependency Injection (TYPO3)
**Pattern**: Constructor injection with type-hinted dependencies
```php
public function __construct(
    protected ModuleTemplateFactory $moduleTemplateFactory,
    protected UriBuilder $backendUriBuilder,
    protected ConfigurationManager $configurationManager,
    protected RequestFactory $requestFactory,
) {}
```
**Frequency**: 2/2 PHP controllers use this pattern
**Usage**: TYPO3 core automatically resolves dependencies from DI container

### Action Methods Pattern (TYPO3 Backend)
**Pattern**: Protected methods ending with `Action` for route handlers
```php
protected function indexAction(ServerRequestInterface $request): ResponseInterface
protected function switchModeAction(ServerRequestInterface $request): ResponseInterface
protected function clearCacheAction(ServerRequestInterface $request): ResponseInterface
```
**Frequency**: Universal in backend controllers
**Returns**: Always `ResponseInterface`, typically `ModuleTemplate->renderResponse()` or `RedirectResponse`

### Flash Messages (TYPO3)
**Pattern**: User feedback via flash message service with severity levels
```php
$this->addFlashMessage(
    'Alle Caches wurden erfolgreich geleert.',
    'Caches geleert',
    ContextualFeedbackSeverity::OK
);
```
**Severity Levels**: OK, INFO, WARNING, ERROR
**Usage**: Shown to user after redirect, stored in session

### Module Template Factory (TYPO3 Backend)
**Pattern**: Generate backend module views with assigned variables
```php
$moduleTemplate = $this->moduleTemplateFactory->create($request);
$moduleTemplate->assignMultiple([...]);
$moduleTemplate->setTitle('pixelcoda Search - Administration');
return $moduleTemplate->renderResponse('Backend/Index');
```
**Frequency**: All backend views use this pattern

### Cache Management (TYPO3)
**Pattern**: System-wide cache clearing via `CacheManager`
```php
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->flushCaches();
```
**Additional**: Manual filesystem cleanup for `var/cache` and `typo3temp`

### Configuration Access (TYPO3)
**Pattern**: Global configuration arrays and service-based updates
```php
// Read
$config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'];

// Write
$this->configurationManager->setLocalConfigurationValueByPath(
    'EXTENSIONS/pixelcoda_search/default_mode',
    $mode
);
```
**File-Based Config**: YAML configs loaded/written via `YamlFileLoader` and `YamlFileWriter`

### JSON API Responses (Node.js)
**Pattern**: Consistent JSON:API format with CORS headers
```javascript
function jsonApiResponse(data, included = [], meta = {}) {
    return { data, included, meta, jsonapi: { version: '1.0' } };
}
```
**CORS**: `Access-Control-Allow-Origin` dynamically set based on `CORS_ALLOWED_ORIGINS` env var
**Frequency**: All API endpoints follow this structure

### Request Body Parsing (Node.js)
**Pattern**: Stream-based JSON parsing with size limits
```javascript
function getRequestBody(req) {
    return new Promise((resolve, reject) => {
        let body = '';
        req.on('data', chunk => {
            body += chunk;
            if (Buffer.byteLength(body) > MAX_REQUEST_BYTES) {
                reject(new Error('Request body exceeds 2 MB limit'));
                req.destroy();
            }
        });
        req.on('end', () => resolve(body ? JSON.parse(body) : {}));
    });
}
```
**Size Limit**: 2 MB by default to prevent memory exhaustion

### Full-Text Search Scoring (Node.js)
**Pattern**: Token-based matching with weighted title/content scores
```javascript
function scoreDocument(document, queryTokens) {
    const titleTokens = tokenize(document.title);
    const contentTokens = tokenize(`${document.summary} ${document.content} ${document.keywords}`);
    return queryTokens.reduce((score, token) => {
        const titleMatches = titleTokens.filter(word => word.includes(token)).length;
        const contentMatches = contentTokens.filter(word => word.includes(token)).length;
        return score + (titleMatches * 5) + contentMatches;
    }, 0);
}
```
**Weighting**: Title matches weighted 5x higher than content matches
**Tokenization**: Normalized, diacritic-stripped, lowercased word splitting

### React Server Components (Next.js 16)
**Pattern**: Async component functions with data fetching
```javascript
export default async function SuchePage({ searchParams }) {
    const resolvedSearchParams = await searchParams;
    const q = resolvedSearchParams?.q || '';
    
    const response = await fetch(url, {
        headers: { Accept: 'application/json' },
        cache: 'no-store',
    });
    
    const results = await response.json();
    return <main>...</main>;
}
```
**Metadata**: Separate `generateMetadata` function for SEO
**Caching**: `cache: 'no-store'` for search results

### Data Attributes for Editing (Next.js)
**Pattern**: Custom attributes for backend editing links
```javascript
<article 
    data-t3-uid={element.id} 
    data-t3-type={element.type}
    data-pixelcoda-uid={pcMeta.uid}
    data-pixelcoda-ctype={pcMeta.ctype}
    data-pixelcoda-edit-url={pcMeta.backendEditUrl}
>
```
**Purpose**: Frontend editing toolbar can detect and link to backend forms

### Inline Styles for Dynamic Content (React)
**Pattern**: Object-based inline styles for conditional rendering
```javascript
<a
    href={url}
    style={{
        padding: '0.6rem 1.2rem',
        background: isActive ? 'var(--green)' : '#fff',
        color: isActive ? '#fff' : 'var(--text)',
        border: '1px solid',
        borderColor: isActive ? 'var(--green)' : 'var(--border)',
    }}
>
```
**Usage**: Frequent for dynamic state-based styling (pagination, facets)

### Component Registry Pattern (React)
**Pattern**: Mapping object for type-to-component resolution
```javascript
export const rendererComponents = {
    pixelcodasearch_search: PixelcodaSearchElement,
    text: TextElement,
    textpic: TextElement,
    textmedia: TextElement,
};

function renderElement(element) {
    if (element.type === 'pixelcodasearch_search') {
        return <PixelcodaSearchElement element={element} />;
    }
    return <TextElement element={element} />;
}
```
**Fallback**: Generic `TextElement` for unknown types

### Environment-Based Configuration
**Pattern**: Environment variables with fallback defaults
```javascript
const PORT = process.env.SEARCH_API_PORT || 8787;
const API_READ_KEY = process.env.API_READ_KEY || (IS_DEVELOPMENT ? 'pc_read_dev_key' : '');
```
**TYPO3 PHP**:
```php
$configPath = Environment::getProjectPath() . '/config/sites/main/config.yaml';
if (!file_exists($configPath)) {
    $path = Environment::getPublicPath() . '/../config/sites/main/config.yaml';
}
```
**Validation**: Production requires all keys, development allows defaults

## Development Practices

### Error Handling
- **Try-Catch Blocks**: Used extensively around file I/O, API calls, config updates
- **Flash Messages**: User-facing errors shown via flash messages (TYPO3)
- **HTTP Status Codes**: Proper REST semantics (200 OK, 401 Unauthorized, 404 Not Found, 500 Error)
- **Error Boundary Pattern**: React components check for error markers (e.g., `isTypo3Error`)

### Security Practices
- **API Key Authentication**: Bearer token and `X-API-Key` header validation
- **Input Validation**: Type checks, whitelist validation for mode switches
- **SQL Injection Prevention**: Doctrine DBAL query builder (no raw SQL in examples)
- **XSS Protection**: `dangerouslySetInnerHTML` used sparingly, only for trusted TYPO3 content
- **CORS Configuration**: Controlled origin whitelisting

### Testing Indicators
- **Health Endpoints**: `/health` with service metadata and document counts
- **Connection Testing**: `testConnectionAction` for API validation
- **Demo Data**: `DEMO_DOCUMENTS` array for sandbox testing
- **Development Mode**: `IS_DEVELOPMENT` flag for relaxed authentication

### Performance Patterns
- **Lazy Loading**: `loading="lazy"` on images
- **Cache Control**: `cache: 'no-store'` for search, otherwise SSR caching
- **Pagination**: Server-side pagination with configurable `perPage`
- **Token Limits**: Max 2 MB request size, query token-based scoring
- **File Persistence**: Asynchronous file writes with `await persistIndex()`

### Logging & Debugging
- **Console Logging**: Request logs with timestamps and HTTP method/path
- **Flash Messages**: Status feedback in UI
- **DevTools Component**: Optional debug overlay with `NEXT_PUBLIC_HEADLESS_DEVTOOLS`
- **Error Boxes**: Styled error components with context messages

## Code Idioms

### Array Destructuring & Spread
```javascript
const { data, included, meta } = results;
const matches = searchDocuments(project, { ...body, q: question });
```

### Nullish Coalescing & Optional Chaining
```javascript
const q = resolvedSearchParams?.q || '';
const title = file?.properties?.alternative || file?.properties?.title || content.header || '';
```

### Match Expressions (PHP 8)
```php
return match ($action) {
    'switchMode' => $this->switchModeAction($request),
    'clearCache' => $this->clearCacheAction($request),
    default => $this->indexAction($request),
};
```

### Array Filtering & Mapping Chains (JavaScript)
```javascript
const ranked = getProjectDocuments(project)
    .filter(document => collections.length === 0 || collections.includes(document.collection))
    .filter(document => !category || (document.categories || []).some(item => item === category))
    .map(document => ({ document, score: scoreDocument(document, queryTokens) }))
    .filter(result => queryTokens.length === 0 || result.score > 0)
    .sort((a, b) => b.score - a.score);
```

### GeneralUtility::makeInstance (TYPO3)
```php
$flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity, true);
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
```
**Purpose**: TYPO3's factory pattern for DI-managed instances

### Ternary and Conditional Rendering (React)
```javascript
{content.header ? <h2>{content.header}</h2> : null}
{media.length ? <div className="content-media">...</div> : null}
```

## Annotations & Attributes

### PHP Attributes
- **TYPO3 Routing**: `@Route` annotations in route configuration (implied by `MODULE_ROUTE`)
- **Dependency Injection**: Constructor parameters automatically resolved

### JSDoc Patterns (PHP)
```php
/**
 * Main entry point for the backend module.
 */
public function handleRequest(ServerRequestInterface $request): ResponseInterface
```
**Style**: Single-line summary for public methods

### React Prop Types
- **Implicit Typing**: No PropTypes or TypeScript interfaces in examples (JS without types)
- **Destructuring**: Props destructured immediately for clarity

## Internal API Usage

### TYPO3 Core APIs
```php
// UriBuilder for module links
$this->backendUriBuilder->buildUriFromRoute(self::MODULE_ROUTE, ['action' => 'switchMode'])

// YamlFileLoader/Writer for config
$loader = GeneralUtility::makeInstance(YamlFileLoader::class);
$config = $loader->load($configPath);
$writer->write($configPath, $config);

// Environment path resolution
$path = Environment::getProjectPath() . '/config/sites/main/config.yaml';

// File operations
GeneralUtility::mkdir_deep($directory);
```

### Next.js/React APIs
```javascript
// Metadata generation
export async function generateMetadata({ searchParams }) {
    return { title: '...', description: '...' };
}

// Client component directive
"use client";

// Dynamic imports (implied by modular structure)
import DevTools from '../../components/DevTools';
import { siteConfig } from '../../lib/config';
```

### @pixelcoda/headless-nextjs
```javascript
import { T3Frame } from '@pixelcoda/headless-nextjs';

<T3Frame 
    id={`c${element.id}`} 
    frameClass={element.appearance?.frameClass} 
    layout={element.appearance?.layout}
    spaceBefore={element.appearance?.spaceBefore}
    spaceAfter={element.appearance?.spaceAfter}
>
```
**Purpose**: Wraps TYPO3 content with frame metadata (spacing, CSS classes)

### Node.js HTTP Server
```javascript
import { createServer } from 'http';
import { readFile, writeFile, mkdir } from 'fs/promises';
import { URL, fileURLToPath } from 'url';

const server = createServer(async (req, res) => {
    setCorsHeaders(req, res);
    res.setHeader('Content-Type', 'application/vnd.api+json');
    // ...
});
```
**Standard Library**: Minimal external dependencies, Node.js built-ins preferred

## Recommended Practices

1. **Always Use Type Declarations**: Strict mode PHP, full return types, JSDoc where needed
2. **Fail Fast**: Validate inputs early, throw exceptions with context
3. **Prefer Composition**: Small, focused functions/methods composed together
4. **Explicit Over Implicit**: Named parameters, clear variable names
5. **Environment Paths**: Use `Environment::getProjectPath()` over hardcoded paths
6. **Flash Messages for Feedback**: Never silent updates in backend modules
7. **CORS Headers**: Set them early, before any response content
8. **Cache Clearing**: After config changes, always flush caches programmatically
9. **Demo/Dev Data**: Include sample data for onboarding and testing
10. **Fallback Rendering**: Always provide default components for unknown types

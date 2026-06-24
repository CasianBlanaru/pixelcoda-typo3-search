# Development Guidelines

## Code Quality Standards

### PHP Code Formatting (TYPO3 Backend)
- **Strict Types**: Always declare `strict_types=1` at the beginning of PHP files
- **Type Declarations**: Use full type hints for parameters and return types (e.g., `string`, `array`, `ResponseInterface`)
- **Namespaces**: Follow PSR-4 autoloading with vendor namespace (e.g., `PixelCoda\PixelcodaSearch\Controller\Backend`)
- **Line Length**: Keep lines concise, generally under 120 characters
- **Indentation**: 4 spaces for PHP, consistent throughout
- **Spacing**: Single space after control structures, no space before opening parentheses in function calls

### JavaScript/Node.js Formatting
- **ESM Modules**: Use modern `import/export` syntax with `.js` extensions
- **Strict Equality**: Always use `===` and `!==` for comparisons
- **String Formatting**: Use template literals for string interpolation
- **Arrow Functions**: Prefer arrow functions for inline callbacks and short functions
- **Semicolons**: Always terminate statements with semicolons
- **Indentation**: 4 spaces consistently (matches project convention)

### React/JSX Formatting (Next.js Frontend)
- **Async Components**: Server components can be async with `async function`
- **Prop Handling**: Await `searchParams` before destructuring in Next.js 15+
- **Inline Styles**: Use style objects for component-specific styling
- **Conditional Rendering**: Use ternary operators and logical AND for conditional JSX
- **Component Structure**: Default export for page components
- **Fragment Usage**: Implicit fragments or semantic HTML over unnecessary divs

### Naming Conventions
- **PHP Classes**: PascalCase (e.g., `SearchModuleController`)
- **PHP Methods**: camelCase (e.g., `handleRequest`, `indexAction`)
- **PHP Constants**: SCREAMING_SNAKE_CASE (e.g., `MODULE_ROUTE`, `MAX_REQUEST_BYTES`)
- **JavaScript Functions**: camelCase (e.g., `checkAuth`, `tokenize`, `getRequestBody`)
- **JavaScript Constants**: camelCase for variables, SCREAMING_SNAKE_CASE for true constants
- **React Components**: PascalCase for component files and function names
- **CSS Classes**: kebab-case (e.g., `pixelcoda-search-form`, `content-shell`)

### Documentation Standards
- **PHP DocBlocks**: Use for classes and public methods; include description and parameter/return types
- **Inline Comments**: Sparingly used; code should be self-documenting via clear naming
- **API Routes**: Document expected request/response formats inline
- **Configuration**: Document environment variables and their defaults

## Semantic Patterns

### Dependency Injection (PHP)
Use constructor property promotion with typed dependencies:
```php
public function __construct(
    protected ModuleTemplateFactory $moduleTemplateFactory,
    protected UriBuilder $backendUriBuilder,
    protected ConfigurationManager $configurationManager,
    protected RequestFactory $requestFactory,
) {}
```

### Action Pattern (TYPO3 Controllers)
- Methods follow `{action}Action()` naming convention
- Actions return `ResponseInterface`
- Use match expressions for action routing
- Separate concerns: one action per distinct operation

### Service Locator Pattern
Extensive use of `GeneralUtility::makeInstance()` for service instantiation:
```php
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $message, $title, $severity, true);
```

### Configuration Management
- Use `ConfigurationManager` for LocalConfiguration updates
- Use `YamlFileLoader` and `YamlFileWriter` for site configuration
- Environment-aware paths with `Environment::getProjectPath()` and `Environment::getPublicPath()`

### Flash Messages
Consistent flash message pattern for user feedback:
```php
$this->addFlashMessage(
    'Message text',
    'Title',
    ContextualFeedbackSeverity::OK|ERROR|WARNING|INFO
);
```

### RESTful API Design (Node.js)
- JSON:API compliant responses with `data`, `included`, `meta` structure
- HTTP method-based routing: GET (read), POST (create/search), DELETE (remove)
- Path-based versioning: `/v1/search/{project}`
- Token-based authentication via Authorization header
- CORS headers set for all responses

### Authentication Pattern
```javascript
function checkAuth(req, requiredKey) {
    const authHeader = req.headers.authorization || req.headers['x-api-key'] || '';
    const providedKey = authHeader.replace('Bearer ', '').replace('ApiKey ', '');
    return acceptedKeys.includes(providedKey) || IS_DEVELOPMENT;
}
```

### Search Algorithm
Full-text search using tokenization:
- Normalize text with `toLocaleLowerCase()`, `normalize('NFKD')`, remove diacritics
- Split on non-alphanumeric characters
- Score: title matches × 5 + content matches
- Support sorting by relevance, date, or title
- Faceted filtering by collection, category, content type

### Next.js Data Fetching
Server-side fetch with no caching:
```javascript
const response = await fetch(url, {
    headers: { Accept: 'application/json' },
    cache: 'no-store',
});
```

### Error Handling
- Try-catch blocks for external calls (API requests, file I/O)
- Flash messages for user-facing errors
- JSON error responses with structured messages for APIs
- Graceful fallbacks (empty arrays, null values)

## Internal API Usage

### TYPO3 Backend Template API
```php
$moduleTemplate = $this->moduleTemplateFactory->create($request);
$moduleTemplate->assignMultiple([...]);
$moduleTemplate->setTitle('Module Title');
return $moduleTemplate->renderResponse('Backend/Index');
```

### TYPO3 Cache Management
```php
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->flushCaches(); // Clear all caches
```

### TYPO3 Routing
```php
$this->backendUriBuilder->buildUriFromRoute('route_identifier', ['param' => 'value']);
return new RedirectResponse((string) $uri);
```

### Environment Paths
```php
Environment::getProjectPath() // Project root
Environment::getPublicPath()  // Public web directory
Environment::getVarPath()     // var/ directory for runtime data
```

### Node.js HTTP Server
```javascript
const server = createServer(async (req, res) => {
    setCorsHeaders(req, res);
    const url = new URL(req.url, `http://localhost:${PORT}`);
    const path = url.pathname;
    // Route handling
});
server.listen(PORT, HOST, callback);
```

### File System Operations (Node.js)
```javascript
await mkdir(DATA_DIR, { recursive: true });
await writeFile(path, JSON.stringify(data, null, 2));
const data = JSON.parse(await readFile(path, 'utf8'));
```

### Next.js Metadata Generation
```javascript
export async function generateMetadata({ searchParams }) {
    const resolvedSearchParams = await searchParams;
    return {
        title: 'Page Title',
        description: 'Page description',
    };
}
```

## Code Idioms

### Match Expressions (PHP 8+)
```php
return match ($action) {
    'switchMode' => $this->switchModeAction($request),
    'clearCache' => $this->clearCacheAction($request),
    default => $this->indexAction($request),
};
```

### Null Coalescing and Nullsafe Operators
```php
$action = $request->getQueryParams()['action'] ?? 'index';
$config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'] ?? [];
```

### Array Destructuring and Spread
```javascript
const { q, collections, page, perPage } = payload;
const queryTokens = tokenize(query);
```

### Logical OR Assignment
```javascript
searchIndex.typo3 ||= {};
searchIndex.typo3[collection] ||= {};
```

### Template Literals for URLs
```javascript
const url = `${typo3Origin}/?type=1701&q=${encodeURIComponent(q)}`;
```

### Array Methods Chaining
```javascript
const results = documents
    .filter(doc => collections.includes(doc.collection))
    .map(doc => ({ doc, score: scoreDocument(doc, tokens) }))
    .filter(result => result.score > 0)
    .sort((a, b) => b.score - a.score);
```

### Early Returns for Guards
```php
if (!file_exists($configPath)) {
    return [];
}
```

### Ternary Operators for Conditional Values
```javascript
const mode = newMode === 'headless' ? 'headless' : 'classic';
```

## Annotations and Attributes

### PHP Attributes (PHP 8+)
While not present in the analyzed files, TYPO3 v13+ supports route and controller attributes. For compatibility, declarative routes are configured separately.

### JSDoc (Minimal Usage)
No JSDoc annotations in JavaScript files; types inferred from usage or documented in README/API docs.

## Project-Specific Patterns

### TYPO3 Extension Structure
- Controllers in `Classes/Controller/Backend/`
- Templates in `Resources/Private/Templates/Backend/`
- Configuration in `ext_localconf.php`, `ext_tables.php`
- Dependency injection via constructor

### Dual-Mode Architecture
- **Standard Mode**: TYPO3 renders HTML via Fluid templates
- **Headless Mode**: TYPO3 returns JSON via headless extension
- Mode switching affects site configuration (`renderingMode`) and plugin configuration (`default_mode`)

### Search API Persistence
- In-memory index stored as JSON file (`.data/search-index.json`)
- Demo documents auto-seeded on startup
- Write operations immediately persist to disk

### Next.js Page Structure
- Pages in `src/app/{route}/page.jsx`
- Server-side rendering by default
- Inline styles for rapid prototyping (production would use CSS modules or Tailwind)

### Configuration Layers
1. **Environment Variables**: API keys, URLs, ports
2. **TYPO3 LocalConfiguration**: Extension settings via ConfigurationManager
3. **Site Configuration YAML**: Site-level settings (rendering mode, languages)
4. **Next.js .env.local**: Frontend URLs and feature flags

### Stateless Search API
- No user sessions; authentication via API keys
- Request-scoped operations
- No persistent connections or WebSockets

### Cache Strategy
- TYPO3: Full cache flush after mode switches
- Next.js: `cache: 'no-store'` for search results (always fresh)
- Search API: No caching; in-memory index for speed

## Quality Practices

- **Minimal Dependencies**: Rely on standard library and framework APIs
- **Fail Fast**: Validate inputs early; throw exceptions for invalid states
- **Separation of Concerns**: Controllers orchestrate; services encapsulate logic
- **Single Responsibility**: Each method/function does one thing well
- **No Magic Numbers**: Use named constants for configuration values
- **Defensive Programming**: Check file existence before I/O; validate API responses
- **Logging**: Console.log for API requests; TYPO3 logging for backend errors
- **Security**: API key authentication; CORS headers; input sanitization for search queries

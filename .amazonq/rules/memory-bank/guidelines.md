# Development Guidelines

## Code Quality Standards

### PHP Code Formatting (TYPO3 Backend)
- **Strict Types**: Always declare `strict_types=1` at the beginning of PHP files
- **Type Declarations**: Use full type hints for parameters and return types (e.g., `string`, `array`, `ResponseInterface`)
- **Namespaces**: Follow PSR-4 autoloading with vendor namespace (e.g., `PixelCoda\PixelcodaSearch\Controller\Backend`)
- **Indentation**: 4 spaces for PHP, consistent throughout
- **Spacing**: Single space after control structures, no space before opening parentheses in function calls

### JavaScript/Node.js Formatting
- **ESM Modules**: Use modern `import/export` syntax with `.js` extensions
- **Strict Equality**: Always use `===` and `!==` for comparisons
- **String Formatting**: Use template literals for string interpolation
- **Arrow Functions**: Prefer arrow functions for inline callbacks and short functions
- **Semicolons**: Always terminate statements with semicolons
- **Indentation**: 4 spaces consistently

### React/JSX Formatting (Next.js Frontend)
- **Async Components**: Server components can be async with `async function`
- **Prop Handling**: Await `searchParams` before destructuring in Next.js 15+
- **Inline Styles**: Use style objects for component-specific styling
- **Conditional Rendering**: Use ternary operators and logical AND for conditional JSX
- **Component Structure**: Default export for page components

### Naming Conventions
- **PHP Classes**: PascalCase (e.g., `SearchModuleController`)
- **PHP Methods**: camelCase (e.g., `handleRequest`, `indexAction`)
- **PHP Constants**: SCREAMING_SNAKE_CASE (e.g., `MODULE_ROUTE`, `MAX_REQUEST_BYTES`)
- **JavaScript Functions**: camelCase (e.g., `checkAuth`, `tokenize`, `getRequestBody`)
- **React Components**: PascalCase for component files and function names
- **CSS Classes**: kebab-case (e.g., `pixelcoda-search-form`, `content-shell`)

## Semantic Patterns

### Dependency Injection (PHP)
Use constructor property promotion with typed dependencies:
```php
public function __construct(
    protected ModuleTemplateFactory $moduleTemplateFactory,
    protected UriBuilder $backendUriBuilder,
) {}
```

### Action Pattern (TYPO3 Controllers)
- Methods follow `{action}Action()` naming convention
- Actions return `ResponseInterface`
- Use match expressions for action routing

### Service Locator Pattern
Use `GeneralUtility::makeInstance()` for service instantiation:
```php
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
```

### Configuration Management
- Use `ConfigurationManager` for LocalConfiguration updates
- Use `YamlFileLoader` and `YamlFileWriter` for site configuration
- Environment-aware paths with `Environment::getProjectPath()` and `Environment::getPublicPath()`

### RESTful API Design (Node.js)
- JSON:API compliant responses with `data`, `included`, `meta` structure
- HTTP method-based routing: GET (read), POST (create/search), DELETE (remove)
- Path-based versioning: `/v1/search/{project}`
- Token-based authentication via Authorization header

### Search Algorithm
Full-text search using tokenization:
- Normalize text with `toLocaleLowerCase()`, `normalize('NFKD')`, remove diacritics
- Split on non-alphanumeric characters
- Score: title matches × 5 + content matches
- Faceted filtering by collection, category, content type

### Next.js Data Fetching
Server-side fetch with no caching:
```javascript
const response = await fetch(url, {
    headers: { Accept: 'application/json' },
    cache: 'no-store',
});
```

## Internal API Usage

### TYPO3 Backend Template API
```php
$moduleTemplate = $this->moduleTemplateFactory->create($request);
$moduleTemplate->assignMultiple([...]);
return $moduleTemplate->renderResponse('Backend/Index');
```

### TYPO3 Cache Management
```php
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->flushCaches();
```

### Node.js HTTP Server
```javascript
const server = createServer(async (req, res) => {
    const url = new URL(req.url, `http://localhost:${PORT}`);
    // Route handling
});
```

## Code Idioms

### Match Expressions (PHP 8+)
```php
return match ($action) {
    'switchMode' => $this->switchModeAction($request),
    default => $this->indexAction($request),
};
```

### Null Coalescing
```php
$action = $request->getQueryParams()['action'] ?? 'index';
```

### Logical OR Assignment
```javascript
searchIndex.typo3 ||= {};
```

### Array Methods Chaining
```javascript
const results = documents
    .filter(doc => collections.includes(doc.collection))
    .map(doc => ({ doc, score: scoreDocument(doc, tokens) }))
    .sort((a, b) => b.score - a.score);
```

## Quality Practices

- **Minimal Dependencies**: Rely on standard library and framework APIs
- **Fail Fast**: Validate inputs early; throw exceptions for invalid states
- **Separation of Concerns**: Controllers orchestrate; services encapsulate logic
- **Security**: API key authentication; CORS headers; input sanitization

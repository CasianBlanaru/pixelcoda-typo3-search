# Development Guidelines

## Code Quality Standards

### PHP Code Style (TYPO3 Extensions)
- **Strict Types**: Declare `strict_types=1` at the beginning of every PHP file
- **Type Declarations**: Use explicit type hints for all method parameters and return types
- **Namespace Convention**: Follow PSR-4 autoloading with vendor namespace `PixelCoda` or `Pixelcoda`
- **Class Structure**: One class per file, file name matches class name
- **Visibility Keywords**: Always declare visibility (public, protected, private) for properties and methods
- **Constructor Property Promotion**: Use PHP 8.0+ constructor property promotion for dependency injection

### JavaScript/TypeScript Style
- **ES Modules**: Use ESM imports (`import`/`export`) instead of CommonJS
- **Async/Await**: Prefer async/await over Promise chains for readability
- **Destructuring**: Use destructuring for cleaner code (`const { data } = results`)
- **Arrow Functions**: Use arrow functions for callbacks and functional operations
- **Template Literals**: Use template literals for string interpolation
- **Const/Let**: Never use `var`, prefer `const` over `let` when possible

### File Organization
- PHP files use `.php` extension with class-based organization
- React components use `.jsx` extension
- Configuration files use `.js` (Next.js, Node.js) or `.yaml` (TYPO3)
- Keep generated files separate (`.next/`, `var/cache/`)

## Naming Conventions

### PHP (TYPO3)
- **Classes**: PascalCase (e.g., `SearchModuleController`, `GsapAnimatedContent`)
- **Methods**: camelCase (e.g., `handleRequest`, `getCurrentConfiguration`)
- **Properties**: camelCase (e.g., `moduleTemplateFactory`, `configurationManager`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MODULE_ROUTE`, `MAX_REQUEST_BYTES`)
- **Database Tables**: snake_case (e.g., `tt_content`, `pages`)

### JavaScript/React
- **Components**: PascalCase (e.g., `GsapAnimatedContent`, `DevTools`)
- **Functions**: camelCase (e.g., `generateMetadata`, `loadAndAnimate`)
- **Variables**: camelCase (e.g., `searchIndex`, `queryTokens`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `PORT`, `API_READ_KEY`)
- **Props**: camelCase (e.g., `animationSettings`, `searchParams`)

## Architectural Patterns

### Dependency Injection (TYPO3)
- Use constructor injection for all service dependencies
- Rely on TYPO3's DI container for service instantiation
- Do not use `GeneralUtility::makeInstance()` for services with DI support
- Protected properties for injected dependencies

```php
public function __construct(
    protected ModuleTemplateFactory $moduleTemplateFactory,
    protected UriBuilder $backendUriBuilder,
) {}
```

### Component-Based Architecture (React)
- Server Components by default in Next.js App Router
- Use `"use client"` directive only when needed (hooks, event handlers)
- Extract reusable UI into separate components
- Keep page components focused on data fetching and layout

### Configuration Management
- TYPO3 configuration in YAML files (`config.yaml`)
- Environment-specific settings via `.env.local` files
- Never hardcode API keys or secrets
- Use environment variables for configuration (`process.env`, `Environment::getProjectPath()`)

## Common Implementation Patterns

### Error Handling (PHP)
```php
try {
    // Operation that might fail
    $this->updateSiteConfiguration($newMode);
} catch (Exception $exception) {
    $this->addFlashMessage(
        'Error message: ' . $exception->getMessage(),
        'Error Title',
        ContextualFeedbackSeverity::ERROR
    );
}
```

### Async Data Fetching (Next.js)
```javascript
// Server-side data fetching in async page components
const response = await fetch(url, {
    headers: { Accept: 'application/json' },
    cache: 'no-store',
});

if (!response.ok) {
    throw new Error(`API returned status ${response.status}`);
}

const results = await response.json();
```

### GSAP Animation Pattern
```javascript
// Dynamic import for client-side only libraries
const gsapModule = await import('gsap');
const ScrollTrigger = scrollTriggerModule.default || scrollTriggerModule;
gsap.registerPlugin(ScrollTrigger);

// Cleanup pattern for React effects
const cleanup = () => {
    if (anim) anim.kill();
    ScrollTrigger.getAll().forEach(st => st.kill());
};
return () => cleanup();
```

### TYPO3 Module Template Pattern
```php
$moduleTemplate = $this->moduleTemplateFactory->create($request);
$moduleTemplate->assignMultiple([
    'config' => $config,
    'systemStatus' => $systemStatus,
    // More template variables
]);
$moduleTemplate->setTitle('Module Title');
return $moduleTemplate->renderResponse('Backend/Index');
```

## API Design Patterns

### JSON:API Compliance
- Use JSON:API structure for all API responses
- Include `data`, `included`, `meta`, and `jsonapi` fields
- Proper resource types and IDs in response objects
- Relationship objects for linked data

```javascript
function jsonApiResponse(data, included = [], meta = {}) {
    return { 
        data, 
        included, 
        meta, 
        jsonapi: { version: '1.0' } 
    };
}
```

### RESTful Routing
- `GET /v1/search/:project` - Search documents
- `POST /v1/index/:project/:collection` - Index documents
- `DELETE /v1/index/:project/:collection` - Delete documents
- `GET /health` - Health check endpoint

### Authentication Patterns
- Bearer token authentication via `Authorization` header
- API key support via `X-API-Key` header
- Development mode bypass for easier local testing
- Separate read and write API keys

```javascript
function checkAuth(req, requiredKey) {
    const authHeader = req.headers.authorization || req.headers['x-api-key'] || '';
    const providedKey = authHeader.replace('Bearer ', '').replace('ApiKey ', '');
    return acceptedKeys.includes(providedKey) || IS_DEVELOPMENT;
}
```

## Frequently Used Code Idioms

### Null Coalescing and Optional Chaining
```javascript
// JavaScript
const q = searchParams?.q || '';
const results = searchIndex?.[project]?.[collection] || {};

// PHP
$mode = $config['default_mode'] ?? 'classic';
$apiUrl = $this->getCurrentConfiguration()['api_url'] ?? '';
```

### Array Filtering and Mapping
```javascript
// Filter and map pattern
const displayedResults = (results?.data || [])
    .filter((item) => item.attributes?.type === 'page')
    .map((result) => ({ ...result.attributes }));
```

### TYPO3 Flash Messages
```php
$this->addFlashMessage(
    'Success message text',
    'Success Title',
    ContextualFeedbackSeverity::OK
);
```

### Next.js Metadata Generation
```javascript
export async function generateMetadata({ searchParams }) {
    const resolvedSearchParams = await searchParams;
    const q = resolvedSearchParams?.q || '';
    return {
        title: q ? `Results for "${q}"` : 'Search',
        description: 'Search page description',
    };
}
```

## Testing Conventions

### URL Construction
- Always use `rtrim()` or `.replace(/\\/$/, '')` to normalize base URLs
- Use `encodeURIComponent()` for query parameters
- Build URLs with proper path joining

### Cache Management
- Clear all TYPO3 caches after configuration changes
- Ensure runtime directories exist before operations
- Use CacheManager for TYPO3 cache operations

### State Management
- React: Use `useRef` for DOM references and persistent values
- React: Use `useEffect` for side effects and lifecycle operations
- Server state lives in search params for pagination/filters

## Performance Best Practices

### Next.js
- Use `cache: 'no-store'` for dynamic search results
- Server-side rendering by default in App Router
- Client components only for interactive features
- Dynamic imports for heavy libraries (GSAP)

### Search Optimization
- Tokenize and normalize search queries (lowercase, remove diacritics)
- Score documents by title (5x weight) and content (1x weight)
- Limit results with pagination (default 10, max 100 per page)
- Build facets from filtered results for accurate counts

### File Operations
- Use async file operations (`fs/promises`)
- Recursive directory creation with `recursive: true`
- Proper cleanup in recursive directory removal

## Security Practices

### Input Validation
- Validate and sanitize all user inputs
- Type-check API payloads before processing
- Limit request body size (2 MB in search API)
- Use prepared statements/query builders for database access

### API Security
- Require API keys in production environments
- Separate read and write permissions
- CORS configuration via environment variables
- Proper error messages without exposing internals

### TYPO3 Security
- Use TYPO3's authentication services
- Respect backend user permissions
- Validate file paths before operations
- Use TYPO3's security aspects and voters

## Documentation Standards

### Inline Comments
- PHP: Use PHPDoc blocks for classes and public methods
- Avoid obvious comments; comment the "why", not the "what"
- Document complex algorithms and business logic
- Keep comments up to date with code changes

### README Files
- Each major component has its own README
- Include installation, configuration, and usage sections
- Provide example configurations and code snippets
- Document environment variables and dependencies

## Common Annotations

### PHP Attributes
```php
#[AsController]
#[AsEventListener]
declare(strict_types=1);
```

### Next.js Metadata
```javascript
export const metadata = { title: 'Page Title' };
export async function generateMetadata() { /* ... */ }
```

### React Directives
```javascript
"use client"; // For client components
"use server"; // For server actions
```

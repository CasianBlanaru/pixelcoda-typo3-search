# Development Guidelines

## Code Quality Standards

### PHP Code Standards
- **PHP Version**: Strict PHP 8.2+ with typed declarations (`declare(strict_types=1)`)
- **Namespacing**: PSR-4 autoloading with vendor namespace prefix (e.g., `PixelCoda\PixelcodaSearch\Controller\Backend`)
- **Type Declarations**: Explicit return types and parameter types on all methods
- **Visibility**: Always specify method visibility (`public`, `protected`, `private`)
- **Properties**: Use promoted constructor properties with visibility and type hints
- **Constants**: Use class constants with `private const` for internal values

### JavaScript/Node.js Standards
- **Module System**: ES6 modules with `import/export` syntax
- **Environment**: Node.js >=18.0.0 for backend APIs
- **File Organization**: Top-level configuration constants at file start
- **Encoding**: UTF-8 with proper text normalization (NFKD) for internationalization
- **Async/Await**: Preferred over callbacks or raw promises

### React/Next.js Standards
- **Component Structure**: Server Components by default in App Router
- **Async Components**: Server components marked with `async` keyword
- **Props**: Destructured with await for searchParams
- **Styling**: Inline styles with CSS custom properties (variables)
- **Imports**: Relative imports for local modules, absolute for external packages

## Structural Conventions

### TYPO3 Extension Structure
```
extension_name/
├── Classes/
│   ├── Controller/
│   │   └── Backend/         # Backend module controllers
│   ├── Command/             # CLI commands
│   ├── DataProcessing/      # Fluid data processors
│   ├── Service/             # Business logic services
│   └── ViewHelpers/         # Fluid ViewHelpers
├── Configuration/
│   ├── TCA/                 # Table Configuration Array
│   ├── TypoScript/          # TypoScript templates
│   └── Backend/             # Backend module configs
├── Resources/
│   ├── Public/              # Public assets
│   └── Private/             # Templates and partials
└── ext_*.php                # Extension configuration files
```

### Next.js Application Structure
```
src/
├── app/                     # App Router pages
│   └── [route]/
│       └── page.jsx         # Route component
├── components/              # Reusable React components
└── lib/                     # Utilities and configurations
```

## Naming Conventions

### PHP Naming
- **Classes**: PascalCase (e.g., `SearchModuleController`)
- **Methods**: camelCase (e.g., `handleRequest`, `getCurrentConfiguration`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MODULE_ROUTE`, `MAX_REQUEST_BYTES`)
- **Variables**: camelCase (e.g., `$configPath`, `$apiUrl`)
- **Protected Methods**: camelCase with `protected` visibility, often action suffixes (e.g., `indexAction`, `switchModeAction`)

### JavaScript/Node.js Naming
- **Variables**: camelCase (e.g., `searchIndex`, `queryTokens`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `PORT`, `API_READ_KEY`, `DATA_DIR`)
- **Functions**: camelCase (e.g., `tokenize`, `searchDocuments`, `buildFacets`)
- **Array Variables**: Plural nouns (e.g., `documents`, `results`, `collections`)

### React Component Naming
- **Components**: PascalCase (e.g., `SuchePage`, `DevTools`)
- **Props**: camelCase (e.g., `searchParams`)
- **Event Handlers**: Prefixed with `handle` or `on` (e.g., `handleSubmit`)

## Documentation Standards

### PHP Documentation
- **Class DocBlock**: Description of class purpose
- **Method DocBlock**: Brief description without repetitive parameter/return tags when types are explicit
- **Inline Comments**: German language used in flash messages and user-facing strings

### Code Comments
- Minimal comments - prefer self-documenting code
- Comments used primarily for complex algorithms or business logic explanations
- User-facing messages in German (this codebase)

## Semantic Patterns

### Dependency Injection (TYPO3)
Constructor injection pattern with promoted properties:
```php
public function __construct(
    protected ModuleTemplateFactory $moduleTemplateFactory,
    protected UriBuilder $backendUriBuilder,
) {}
```

### Configuration Management
- Environment variables with fallback defaults
- YAML configuration files for structured data
- Global `$GLOBALS['TYPO3_CONF_VARS']` access pattern in TYPO3
- Extension configuration: `$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['extension_key']`

### Error Handling
**PHP:**
```php
try {
    // operation
    $this->addFlashMessage('Success', 'Title', ContextualFeedbackSeverity::OK);
} catch (Exception $exception) {
    $this->addFlashMessage('Error: ' . $exception->getMessage(), 'Error', ContextualFeedbackSeverity::ERROR);
}
```

**JavaScript:**
```javascript
try {
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error(`API returned ${response.status}`);
    }
} catch (e) {
    error = e.message;
}
```

### HTTP Request Handling

**TYPO3 Backend Controllers:**
```php
public function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    $action = $request->getQueryParams()['action'] ?? 'index';
    return match ($action) {
        'switchMode' => $this->switchModeAction($request),
        default => $this->indexAction($request),
    };
}
```

**Node.js API Server:**
```javascript
const server = createServer(async (req, res) => {
    setCorsHeaders(req, res);
    const url = new URL(req.url, `http://localhost:${PORT}`);
    
    if (path === '/health' && method === 'GET') {
        sendJson(res, 200, { ok: true });
    }
});
```

### Data Processing Patterns

**Tokenization and Search:**
```javascript
function tokenize(value) {
    return String(value || '')
        .toLocaleLowerCase()
        .normalize('NFKD')
        .replace(/\p{Diacritic}/gu, '')
        .split(/[^\p{Letter}\p{Number}]+/u)
        .filter(Boolean);
}

function scoreDocument(document, queryTokens) {
    const titleMatches = titleTokens.filter(word => word.includes(token)).length;
    return score + (titleMatches * 5) + contentMatches;
}
```

### Response Formatting

**JSON:API Standard:**
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

**TYPO3 TCA Array Structure:**
```php
'ctrl' => [
    'label' => 'username',
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'delete' => 'deleted',
    'enablecolumns' => [
        'disabled' => 'disable',
        'starttime' => 'starttime',
        'endtime' => 'endtime',
    ],
],
```

### Authentication Patterns

**API Key Authentication:**
```javascript
function checkAuth(req, requiredKey) {
    const authHeader = req.headers.authorization || req.headers['x-api-key'] || '';
    const providedKey = authHeader.replace('Bearer ', '').replace('ApiKey ', '');
    return acceptedKeys.includes(providedKey) || IS_DEVELOPMENT;
}
```

### CORS Configuration
```javascript
function setCorsHeaders(req, res) {
    const configuredOrigins = process.env.CORS_ALLOWED_ORIGINS.split(',');
    if (configuredOrigins.includes('*')) {
        res.setHeader('Access-Control-Allow-Origin', '*');
    }
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, DELETE, OPTIONS');
}
```

## Common Idioms

### Safe Navigation / Null Coalescing
**PHP:**
```php
$value = $request->getQueryParams()['action'] ?? 'index';
$config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['key'] ?? [];
```

**JavaScript:**
```javascript
const config = searchIndex[project]?.[collection]?.[id];
const title = document.title || 'Default Title';
```

### Pagination Pattern
```javascript
const page = Math.max(1, Number(payload.page) || 1);
const perPage = Math.max(1, Math.min(Number(payload.per_page) || 10, 100));
const offset = (Math.min(page, pages) - 1) * perPage;
const results = ranked.slice(offset, offset + perPage);
```

### Array Filtering and Mapping
```javascript
const ranked = documents
    .filter(doc => collections.length === 0 || collections.includes(doc.collection))
    .map(doc => ({ document: doc, score: scoreDocument(doc, queryTokens) }))
    .filter(result => result.score > 0)
    .sort((a, b) => b.score - a.score);
```

### React Server Component Data Fetching
```jsx
export default async function Page({ searchParams }) {
    const resolvedParams = await searchParams;
    const q = resolvedParams?.q || '';
    
    const response = await fetch(url, { 
        cache: 'no-store',
        headers: { Accept: 'application/json' }
    });
    
    const results = await response.json();
    return <main>{/* render */}</main>;
}
```

## Frequently Used Patterns

### GeneralUtility Static Methods (TYPO3)
```php
GeneralUtility::makeInstance(ClassName::class)
GeneralUtility::mkdir_deep($directory)
```

### Environment Path Resolution (TYPO3)
```php
Environment::getProjectPath()
Environment::getPublicPath()
Environment::getVarPath()
```

### Flash Message System (TYPO3)
```php
$flashMessage = GeneralUtility::makeInstance(
    FlashMessage::class, 
    $message, 
    $title, 
    ContextualFeedbackSeverity::OK
);
$messageQueue->addMessage($flashMessage);
```

### File Operations (Node.js)
```javascript
import { readFile, writeFile, mkdir } from 'fs/promises';

await mkdir(DATA_DIR, { recursive: true });
await writeFile(INDEX_FILE, JSON.stringify(data, null, 2));
const content = await readFile(FILE_PATH, 'utf8');
```

## Best Practices

### Type Safety
- Always use strict types in PHP with `declare(strict_types=1)`
- Explicit type hints for parameters and return values
- Use TypeScript for complex JavaScript projects (workspace packages use it)

### Security
- API key authentication for all write operations
- CORS configuration from environment variables
- SQL injection prevention through parameterized queries (implied by ORM usage)
- File operation validation and path sanitization

### Performance
- Pagination with configurable limits (max 100 items)
- Cache management with explicit flush operations
- Request body size limits (2MB in API server)
- Lazy loading of search index on server start

### Maintainability
- Single Responsibility Principle - methods do one thing
- Match expressions for routing (PHP 8.0+)
- Small, focused functions (most functions < 30 lines)
- Configuration externalized to environment variables

### Internationalization
- UTF-8 encoding throughout
- Text normalization (NFKD) for search tokenization
- Unicode-aware regex patterns (`\p{Letter}`, `\p{Number}`)
- German language UI strings in this project

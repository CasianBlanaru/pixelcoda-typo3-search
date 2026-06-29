# Development Guidelines

## Code Quality Standards

### PHP Development Patterns
- **Strict Types**: Always declare `strict_types=1` at file start
- **Type Declarations**: Full parameter and return type hints required (e.g., `string`, `int`, `ResponseInterface`)
- **Dependency Injection**: Constructor-based DI with `readonly` promoted properties in PHP 8.2+
- **PSR Standards**: Follow PSR-12 code style and PSR-4 autoloading conventions
- **Namespace Declarations**: Vendor\Extension\Layer\Feature structure (e.g., `PixelCoda\PixelcodaSearch\Controller\Backend`)

### JavaScript/React Patterns
- **Client Components**: Mark interactive components with `"use client"` directive
- **Async Server Components**: Default to async functions for data fetching
- **Hooks First**: Use functional components with hooks (useState, useEffect, useRef)
- **Dynamic Imports**: Lazy-load heavy libraries (GSAP) within useEffect
- **Prop Spreading**: Support `{...props}` for flexible component composition

### Naming Conventions
- **PHP Classes**: PascalCase with descriptive suffixes (Controller, Repository, Service, ViewHelper)
- **PHP Methods**: camelCase with action-oriented names (handleRequest, indexAction, switchModeAction)
- **PHP Constants**: SCREAMING_SNAKE_CASE (MODULE_ROUTE, TYPO3_CONF_VARS)
- **React Components**: PascalCase function names (GsapAnimatedContent, DevTools)
- **JavaScript Variables**: camelCase (elementRef, animationConfig, activeType)
- **File Names**: PascalCase for PHP classes matching class name, camelCase for React components

## Structural Conventions

### TYPO3 Backend Development
- **Controller Pattern**: Protected methods for actions, public handleRequest as entry point
- **ModuleTemplate Usage**: Use ModuleTemplateFactory for consistent backend UI
- **Flash Messages**: ContextualFeedbackSeverity enum for message types (OK, ERROR, WARNING, INFO)
- **Configuration Access**: Read from `$GLOBALS['TYPO3_CONF_VARS']`, write via ConfigurationManager
- **URI Building**: UriBuilder for route generation, never hardcoded URLs
- **Cache Management**: CacheManager for TYPO3 caches, manual cleanup for filesystem caches

### Next.js Frontend Architecture
- **App Router**: Use `app/` directory structure with page.jsx as route files
- **Metadata Export**: Export async `generateMetadata` for SEO
- **Search Parameters**: Always await searchParams before accessing properties
- **API Integration**: Fetch TYPO3 JSON endpoints with `cache: 'no-store'` for dynamic content
- **Error Handling**: Try-catch with error state display, never silent failures
- **URL Building**: Template literals with encodeURIComponent for query parameters

### File Organization
- **PHP Extensions**: Controller, ViewHelper, Service in `Classes/` directory
- **Configuration**: YAML for site config, PHP arrays for TCA/extension config
- **Templates**: Fluid templates in `Resources/Private/Templates/`
- **Public Assets**: CSS/JS in `Resources/Public/`, auto-published by TYPO3
- **Frontend Components**: Separate directories for `app/`, `components/`, `lib/`

## Common Internal API Patterns

### TYPO3 Dependency Injection
```php
public function __construct(
    protected ModuleTemplateFactory $moduleTemplateFactory,
    protected UriBuilder $backendUriBuilder,
    protected ConfigurationManager $configurationManager,
) {}
```
- Use protected property promotion
- Prefer interface types when available
- No manual GeneralUtility::makeInstance in constructors

### Configuration Management
```php
// Read configuration
$config = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['extension_key'];

// Write configuration
$this->configurationManager->setLocalConfigurationValueByPath(
    'EXTENSIONS/extension_key/setting',
    $value
);
```

### Flash Message Pattern
```php
$this->addFlashMessage(
    'Message content',
    'Title',
    ContextualFeedbackSeverity::OK
);
```

### YAML Configuration Access
```php
$loader = GeneralUtility::makeInstance(YamlFileLoader::class);
$writer = GeneralUtility::makeInstance(YamlFileWriter::class);
$config = $loader->load($path);
$writer->write($path, $config);
```

### React Component with Refs
```jsx
const elementRef = useRef(null);
useEffect(() => {
  const element = elementRef.current;
  if (!element) return;
  // operate on element
}, [dependencies]);
return <div ref={elementRef}>{children}</div>;
```

### GSAP Animation Registration
```jsx
const gsap = gsapModule.default || gsapModule;
const ScrollTrigger = scrollTriggerModule.default || scrollTriggerModule;
gsap.registerPlugin(ScrollTrigger);

const animationConfig = {
  duration: duration / 1000,
  scrollTrigger: {
    trigger: element,
    toggleActions: 'play none none none',
  },
};
gsap.from(element, { ...animationConfig, opacity: 0, y: 50 });
```

## Frequently Used Code Idioms

### Environment Path Resolution
```php
$path = Environment::getProjectPath() . '/config/sites/main/config.yaml';
if (!file_exists($path)) {
    $path = Environment::getPublicPath() . '/../config/sites/main/config.yaml';
}
```

### Recursive Directory Operations
```php
protected function removeDirectory(string $dir): void {
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? $this->removeDirectory($path) : unlink($path);
    }
    rmdir($dir);
}
```

### Match Expression for Routing
```php
return match ($action) {
    'switchMode' => $this->switchModeAction($request),
    'clearCache' => $this->clearCacheAction($request),
    default => $this->indexAction($request),
};
```

### Next.js Metadata Generation
```jsx
export async function generateMetadata({ searchParams }) {
  const resolvedSearchParams = await searchParams;
  return {
    title: `Page Title`,
    description: 'Description',
  };
}
```

### Conditional Rendering with Inline Styles
```jsx
{totalPages > 1 && (
  <div style={{ display: 'flex', gap: '0.5rem' }}>
    {/* pagination UI */}
  </div>
)}
```

### Dynamic Import with Fallback
```jsx
const gsapModule = await import('gsap');
const gsap = gsapModule.default || gsapModule;
```

## Popular Annotations

### PHP DocBlock Structure
```php
/**
 * Short description.
 *
 * Longer explanation if needed.
 */
```
- No `@param` or `@return` when types are declared
- Use docblocks only for clarification, not duplication

### React JSDoc Patterns
```jsx
/**
 * Component description.
 * 
 * @param {Object} props - Component props
 * @param {ReactNode} props.children - Child elements
 * @param {Object} props.animationSettings - GSAP configuration
 */
```

## Testing and Quality Assurance

### Code Style Requirements
- PHP: Strict type checking, no mixed returns
- JavaScript: ESLint-compatible modern syntax
- Indentation: 4 spaces for PHP, 2 spaces for JavaScript
- Line length: Prefer 120 characters max, enforce at 255 for PHP inputs

### Error Handling Strategy
- Backend: Try-catch with flash messages, never expose stack traces
- Frontend: Error state variables, user-friendly messages
- Logging: Use TYPO3 LogManager for backend, console.warn for frontend development

### Performance Considerations
- Cache API responses with appropriate headers
- Lazy-load heavy libraries (GSAP, ScrollTrigger)
- Use `cache: 'no-store'` only when necessary
- Cleanup GSAP animations in useEffect return functions
- Batch configuration writes, flush caches once after multiple operations

## Documentation Standards
- README.md files in each package describing purpose and usage
- Inline comments only for complex business logic
- Method names should be self-documenting
- Configuration examples in docblocks or separate example files

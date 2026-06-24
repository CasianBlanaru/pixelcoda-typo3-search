# Technology Stack

## Programming Languages

### PHP 8.2+
- Backend language for TYPO3 CMS
- Platform version locked to 8.2.0 in composer.json
- Modern PHP features (typed properties, attributes, enums)

### JavaScript/TypeScript
- Frontend language for Next.js (React)
- Node.js >=18.0.0 for search platform
- Node.js >=22.0.0 for Next.js frontend
- TypeScript 5.6+ for type safety

## Core Frameworks

### TYPO3 14.3
- Enterprise CMS framework
- Core system extensions:
  - cms-backend, cms-core, cms-frontend
  - cms-extbase (MVC framework)
  - cms-fluid (templating)
  - cms-form, cms-seo, cms-rte-ckeditor
  - cms-dashboard, cms-reactions, cms-webhooks

### Next.js (latest)
- React framework with App Router
- Server-side rendering (SSR)
- Static site generation (SSG)
- API routes and middleware

### React (latest)
- UI library for frontend
- Component-based architecture
- Hooks and modern patterns

## Key Dependencies

### TYPO3/PHP
**friendsoftypo3/headless** (dev-feature/typo3-v14)
- Headless CMS capabilities
- JSON API endpoints
- Content serialization

**Symfony Components** (via TYPO3)
- Cache, Console, DependencyInjection
- EventDispatcher, HttpFoundation, Mailer
- Routing, Validator, Messenger, Filesystem

**Doctrine DBAL**
- Database abstraction layer
- Query builder
- Schema management

**Guzzle HTTP Client**
- HTTP requests and API calls
- PSR-7/PSR-18 compliant

**Firebase PHP-JWT**
- JWT token handling
- API authentication

### Frontend
**@pixelcoda/headless-nextjs** (latest)
- TYPO3 Headless integration for Next.js
- Content rendering components
- API client utilities

### Search Platform
**Meilisearch** (inferred)
- Vector search engine
- AI-assisted search capabilities
- Fast full-text search

## Build Systems

### Composer
- PHP dependency management
- TYPO3 extension installation
- Autoloading (PSR-4)
- Platform requirements enforcement

**Commands:**
```bash
composer install      # Install dependencies
composer update       # Update dependencies
```

### Yarn (Yarn Berry/v3+)
- Node.js package management
- Workspace support
- Zero-installs with PnP

**Commands:**
```bash
yarn install          # Install dependencies
yarn dev              # Development server
yarn build            # Production build
```

### npm (for workspaces)
- Alternative to Yarn for monorepo
- Workspace management
- Script execution

**Commands:**
```bash
npm install           # Install all workspaces
npm run build         # Build all workspaces
npm run api:dev       # Run standalone API
```

## Development Tools

### DDEV
- Local development environment
- Docker-based (Apache/Nginx, PHP, MySQL)
- Mutagen sync for macOS performance
- Custom commands and hooks

### PHPStan 2.1+
- Static analysis for PHP
- Type checking and error detection
- Custom rules and baseline

### TypeScript Compiler
- Type checking for JavaScript
- Build-time validation
- IDE integration

## Development Commands

### TYPO3 Backend
```bash
# TYPO3 CLI
vendor/bin/typo3 cache:flush
vendor/bin/typo3 extension:setup

# DDEV shortcuts
ddev start
ddev restart
ddev exec [command]
```

### Next.js Frontend
```bash
# Development (frontend directory)
cd frontend
yarn install
yarn dev              # http://localhost:3000

# Production
yarn build
yarn start
```

### Search Platform
```bash
# Root workspaces
npm run api:dev       # Standalone API development
npm run widgets:dev   # Widgets development
npm run build         # Build all workspaces

# Simple API
npm start             # node simple-api.js
```

## Runtime Environment

### Environment Variables (Frontend)
```env
NEXT_PUBLIC_API_BASE_URL         # TYPO3 API endpoint
NEXT_PUBLIC_TYPO3_BASE_URL       # TYPO3 base URL
NEXT_PUBLIC_BASE_URL             # Frontend URL
NEXT_PUBLIC_FRONTEND_FILE_API    # File assets endpoint
NEXT_PUBLIC_SKIN                 # UI skin (premium/standard)
NEXT_PUBLIC_HEADLESS_DEVTOOLS    # DevTools enable flag
```

### PHP Configuration
- php-production.ini for production settings
- Memory limits, upload sizes, error reporting
- OPcache configuration

### Web Server
- Apache or Nginx
- Configured via deployment/typo3/apache-vhost.conf.template
- Reverse proxy to TYPO3 backend

## Deployment

### Docker Support
- Dockerfile at project root
- Health check endpoints (healthz.php, diag.php)
- Entrypoint scripts for initialization
- Multi-stage builds supported

### Railway Platform
- railway.json configuration files
- Environment variable management
- Automatic deployments from Git

### Database
- MySQL/MariaDB for TYPO3
- Doctrine DBAL abstraction
- Schema management via TYPO3 Install Tool

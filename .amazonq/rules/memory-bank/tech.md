# Technology Stack

## Programming Languages

### PHP
- **Version**: 8.2.0+ (configured platform requirement)
- **Usage**: TYPO3 backend, extensions, API generation
- **Key Libraries**:
  - Symfony components (console, dependency-injection, cache, event-dispatcher)
  - Doctrine DBAL (database abstraction)
  - Guzzle (HTTP client)
  - PHPStan (static analysis)

### JavaScript/TypeScript
- **Node.js Version**: >=18.0.0 (root), >=22.0.0 (frontend)
- **TypeScript Version**: 5.9.3
- **Usage**: Next.js frontend, build tools, search platform

### SQL
- Database queries and schema definitions
- TypoScript template setup

## TYPO3 CMS

### Core Version
- **TYPO3 CMS**: ^14.3 (latest LTS)
- **Distribution**: typo3/cms-base-distribution

### Installed System Extensions
- cms-backend, cms-core, cms-frontend
- cms-extbase, cms-fluid (MVC framework)
- cms-form (form builder)
- cms-seo, cms-dashboard, cms-webhooks
- cms-rte-ckeditor (rich text editor)
- And 10+ additional system extensions

### Third-Party Extensions
- **friendsoftypo3/headless**: ^5.0@RC - Headless API generation

### Custom Extensions (PixelCoda)
- **pixelcoda/typo3-search**: dev-main - AI-assisted search
- **pixelcoda/fe-editor**: @dev - Frontend editing
- **pixelcoda/content-gsap-animation**: dev-main as 3.1.0 - Animations
- **pixelcoda/sitepackage**: ^1.0 - Site configuration

## Frontend Stack

### Next.js Application
- **Next.js**: ^16.2.9
- **React**: ^19.2.7
- **React DOM**: ^19.2.7
- **PostCSS**: ^8.5.15

### PixelCoda Packages
- **@pixelcoda/headless-nextjs**: ^1.1.4 - TYPO3 headless integration

### Animation
- **GSAP**: ^3.12.5 - Professional animation library

## Search Platform Stack

### Core Dependencies (from packages/pixelcoda_search/)
- Modern TypeScript/JavaScript architecture
- Workspace-based monorepo structure
- AI/LLM integration capabilities
- Vector search support via Meilisearch (implied)

## Build Systems & Package Management

### PHP Dependencies
- **Composer**: Standard TYPO3 dependency management
- **Platform**: PHP 8.2.0
- **Installers**: typo3/cms-composer-installers, typo3/class-alias-loader

### JavaScript Dependencies
- **Yarn**: Primary package manager (workspace support)
- **npm**: Alternative package manager
- **Workspaces**: Monorepo management for search platform

## Development Tools

### PHP Quality Tools
- **PHPStan**: ^2.1 - Static analysis
- **php-cs-fixer**: Code style fixer (in pixelcoda_search)
- **Rector**: Automated refactoring (in pixelcoda_search)

### TYPO3 CLI Tools
- `bin/typo3` - TYPO3 console commands
- `bin/phpstan` - Static analysis
- `bin/fluid` - Fluid template compiler
- `bin/yaml-lint` - YAML validation

## Development Environment

### DDEV
- Docker-based local development
- Apache or Nginx web server options
- PHP 8.2+ container
- MySQL/MariaDB database
- Traefik reverse proxy with SSL

### Environment Variables (Frontend)
```env
NEXT_PUBLIC_API_BASE_URL         # TYPO3 API endpoint
NEXT_PUBLIC_TYPO3_BASE_URL       # TYPO3 base URL
NEXT_PUBLIC_BASE_URL             # Frontend base URL
NEXT_PUBLIC_FRONTEND_FILE_API    # File API path
NEXT_PUBLIC_SKIN                 # UI skin (premium/default)
NEXT_PUBLIC_HEADLESS_DEVTOOLS    # Enable dev overlay
```

## Development Commands

### Frontend (Next.js)
```bash
yarn dev          # Development server (0.0.0.0)
yarn build        # Production build
yarn start        # Start production server (port 3000)
```

### Root Project (Search Platform)
```bash
npm start         # Start simple-api.js
npm run dev       # Development mode
npm run build     # Build all workspaces
npm run api:dev   # Standalone API dev server
npm run widgets:dev   # Widgets dev server
npm test          # Run extension tests
```

### TYPO3 Extensions
```bash
npm run build:extension    # Build TYPO3 extension assets
npm run test:extension     # Run extension tests (Jest)
npm run lint:extension     # Lint extension code
```

## Deployment Platforms

### Railway
- Configuration: `railway.json` (root and frontend)
- Automated deployments from git
- Environment variable management

### Docker
- Dockerfile in root directory
- TYPO3-specific entrypoint scripts
- Apache/PHP configuration templates

## Database

### Supported Systems
- MySQL/MariaDB (via Doctrine DBAL)
- PostgreSQL support (via Doctrine)
- SQLite for development/testing

### Migrations
- TYPO3 database analyzer
- Extension SQL definitions in `ext_tables.sql`

## Security & Authentication

- **Firebase JWT**: php-jwt library for token authentication
- **Email Validation**: egulias/email-validator
- **SVG Sanitization**: enshrined/svg-sanitize
- TYPO3 built-in authentication and authorization

## HTTP & Networking

- **Guzzle HTTP**: ^7.x - HTTP client for API calls
- PSR-7/PSR-15 HTTP message interfaces
- Symfony HTTP Foundation components

## Caching

- Symfony Cache component
- PSR-6/PSR-16 cache interfaces
- TYPO3 caching framework (file, database, Redis-ready)

## Version Control & Repository

- **Git**: Primary VCS
- **Repository**: https://github.com/CasianBlanaru/pixelcoda-typo3-search.git
- Path repositories for local package development

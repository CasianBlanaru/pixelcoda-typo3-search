# Technology Stack

## Programming Languages

### PHP
- **Version**: 8.2.0 (platform requirement)
- **Usage**: TYPO3 CMS backend, custom extensions
- **Key Features**: Type declarations, attributes, readonly properties

### JavaScript/TypeScript
- **Node.js**: >=18.0.0 (search platform), >=22.0.0 (frontend)
- **TypeScript**: 5.9.3 for type-safe development
- **Usage**: Next.js frontend, search platform, extension tooling

## Backend Technologies

### TYPO3 CMS
- **Version**: 14.3
- **Type**: Enterprise PHP CMS
- **Core Modules**:
  - cms-backend, cms-frontend
  - cms-extbase (MVC framework)
  - cms-fluid (templating engine)
  - cms-form, cms-seo, cms-rte-ckeditor

### Headless Extension
- **Package**: `friendsoftypo3/headless` v5.0 RC
- **Purpose**: Transform TYPO3 pages to JSON API
- **Features**: Content element serialization, routing, file handling

### Database
- **Doctrine DBAL**: Database abstraction layer
- **Supported**: MySQL/MariaDB (typical TYPO3 deployment)

### PHP Dependencies
- **Symfony Components**: Cache, Console, Dependency Injection, Event Dispatcher, Mailer, Messenger, Routing, Validator
- **Guzzle HTTP**: HTTP client library
- **PHPStan**: Static analysis tool (v2.1)

## Frontend Technologies

### Next.js
- **Version**: 16.2.9
- **Framework**: React-based with App Router
- **Features**: SSR, SSG, API routes, image optimization
- **Rendering**: Server Components and Client Components

### React
- **Version**: 19.2.7
- **Usage**: Component-based UI
- **Integration**: `@pixelcoda/headless-nextjs` v1.1.4 for TYPO3 integration

### PostCSS
- **Version**: 8.5.15
- **Usage**: CSS processing and transformations

## Search Platform Technologies

### Core Stack
- **Meilisearch**: Vector search engine
- **AI/LLM**: Integration via `@pixelcoda/llm-adapter`
- **Workspaces**: Monorepo with multiple packages

### Components
- **API Service**: Express/Node.js based
- **Worker**: Background job processing
- **Widgets**: UI components for search interface

## Build Systems & Tools

### PHP Build
- **Composer**: Dependency management
  - Class autoloading (PSR-4)
  - Package installation
  - Path repositories for local extensions

### JavaScript Build
- **npm/yarn**: Package management
- **Workspaces**: Monorepo management
- **Next.js Build**: Production optimization, code splitting

### Development Tools
- **DDEV**: Docker-based local development
  - PHP 8.2 container
  - Database container
  - Web server (Apache/Nginx)
  - Mutagen file sync

## Development Commands

### TYPO3 Backend
```bash
composer install              # Install PHP dependencies
composer require <package>    # Add new dependency
vendor/bin/typo3              # TYPO3 CLI
```

### Next.js Frontend
```bash
cd frontend
yarn install                  # Install dependencies
yarn dev                      # Development server (port 3000)
yarn build                    # Production build
yarn start                    # Production server
```

### Search Platform
```bash
npm install                   # Install all workspace dependencies
npm start                     # Run simple API
npm run build                 # Build all workspaces
npm run api:dev              # Development API
npm run widgets:dev          # Development widgets
npm test                      # Run tests
```

### DDEV Environment
```bash
ddev start                    # Start containers
ddev stop                     # Stop containers
ddev ssh                      # SSH into web container
ddev exec <command>          # Execute command in container
ddev composer <command>      # Run Composer commands
```

## Testing & Quality

### PHP
- **PHPStan**: Static analysis (level defined in phpstan.neon)
- **PHP CS Fixer**: Code style enforcement (.php-cs-fixer.php)
- **Rector**: Automated refactoring (rector.php)

### JavaScript
- **Jest**: Testing framework (extension tests)
- **ESLint**: Linting (configured per workspace)

## Deployment

### Docker
- **Dockerfile**: Multi-stage build for production
- **Base Image**: PHP 8.2 with Apache/Nginx

### Railway
- **railway.json**: Deployment configuration
- **Environment**: Node.js for frontend, PHP for backend

### Configuration Files
- `composer.json` - PHP dependencies, autoloading
- `package.json` - Node.js scripts, workspaces
- `next.config.js` - Next.js build configuration
- `.ddev/config.yaml` - Local dev environment
- `.env.local` - Environment variables (API endpoints, feature flags)

## Key Libraries & Packages

### TYPO3 Extensions
- typo3/cms-* - Core TYPO3 modules
- friendsoftypo3/headless - JSON API
- pixelcoda/* - Custom extensions (symlinked from packages/)

### Frontend
- @pixelcoda/headless-nextjs - TYPO3-Next.js bridge
- react-dom - React rendering

### Utilities
- dotenv - Environment variable loading
- firebase/php-jwt - JWT authentication
- enshrined/svg-sanitize - SVG security
- masterminds/html5 - HTML parsing

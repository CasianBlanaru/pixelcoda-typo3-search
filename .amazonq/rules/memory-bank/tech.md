# Technology Stack

## Programming Languages
- **PHP 8.2** - TYPO3 backend and extensions
- **JavaScript/JSX** - Next.js frontend and Node.js search API
- **TypeScript 5.9** - Type definitions for Node.js packages
- **SQL** - Database queries and migrations
- **TypoScript** - TYPO3 configuration language

## Backend Stack

### TYPO3 CMS (v14.3)
Core packages:
- cms-core, cms-backend, cms-frontend
- cms-extbase, cms-fluid (MVC framework)
- cms-form, cms-seo, cms-rte-ckeditor
- headless extension (friendsoftypo3/headless v5.0 RC)

### PHP Dependencies
- Symfony components (cache, console, dependency-injection, event-dispatcher, mailer, etc.)
- Doctrine DBAL for database abstraction
- Guzzle HTTP client
- PHPStan for static analysis
- PSR interfaces (PSR-3, PSR-6, PSR-7, PSR-11, PSR-14)

## Frontend Stack

### Next.js 16.2.9
- React 19.2.7
- Server Components and App Router
- PostCSS 8.5

### Custom Package
- @pixelcoda/headless-nextjs v1.1.4 - TYPO3 headless integration

## Search Platform

### Node.js (>=18.0.0)
- Workspace-based monorepo
- Workspaces: api, worker, widgets, llm-adapter, standalone-api
- dotenv for environment configuration

## Build Systems & Tools

### Backend
- **Composer** - PHP dependency management
- Platform: PHP 8.2.0
- Auto-loader: PSR-4 autoloading

### Frontend
- **Yarn** (Berry/v2+) - Package manager
- **Next.js CLI** - Build and development server
- Node.js >=22.0.0 required

### Search
- **npm workspaces** - Monorepo management
- TypeScript compiler

## Development Tools

### Local Environment
- **DDEV** - Docker-based development environment
  - Apache/Nginx web server
  - MariaDB/MySQL database
  - PHP-FPM
  - Mailhog for email testing
  - Mutagen file sync

### Code Quality
- **PHPStan** - PHP static analysis
- **php-cs-fixer** - PHP code formatting (in pixelcoda_search)
- **Rector** - PHP automated refactoring

## Database
- **MariaDB/MySQL** - Primary database
- Doctrine DBAL for abstraction

## Deployment

### Platforms
- **Railway.app** - Production hosting
- Docker support
- railway.json configurations

### Environment Variables
- NEXT_PUBLIC_API_BASE_URL
- NEXT_PUBLIC_TYPO3_BASE_URL
- NEXT_PUBLIC_BASE_URL
- NEXT_PUBLIC_FRONTEND_FILE_API
- NEXT_PUBLIC_SKIN
- NEXT_PUBLIC_HEADLESS_DEVTOOLS

## Development Commands

### TYPO3 Backend
```bash
# Install dependencies
composer install

# Clear cache
vendor/bin/typo3 cache:flush

# Run CLI commands
vendor/bin/typo3 <command>
```

### Next.js Frontend
```bash
cd frontend
yarn install      # Install dependencies
yarn dev          # Development server
yarn build        # Production build
yarn start        # Production server
```

### Search Platform
```bash
npm install                # Install all workspaces
npm start                  # Run simple API
npm run build              # Build all workspaces
npm run api:dev            # Standalone API development
npm run widgets:dev        # Widget development
npm test                   # Run tests
```

### DDEV
```bash
ddev start        # Start environment
ddev stop         # Stop environment
ddev restart      # Restart services
ddev ssh          # SSH into web container
ddev composer     # Run composer commands
ddev exec         # Execute commands
```

## Key Configuration Files
- `composer.json` - PHP dependencies and autoloading
- `frontend/package.json` - Frontend dependencies
- `package.json` - Search platform workspaces
- `frontend/next.config.js` - Next.js configuration
- `.ddev/config.yaml` - DDEV environment
- `config/system/settings.php` - TYPO3 settings
- `.env.local` - Environment variables

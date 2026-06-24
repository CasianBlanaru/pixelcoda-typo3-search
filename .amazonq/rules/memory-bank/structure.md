# Project Structure

## Root Directory Organization

```
typo3-inst/
├── frontend/           # Next.js frontend application
├── packages/           # TYPO3 extensions (custom)
├── public/             # TYPO3 public assets and entry point
├── config/             # TYPO3 configuration
├── var/                # Runtime cache, logs, sessions
├── vendor/             # PHP dependencies (Composer)
├── deployment/         # Docker/deployment configurations
├── .ddev/              # Local development environment
└── node_modules/       # Node.js dependencies (workspaces)
```

## Frontend Application (`frontend/`)

```
frontend/
├── src/
│   ├── app/            # Next.js app router pages
│   ├── components/     # React components
│   └── lib/            # Utilities and libraries
├── public/             # Static assets
├── .next/              # Next.js build output
├── next.config.js      # Next.js configuration
├── package.json        # Dependencies (@pixelcoda/headless-nextjs)
└── .env.local          # Environment variables
```

## TYPO3 Extensions (`packages/`)

### Core Extensions
- **pixelcoda_search/**: AI-powered search module
  - Classes/: PHP controllers and services
  - Configuration/: TCA, routing, TypoScript
  - Resources/: Frontend assets (JS/CSS)
  - Tests/: Unit and functional tests
  
- **typo3_fe_editing/**: Frontend editing capabilities
  - packages/pixelcoda_fe_editor/: Main editor package
  - Classes/: Editor logic and hooks
  - Resources/: Editor UI components
  
- **content_gsap_animation/**: GSAP animation content elements
  - Classes/: Content element handlers
  - JavaScript/: GSAP integration
  - Configuration/: TCA definitions
  
- **pixelcoda_sitepackage/**: Site configuration
  - Configuration/: Site config, TypoScript
  - Resources/: Templates and assets

## TYPO3 Core Structure

### Configuration (`config/`)
- `sites/main/`: Site configuration (domains, languages)
- `system/`: Global TYPO3 settings
  - settings.php: Database, environment config
  - additional.php: Custom PHP configuration

### Public Assets (`public/`)
- `index.php`: TYPO3 entry point
- `fileadmin/`: User-uploaded files
- `_assets/`: Processed assets (hashed)
- `typo3temp/`: Temporary files and caches

### Runtime (`var/`)
- `cache/`: File-based caching
  - code/: PHP class caches, DI containers
  - data/: Page and content caches
- `log/`: Application logs
- `lock/`: File locks for synchronization
- `session/`: PHP session data

## Deployment Configuration

### Docker (`deployment/typo3/`)
- `entrypoint.sh`: Container initialization
- `apache-vhost.conf.template`: Web server config
- `php-production.ini`: PHP runtime settings
- `set-permissions.php`: File permission setup
- `healthz.php`: Health check endpoint

### DDEV (`.ddev/`)
- Local development environment configuration
- Docker Compose overrides
- Custom commands and providers
- Mutagen sync for performance

## Architectural Patterns

### Decoupled Architecture
- **Backend**: TYPO3 as headless CMS exposing JSON API
- **Frontend**: Next.js consuming TYPO3 API
- **Communication**: REST API via friendsoftypo3/headless extension
- **Assets**: Shared via `/headless/fileadmin` endpoint

### Monorepo with Workspaces
- Root package.json defines npm workspaces
- Separate apps (api, worker, widgets) in workspaces
- Shared packages (llm-adapter) for code reuse
- Independent build processes per workspace

### Extension-Based TYPO3
- Custom extensions in `packages/`
- Symlinked to vendor via Composer path repositories
- Extension configuration via ext_localconf.php, ext_tables.php
- TCA for database schema and forms

### Composer-Managed PHP
- TYPO3 installed via Composer (typo3/cms-*)
- Custom extensions via path repositories
- Autoloading via PSR-4
- Lock file for reproducible builds

## Key Component Relationships

### Frontend → TYPO3 API
- Next.js fetches content via NEXT_PUBLIC_API_BASE_URL
- Headless extension serializes TYPO3 content to JSON
- File assets resolved via NEXT_PUBLIC_FRONTEND_FILE_API

### Search Module
- TYPO3 extension provides admin interface
- Standalone API (simple-api.js) for search queries
- Worker processes for indexing
- LLM adapter for AI-assisted answers

### Frontend Editing
- Editor UI loaded in Next.js frontend
- Communicates with TYPO3 backend for content updates
- Real-time preview of changes
- Drag-and-drop content management

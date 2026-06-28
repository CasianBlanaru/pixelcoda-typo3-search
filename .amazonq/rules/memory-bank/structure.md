# Project Structure

## Root Directory Organization

```
typo3-inst/
├── frontend/              # Next.js frontend application
├── packages/              # TYPO3 extensions and custom packages
├── config/                # TYPO3 configuration
├── public/                # TYPO3 public web root
├── var/                   # Runtime cache, logs, sessions
├── vendor/                # PHP dependencies (Composer)
├── deployment/            # Deployment scripts and configurations
├── .ddev/                 # DDEV local development environment
└── composer.json          # PHP/TYPO3 dependencies
```

## Frontend Application (`frontend/`)

Next.js application structure:
- `src/app/` - Next.js App Router pages and layouts
- `src/components/` - React components
- `src/lib/` - Utility functions and helpers
- `.env.local` - Environment configuration for API URLs
- `next.config.js` - Next.js configuration
- `railway.json` - Railway deployment configuration

## TYPO3 Extensions (`packages/`)

### Core Extensions
- **pixelcoda_search/**: AI-assisted search extension with:
  - `Classes/` - PHP backend logic
  - `Configuration/` - TCA, routing, TypoScript
  - `Resources/` - Frontend assets
  - `Tests/` - Unit and integration tests
  - PHP quality tools (phpstan, php-cs-fixer, rector)

- **typo3_fe_editing/**: Frontend editing capabilities
  - Nested package structure under `packages/pixelcoda_fe_editor/`
  - Enables in-place content editing

- **content_gsap_animation/**: GSAP animation content elements
  - `Classes/` - Data processors and domain models
  - `Configuration/` - Content element definitions
  - `JavaScript/` - Animation scripts
  - `Build/` - Frontend build configuration

- **pixelcoda_sitepackage/**: Site configuration and templates
  - `Configuration/` - Site configuration, TypoScript
  - `Resources/` - Templates and assets

## TYPO3 Configuration (`config/`)

```
config/
├── sites/main/           # Site configuration (domains, languages)
└── system/               # System configuration
    ├── settings.php      # Database, encryption keys
    └── additional.php    # Custom PHP configuration
```

## Public Web Root (`public/`)

TYPO3's entry point:
- `index.php` - Main entry script
- `_assets/` - Generated frontend assets
- `fileadmin/` - User-uploaded files
- `typo3temp/` - Temporary files and caches

## Runtime Directory (`var/`)

- `cache/code/` - Compiled PHP code cache
- `cache/data/` - Data caches
- `log/` - Application logs
- `session/` - User sessions
- `lock/` - File-based locks

## Deployment (`deployment/`)

Scripts and configurations for production deployment:
- `typo3/` - TYPO3-specific deployment files
  - `entrypoint.sh` - Docker/Railway startup script
  - `*.php` - Database setup, configuration scripts
  - `apache-vhost.conf.template` - Apache configuration
- Railway and manual deployment documentation
- Database dumps and setup SQL

## Development Environment (`.ddev/`)

DDEV Docker-based local environment:
- `config.yaml` - DDEV project configuration
- `apache/` - Apache web server configuration
- `php/` - PHP configuration overrides
- `providers/` - Integration with hosting providers
- `commands/` - Custom DDEV commands

## Architectural Patterns

### Headless CMS Architecture
- **Backend (TYPO3)**: Content management, API generation
- **Frontend (Next.js)**: Content consumption, rendering
- **Communication**: JSON API via friendsoftypo3/headless extension

### Monorepo Structure
Root contains both TYPO3 (PHP) and frontend (Node.js) codebases:
- Shared deployment configurations
- Unified version control
- Independent runtime environments

### Extension-Based Modularity
TYPO3 functionality organized as Composer packages:
- Local packages in `packages/` directory
- Symlinked to `vendor/pixelcoda/` via Composer path repositories
- Each extension is self-contained with own dependencies

### Search Platform Workspace Architecture
`pixelcoda_search` uses npm workspaces:
- `apps/api` - REST API server
- `apps/worker` - Background job processor
- `apps/widgets` - Embeddable search widgets
- `packages/llm-adapter` - AI/LLM integration layer
- `standalone-api` - Standalone search API deployment

## Key Relationships

- Frontend fetches content from TYPO3 via API endpoints
- TYPO3 extensions enhance headless API output
- Search extension provides separate API for search functionality
- Frontend editor bridges TYPO3 backend with Next.js frontend
- GSAP animation extension provides both backend content elements and frontend JavaScript

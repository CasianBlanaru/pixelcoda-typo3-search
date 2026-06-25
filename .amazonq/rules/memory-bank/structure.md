# Project Structure

## Directory Organization

```
typo3-inst/
├── config/                    # TYPO3 system configuration
│   ├── sites/main/           # Site-specific settings
│   └── system/               # System-wide configuration (settings.php)
├── deployment/               # Deployment configurations
│   └── typo3/               # TYPO3-specific deployment scripts and configs
├── frontend/                 # Next.js headless frontend
│   ├── src/
│   │   ├── app/             # Next.js App Router pages
│   │   ├── components/      # React components
│   │   └── lib/             # Utility libraries
│   ├── public/              # Static assets
│   ├── .next/               # Build output
│   ├── next.config.js       # Next.js configuration
│   ├── package.json         # Frontend dependencies
│   └── .env.local           # Environment variables
├── packages/                 # TYPO3 custom extensions
│   ├── pixelcoda_search/    # AI search platform
│   ├── typo3_fe_editing/    # Frontend editing tools
│   ├── content_gsap_animation/  # GSAP animation extension
│   ├── pixelcoda_content_gsap_animation/  # Alternative GSAP extension
│   ├── pixelcoda_sitepackage/   # Site package
│   └── site_package/        # Additional site package
├── public/                   # TYPO3 web root
│   ├── fileadmin/           # User-uploaded files
│   ├── typo3temp/           # Temporary files and caches
│   ├── _assets/             # Processed assets
│   └── index.php            # TYPO3 entry point
├── var/                      # Runtime data
│   ├── cache/               # Application cache
│   ├── log/                 # Log files
│   ├── lock/                # Lock files
│   └── session/             # Session storage
├── vendor/                   # Composer dependencies (PHP)
│   ├── typo3/               # TYPO3 core packages
│   ├── friendsoftypo3/      # Headless extension
│   ├── pixelcoda/           # Symlinked custom extensions
│   └── symfony/             # Symfony components
├── .ddev/                    # DDEV Docker development environment
├── composer.json             # PHP dependencies and project config
├── package.json              # Root-level Node.js workspace config
└── README.md                 # Project documentation
```

## Core Components

### TYPO3 Backend
**Location**: Root directory  
**Purpose**: CMS backend for content management  
**Key Files**:
- `composer.json` - PHP dependencies, TYPO3 14.3 core and extensions
- `config/system/settings.php` - Database and system configuration
- `public/index.php` - Application entry point

### Next.js Frontend
**Location**: `frontend/`  
**Purpose**: Headless frontend consuming TYPO3 JSON API  
**Key Files**:
- `src/app/` - App Router pages (e.g., `suche/page.jsx` for search)
- `src/components/Renderer.jsx` - Renders TYPO3 content elements
- `.env.local` - API endpoints and configuration

### Custom Extensions
**Location**: `packages/`  
**Purpose**: TYPO3 extension development  
**Structure**: Each extension follows TYPO3 conventions:
- `Classes/` - PHP classes (Controllers, ViewHelpers, etc.)
- `Configuration/` - TCA, TypoScript, routing
- `Resources/` - Templates, assets
- `ext_emconf.php` - Extension metadata

### Search Platform
**Location**: `packages/pixelcoda_search/`  
**Architecture**: Workspace-based monorepo  
**Workspaces**:
- `apps/api` - Search API service
- `apps/worker` - Background processing
- `apps/widgets` - UI components
- `packages/llm-adapter` - AI/LLM integration
- `standalone-api` - Standalone search service

## Component Relationships

### Data Flow
1. **Content Creation**: Editors create content in TYPO3 backend
2. **API Exposure**: Headless extension transforms content to JSON
3. **Frontend Consumption**: Next.js fetches and renders JSON data
4. **Search Indexing**: Search platform indexes content for AI search

### Extension Dependencies
- **TYPO3 Core** (14.3) provides base CMS functionality
- **friendsoftypo3/headless** (5.0) enables JSON API
- **pixelcoda/fe-editor** adds frontend editing UI
- **pixelcoda/typo3-search** integrates AI search
- **pixelcoda/content-gsap-animation** adds animation capabilities

### Frontend-Backend Integration
- Next.js reads `NEXT_PUBLIC_API_BASE_URL` from `.env.local`
- Renderer component maps TYPO3 content types to React components
- FileAdmin assets served via `/headless/fileadmin` endpoint

## Architectural Patterns

### Headless CMS Architecture
- **Separation of Concerns**: Content management (TYPO3) decoupled from presentation (Next.js)
- **API-First**: JSON API as contract between backend and frontend
- **Extensibility**: TYPO3 extensions add custom functionality

### Monorepo Pattern
- Root manages TYPO3 and shared configuration
- `frontend/` self-contained Next.js application
- `packages/` individual TYPO3 extensions with own dependencies
- npm workspaces for JavaScript packages

### Development Environment
- **DDEV** provides containerized TYPO3, database, web server
- **Local development**: Frontend on port 3000, TYPO3 API on localhost
- **Hot reload**: Next.js dev mode and DDEV file syncing

## Configuration Files

- `composer.json` - PHP dependencies, TYPO3 platform version (PHP 8.2)
- `package.json` - npm workspaces, search platform scripts
- `frontend/package.json` - Next.js and React dependencies
- `.ddev/config.yaml` - Docker development environment
- `frontend/.env.local` - Frontend environment variables
- `config/system/settings.php` - TYPO3 database and system config

# Project Structure

## Root Directory Organization

```
typo3-inst/
├── frontend/              # Next.js frontend application
├── packages/              # TYPO3 extensions (composer packages)
├── public/                # TYPO3 public files & assets
├── config/                # TYPO3 configuration
├── var/                   # Runtime cache, logs, sessions
├── vendor/                # Composer dependencies
├── deployment/            # Deployment scripts and configs
├── .ddev/                 # DDEV local development environment
└── simple-api.js          # Search API entry point
```

## Frontend Application (`frontend/`)

Next.js application structure:
- `src/app/` - App Router pages and layouts
- `src/components/` - React components
- `src/lib/` - Utility functions and API clients
- `public/` - Static assets
- `.env.local` - Environment configuration
- `next.config.js` - Next.js configuration
- `railway.json` - Railway deployment config

## TYPO3 Extensions (`packages/`)

Custom extensions organized as composer packages:

### Frontend Editing (`typo3_fe_editing/`)
Visual frontend editor with drag-and-drop, contextual editing, AI assistant.

### Search Module (`pixelcoda_search/`)
AI-powered search platform with vector search, API-first architecture.

### GSAP Animations (`content_gsap_animation/`, `pixelcoda_content_gsap_animation/`)
Content elements with GSAP-powered animations.

### Sitepackage (`pixelcoda_sitepackage/`, `site_package/`)
Custom site configuration and templates.

## TYPO3 Core Structure

### Public Directory (`public/`)
- `index.php` - TYPO3 entry point
- `fileadmin/` - User-uploaded files
- `typo3temp/` - Temporary files and processed assets
- `_assets/` - Compiled frontend assets

### Configuration (`config/`)
- `config/system/settings.php` - Core configuration
- `config/sites/main/` - Site configuration

### Runtime (`var/`)
- `var/cache/` - Application cache
- `var/log/` - Application logs
- `var/session/` - User sessions
- `var/lock/` - File locks

### Vendor (`vendor/`)
- `typo3/` - TYPO3 CMS core packages
- `friendsoftypo3/headless/` - Headless API extension
- `pixelcoda/` - Symlinked custom extensions
- Third-party libraries

## Deployment (`deployment/`)

Scripts and configurations for Railway and production deployment:
- `deployment/typo3/` - TYPO3-specific deployment files
- SQL dumps and setup scripts
- Docker configurations
- Database import utilities

## Development Environment (`.ddev/`)

DDEV configuration for local development:
- Apache/Nginx configuration
- PHP settings
- Database configuration
- Custom commands

## Architectural Patterns

### Decoupled Architecture
- Backend: TYPO3 serves as headless CMS via JSON API
- Frontend: Next.js consumes API and renders pages
- Communication: REST API endpoints

### Extension Architecture
- Composer packages in `packages/` directory
- Symlinked into `vendor/pixelcoda/`
- Standard TYPO3 extension structure (Classes, Configuration, Resources)

### Content Flow
1. Content created in TYPO3 backend
2. Headless extension transforms to JSON
3. Next.js fetches via API
4. React components render content

### Search Architecture
- Standalone Node.js API (`simple-api.js`)
- Workspace-based monorepo structure
- LLM adapter for AI features
- Widget system for embeddable search

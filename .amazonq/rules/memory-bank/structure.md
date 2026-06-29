# Project Structure

## Root Organization
```
typo3-inst/
├── config/              # TYPO3 system configuration
├── deployment/          # Railway deployment scripts and SQL
├── frontend/            # Next.js application
├── packages/            # Local TYPO3 extensions
├── public/              # TYPO3 web root
├── var/                 # TYPO3 cache and runtime data
└── vendor/              # Composer dependencies
```

## Frontend Application (`frontend/`)
```
frontend/
├── src/
│   ├── app/            # Next.js App Router pages
│   ├── components/     # React components
│   └── lib/            # Utility functions and API clients
├── public/             # Static assets
├── next.config.js      # Next.js configuration
└── package.json        # Node dependencies
```

## TYPO3 Extensions (`packages/`)
- **pixelcoda_fe_editor**: Frontend editing with inline text, drag-drop, AI assistant
- **pixelcoda_content_gsap_animation**: GSAP scroll animations for content elements
- **pixelcoda_search**: Custom search module for TYPO3 backend
- **pixelcoda_sitepackage**: Site configuration and templates
- **typo3_fe_editing**: Development workspace for fe-editor extension

## Core Components

### Backend (TYPO3)
- **Headless API**: friendsoftypo3/headless provides JSON endpoints
- **Content Management**: Standard TYPO3 tt_content records with custom CTypes
- **Frontend Editing Middleware**: PSR-15 middleware injecting editing toolbar
- **Search Controller**: Custom backend module for content search

### Frontend (Next.js)
- **API Integration**: @pixelcoda/headless-nextjs handles TYPO3 JSON consumption
- **Component Rendering**: Dynamic content element rendering from API
- **GSAP Animations**: GsapAnimatedContent wrapper for scroll-triggered effects
- **DevTools**: Debug overlay for headless development

## Architectural Patterns
- **Decoupled Architecture**: TYPO3 backend at api.typo3-inst.localhost, Next.js at typo3-inst.localhost
- **Monorepo Structure**: Local extensions as Composer path repositories
- **Environment Separation**: .env.local for frontend, config/system/ for backend
- **PSR Standards**: PSR-15 middleware, PSR-7 HTTP messages in TYPO3 extensions

# Technology Stack

## Programming Languages
- **PHP**: 8.2+ (TYPO3 backend, extensions)
- **JavaScript/JSX**: ES2022+ (Next.js frontend, React components)
- **TypeScript**: Type definitions via @pixelcoda/headless-nextjs
- **SQL**: MySQL/MariaDB for TYPO3 database
- **TypoScript**: TYPO3 configuration language

## Backend Stack
- **CMS**: TYPO3 14.3 (cms-core, cms-backend, cms-frontend)
- **Headless Extension**: friendsoftypo3/headless ^5.0
- **PHP Framework**: Symfony components (dependency-injection, event-dispatcher, console)
- **Database**: Doctrine DBAL
- **HTTP**: PSR-7/PSR-15 (Guzzle, PSR implementations)

## Frontend Stack
- **Framework**: Next.js 16.2.9 (App Router)
- **React**: 19.2.7 (react, react-dom)
- **TYPO3 Integration**: @pixelcoda/headless-nextjs ^1.1.4
- **Animation**: GSAP 3.12.5 (ScrollTrigger plugin)
- **Styling**: CSS modules, PostCSS 8.5.15

## Development Tools
- **Dependency Management**: Composer (backend), Yarn (frontend)
- **Local Environment**: DDEV (Docker-based)
- **Code Quality**: PHPStan 2.1 (static analysis), php-cs-fixer (formatting)
- **Version Control**: Git with .gitignore patterns
- **Deployment**: Railway (Docker, automated scripts)

## Build Systems

### TYPO3 Backend
```bash
composer install              # Install PHP dependencies
vendor/bin/typo3 cache:flush # Clear TYPO3 caches
vendor/bin/typo3 extension:setup # Activate extensions
```

### Next.js Frontend
```bash
cd frontend
yarn install    # Install Node dependencies
yarn dev        # Development server (port 3000)
yarn build      # Production build
yarn start      # Production server
```

## Environment Variables

### Frontend (`frontend/.env.local`)
```
NEXT_PUBLIC_API_BASE_URL=https://api.typo3-inst.localhost
NEXT_PUBLIC_TYPO3_BASE_URL=https://api.typo3-inst.localhost
NEXT_PUBLIC_BASE_URL=https://typo3-inst.localhost
NEXT_PUBLIC_FRONTEND_FILE_API=/headless/fileadmin
NEXT_PUBLIC_SKIN=premium
NEXT_PUBLIC_HEADLESS_DEVTOOLS=true
```

### Backend (Extension Configuration)
- `OPENAI_API_KEY`: AI writing assistant integration
- `OPENAI_MODEL`: gpt-4.1-mini or compatible model

## Key Dependencies

### TYPO3 Extensions
- typo3/cms-rte-ckeditor: Rich text editor
- typo3/cms-form: Form framework
- typo3/cms-seo: SEO optimization
- pixelcoda/fe-editor: Frontend editing (@dev)
- pixelcoda/content-gsap-animation: Scroll animations (dev-main)
- pixelcoda/typo3-search: Search module (dev-main)

### Node Packages
- next: ^16.2.9
- react/react-dom: ^19.2.7
- @pixelcoda/headless-nextjs: ^1.1.4
- gsap: ^3.12.5
- postcss: ^8.5.15

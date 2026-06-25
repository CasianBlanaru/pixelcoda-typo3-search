# Product Overview

## Project Purpose
PixelCoda TYPO3 Headless Instance - A comprehensive headless CMS solution combining TYPO3 14.3 backend with Next.js 16 frontend, featuring AI-powered search capabilities and frontend editing tools.

## Value Proposition
Provides a modern, decoupled CMS architecture that separates content management from presentation, enabling:
- Flexible content delivery to multiple frontends via JSON API
- Enhanced editor experience with visual frontend editing
- AI-assisted search with vector search capabilities
- Animated content presentations with GSAP integration

## Key Features

### Backend (TYPO3 CMS)
- **TYPO3 14.3** with headless extension (friendsoftypo3/headless)
- **Custom Extensions**:
  - `pixelcoda/fe-editor` - Frontend visual editing capabilities
  - `pixelcoda/typo3-search` - AI-powered search platform with vector search
  - `pixelcoda/content-gsap-animation` - GSAP animation integration for content elements
  - `pixelcoda/sitepackage` - Custom site configuration and templates

### Frontend (Next.js)
- **Next.js 16.2.9** with React 19 for modern UI
- Server-side rendering and static site generation
- Premium skin system with customizable themes
- Headless DevTools for debugging (keyboard shortcut overlay)
- Integration with TYPO3 JSON API

### Search Platform
- AI-assisted search answers
- Vector search capabilities
- API-first architecture
- Multiple workspaces: API, worker, widgets, LLM adapter
- Meilisearch integration

### Development Environment
- **DDEV** for local Docker-based development
- PHP 8.2+ runtime
- Node.js 18+ for frontend and search services

## Target Users

### Content Editors
- Backend: TYPO3 admin interface for content management
- Frontend: Visual editing tools for WYSIWYG experience
- Search: Easy content discovery through AI-assisted search

### Developers
- Headless API for building custom frontends
- Extensible through TYPO3 extensions
- Modern JavaScript/TypeScript tooling
- Docker-based development workflow

### End Users
- Fast, modern web experience via Next.js SSR/SSG
- Enhanced search with AI-powered answers
- Smooth animations and interactions

## Primary Use Cases

1. **Headless CMS Content Delivery** - Deliver structured content via JSON API to Next.js or other frontends
2. **Multi-channel Publishing** - Single content source for web, mobile, and other platforms
3. **AI-Enhanced Content Search** - Semantic search with contextual answers for improved findability
4. **Visual Content Editing** - Frontend editing experience without backend context switching
5. **Animated Web Experiences** - GSAP-powered animations for engaging content presentation

## Technical Architecture
- **Monorepo structure** with TYPO3 backend, Next.js frontend, and search platform
- **API-driven** communication between backend and frontend
- **Composer** for PHP dependency management
- **npm/yarn** workspaces for JavaScript projects
- **Railway/Docker** deployment support

# Product Overview

## Purpose

PixelCoda TYPO3 Headless is a modern, API-first CMS solution that decouples a TYPO3 backend from a Next.js frontend. This architecture enables content management through TYPO3's robust CMS while delivering content via headless JSON APIs to a high-performance React-based frontend.

## Value Proposition

- **Decoupled Architecture**: TYPO3 backend serves as headless CMS, Next.js frontend consumes JSON APIs
- **Modern Frontend**: React 19 and Next.js 16 for optimal performance and developer experience
- **Content Flexibility**: Manage content in TYPO3, render dynamically in Next.js
- **Enhanced Search**: AI-assisted search platform with vector search capabilities via Meilisearch
- **Frontend Editing**: In-place content editing directly on the frontend
- **Animation Support**: GSAP-based content animations for engaging user experiences
- **Developer Tools**: Built-in headless devtools for debugging and development

## Key Features

### TYPO3 Backend
- TYPO3 v14.3 core CMS functionality
- Headless extension (FriendsOfTYPO3/headless) for JSON API output
- Custom extensions for enhanced functionality:
  - pixelcoda_search: Accessible, API-first search with AI assistance
  - pixelcoda_fe_editor: Frontend editing capabilities
  - content_gsap_animation: Animation content elements
  - pixelcoda_sitepackage: Site configuration and templates

### Next.js Frontend
- Server-side rendering with Next.js 16
- React 19 for modern component architecture
- @pixelcoda/headless-nextjs integration package
- GSAP animations support
- Multiple skin support (premium, default)
- Developer overlay (CMD+SHIFT+H / CTRL+SHIFT+H)

### Search Platform
- AI-assisted answers powered by LLM adapters
- Vector search capabilities
- Accessible, API-first architecture
- Workspace-based architecture with multiple apps:
  - API server
  - Worker processes
  - Widgets for integration
  - LLM adapter for AI functionality

## Target Users

- **Content Editors**: Manage content through familiar TYPO3 interface
- **Frontend Developers**: Build modern React applications with TYPO3 content
- **Site Administrators**: Deploy and configure headless CMS infrastructure
- **End Users**: Experience fast, modern web interfaces with enhanced search

## Use Cases

- Corporate websites requiring decoupled CMS
- High-traffic sites needing frontend performance optimization
- Multi-channel content delivery (web, mobile, IoT)
- Projects requiring modern frontend frameworks with enterprise CMS
- Content-heavy sites benefiting from AI-powered search
- Sites requiring in-place frontend editing capabilities

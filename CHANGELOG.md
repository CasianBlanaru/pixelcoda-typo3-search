# Changelog

## 2.2.1 - 2026-06-04

- Replace ambiguous mixed-runtime Railpack detection with explicit Docker
  deployments for TYPO3 and the Search API.
- Add persistent TYPO3 first-deploy setup for Railway MySQL and `/data`.
- Fix CI commands for current ESLint and Composer versions.
- Correct frontend search heading hierarchy for automated accessibility checks.
- Load minified Search assets only when a Search view is rendered.
- Add WebP/lazy-loading guidance plus compressed, immutable static assets for
  the Railway TYPO3 service.
- Make functional tests self-contained with SQLite and modern PHPUnit
  attributes.
- Reach Lighthouse 100 in Performance, Accessibility, Best Practices and SEO
  for the production Search landing page.

## 2.2.0 - 2026-06-04

- Replace the legacy deployment setup with Railway configuration, health
  checks, strict production credentials, CORS allowlists and persistent volume
  guidance.
- Add a persistent local and Railway-ready search API with indexing, search and
  source-grounded AI answer endpoints.
- Index published TYPO3 pages and content elements through the CLI.
- Add an accessible classic frontend search and AI question interface while
  preserving headless configuration data.
- Improve TYPO3 13 and 14 integration, content element registration and
  extension configuration.
- Add current functional coverage for frontend rendering, accessibility,
  headless API configuration and asset loading.

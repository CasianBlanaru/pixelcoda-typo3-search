# Changelog

## 2.2.6 - 2026-06-06

- Remove the hardcoded demo editor password and require
  `PIXELCODA_DEMO_EDITOR_PASSWORD` for Railway demo account creation.
- Fix local and Railway frontend API detection for `localhost`,
  `127.0.0.1`, and `host.docker.internal`.
- Add seeded demo search documents for autocomplete, suggestions,
  pagination, faceted filters, headless output, AI answers and Railway demos.
- Return pagination and facet metadata from the Search API demo endpoint.
- Fix the TYPO3 backend footer override to avoid Fluid array-to-string
  warnings.
- Add a favicon redirect and deployment favicon asset for the Railway demo.

## 2.2.5 - 2026-06-06

- Prevent nested TYPO3 backend frames after Search module actions by using
  tokenized backend module URLs and direct module redirects.
- Recreate TYPO3 runtime cache directories after cache clearing to avoid
  missing temporary cache file errors on Railway.
- Add a non-admin demo editor account during Railway bootstrap for extension
  testing.
- Add a public demo login notice with editor username and role for
  unauthenticated visitors.
- Generate preview site configurations for additional TYPO3 root pages so both
  demo page trees can be previewed.

## 2.2.4 - 2026-06-05

- Redesign the TYPO3 Search administration as a responsive premium dashboard.
- Replace decorative emoji with accessible TYPO3 Core icons.
- Improve rendering mode, API status, configuration and quick-action hierarchy.
- Add automatic light and dark mode styling for the backend module.
- Point public demo links to the working Railway TYPO3 Suite entry page.
- Remove obsolete deployment and migration documents.

## 2.2.3 - 2026-06-05

- Prevent nested TYPO3 backend frames after Search module actions by
  redirecting to the current direct module path.
- Run the persistent Search API inside the Railway TYPO3 service and expose it
  same-origin under `/search-api`.
- Replace production browser requests to `localhost:8787` with the same-origin
  Search API endpoint.
- Remove duplicate inline Search JavaScript and use the tested bundled asset.
- Rebuild public TYPO3 assets after deployment and flush stale imported page
  caches to prevent CSS MIME-type errors.

## 2.2.2 - 2026-06-05

- Restore the Pixelcoda Search backend module on TYPO3 14 using the current
  `ModuleTemplateFactory` rendering API.
- Remove the optional PHP YAML extension requirement by using TYPO3's
  `YamlFileLoader`.
- Test the Search API against its real `/health` endpoint and return actionable
  connection errors.
- Redirect backend actions to the module route to prevent nested backend frames.
- Persist frontend editor ordering through TYPO3 DataHandler move commands and
  announce save results accessibly.

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

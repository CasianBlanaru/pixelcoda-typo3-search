# Performance verification

Verified on 4 June 2026 against the production Search landing page served from
the repository root.

| Lighthouse category | Score |
| --- | ---: |
| Performance | 100 |
| Accessibility | 100 |
| Best Practices | 100 |
| SEO | 100 |

Core metrics:

- First Contentful Paint: 1.0 s
- Largest Contentful Paint: 1.0 s
- Total Blocking Time: 0 ms
- Cumulative Layout Shift: 0

The extension loads its minified CSS and JavaScript through TYPO3's
AssetCollector only when a Search view is rendered. The Railway TYPO3 container
also enables compression and immutable caching for versioned static assets.

Lighthouse is an automated check, not a BITV certification. Manual keyboard,
screen-reader, zoom, high-contrast and editorial-content checks remain required
for each integrating website.

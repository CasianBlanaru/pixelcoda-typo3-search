# Cursor Task: Headless Widgets (A11y)

## Goal
Create accessible search & answer widgets that talk to the API.

## Components
- `SearchBox` with role="search", label, keyboard submit, live region for results count.
- `ResultsList` with role="list" and `article` items, keyboard focus management.
- `AnswerPanel` with citations list; `<section aria-labelledby>` pattern.

## Steps
1. Implement `apps/widgets/src/components/SearchBox.tsx`
2. Implement `apps/widgets/src/components/AnswerPanel.tsx`
3. SDK client in `apps/widgets/src/client.ts` with `search`, `ask`, `suggest`.
4. Export as ESM + types; include README usage snippet.

## Done When
- Local test app renders widgets and they call the API successfully.

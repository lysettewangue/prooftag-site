# Public layout shell

| | |
|--|--|
| **Step** | 124 — Implement the public layout |
| **Parent** | [Tailwind public tokens](../design/tailwind-tokens.md) (123) |
| **Next** | [125 — Implement navigation](public-navigation.md) |
| **Layout** | [`resources/views/components/layouts/public.blade.php`](../../resources/views/components/layouts/public.blade.php) |
| **Test** | [`tests/Feature/PublicLayoutTest.php`](../../tests/Feature/PublicLayoutTest.php) |
| **Date** | 2026-10-27 |
| **Status** | Shared bilingual public shell implemented |

## 1. Structure

All public pages can render inside `<x-layouts.public>`. The shell owns the document-level concerns that must not be duplicated by page templates:

1. HTML language from the active Laravel locale;
2. UTF-8 and responsive viewport declarations;
3. page title, optional description, robots, canonical, and hreflang metadata;
4. Vite public CSS and JavaScript entry points;
5. translated skip link;
6. shared header, main landmark, and shared footer;
7. optional `head` and `scripts` slots for page-specific additions.

The main landmark keeps the stable `#main-content` target and `tabindex="-1"` so the skip link can move keyboard focus to page content.

## 2. Layout API

| Property/slot | Purpose | Default |
|---------------|---------|---------|
| `title` | Complete document title | Application name |
| `description` | Meta description | Omitted when empty |
| `canonical` | Absolute canonical URL | Omitted when empty |
| `hreflang` | Locale-to-absolute-URL map | Empty array |
| `robots` | Robots directive | `index,follow` |
| default slot | Page content inside `<main>` | Required by page |
| `head` slot | Structured data or page-specific head markup | Empty |
| `scripts` slot | Page-specific scripts after the shell | Empty |

The homepage now consumes this API in both French and English. Later controllers may pass the existing `SeoMeta` values without changing the shell.

## 3. Shared regions and step boundaries

- `site-header.blade.php` supplies the official logo, always-visible FR/EN controls, and the [frozen eight-section navigation](public-navigation.md).
- `site-footer.blade.php` supplies the complete corporate, contact, exploration, and legal content from global settings, as documented in [step 126](public-footer.md).
- The homepage now provides the complete bilingual visual narrative recorded in the [public visual refresh](../design/visual-refresh.md). Its final published-content queries and CMS binding still belong to step 130.

This separation makes header and footer improvements global without requiring edits to each public page.

## 4. Responsive and accessible foundation

- The shell uses compact-first gutters and a 1280 px maximum content measure. Full navigation waits until 1280 px; smaller widths use the compact disclosure.
- Header language controls meet the 44 × 44 px target.
- The logo returns to the active locale homepage.
- FR and EN controls expose `hreflang`, `lang`, and `aria-current`.
- Focus uses the approved two-colour ring.
- Smooth scrolling and transitions collapse under `prefers-reduced-motion: reduce`.
- The footer remains after the main content even on short pages through the full-height flex shell.

## 5. Verification

- Vite/Tailwind production build succeeds.
- French and English shell tests cover document language, landmarks, skip link, logo, canonical, hreflang, and translated chrome.
- Existing localisation and design-token tests remain green.
- `git diff --check` remains clean.

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Shared responsive and accessible public layout implemented; navigation step 125 unlocked | 2026-10-27 |

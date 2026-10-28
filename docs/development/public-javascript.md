# Public JavaScript foundation

| | |
|--|--|
| **Step** | 129 — Implement the JavaScript foundation |
| **Parent** | [128 — Lucide](public-lucide.md) · [ADR-003](../architecture/adr-003-blade-not-spa.md) |
| **Next** | 130 — Implement the homepage → [public-homepage.md](public-homepage.md) (COMPLETED) · 131 About Us |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Vanilla first, Alpine available where useful |

Public pages stay server-rendered Blade. JavaScript is progressive enhancement only. The centre directory list, navigation, and forms must remain usable with scripts disabled.

## 1. Stack

| Piece | Role |
|-------|------|
| Vite entry `resources/js/app.js` | Loads bootstrap, public boot, Alpine |
| Axios | CSRF-aware `XMLHttpRequest` helper for later public forms |
| Vanilla modules in `resources/js/public/` | Header scroll state, mobile menu Escape / outside / wide close |
| Alpine.js | Started globally for selective widgets (filters, richer disclosures later) |

No Inertia, Vue, React, or public SPA router.

## 2. Boot order

1. `bootstrap.js` — Axios + CSRF token from `<meta name="csrf-token">`
2. `public/boot.js` — vanilla enhancements; sets `document.documentElement.dataset.publicJs = "ready"`
3. `public/alpine.js` — `window.Alpine = Alpine` then `Alpine.start()`

Register `Alpine.data(...)` in a page module **before** `startAlpine()` if a later step needs a named component. For Blade-only Alpine snippets, `x-data` on the markup is enough after start.

## 3. Header enhancements

Hooks:

| Attribute | Element |
|-----------|---------|
| `data-public-header` | Sticky shell header |
| `data-public-mobile-nav` | Compact `<details>` menu |
| `data-scrolled` | Set by JS when `scrollY > 8` |
| `data-public-js="ready"` | On `<html>` after boot |

Behaviours (vanilla):

- Toggle scrolled styling for a clearer sticky bar
- Close the mobile disclosure on Escape, pointer outside, or when the `wide` breakpoint matches
- Skip motion-related markers when `prefers-reduced-motion: reduce`

The menu remains a native `<details>` so it works without JavaScript.

## 4. Alpine policy

Use Alpine when it clearly improves a control that would be awkward in plain HTML (for example centre filters at step 138). Prefer native elements for disclosure, forms, and links. Do not mount a client-side page router.

## 5. Layout contract

The public layout always includes:

- CSRF meta for Axios / future form posts
- `@vite(['resources/css/app.css', 'resources/js/app.js'])`

## 6. Out of this step

| Item | Step |
|------|------|
| Homepage CMS queries | 130 (done) |
| Centre map / Leaflet | 138 (done — see [public-centre-directory.md](public-centre-directory.md)) |
| Contact form UX polish | 144 |

## 7. Verification

```powershell
npm run lint
npm run build
php artisan test --filter=PublicJavaScriptTest
php artisan test --filter=PublicLayoutTest
```

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Public JavaScript foundation implemented — vanilla first, Alpine available (step 129 executed) | 2026-10-27 |

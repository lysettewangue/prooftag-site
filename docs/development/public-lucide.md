# Public Lucide icons

| | |
|--|--|
| **Step** | 128 — Integrate Lucide |
| **Parent** | [127 — Shared components](public-components.md) |
| **Next** | 129 — [public-javascript.md](public-javascript.md) · 130 — [public-homepage.md](public-homepage.md) (COMPLETED) · 131 About Us |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Public icon set available in Blade |

Admin Filament continues to use Heroicons. The public site uses **Lucide only**.

## 1. Approach

| Piece | Role |
|-------|------|
| `lucide-static` (devDependency) | Upstream SVG source |
| `npm run icons:sync` | Copies the curated allowlist into the repo |
| `resources/svg/lucide/*.svg` | Committed icons used at runtime (no `node_modules` on LWS) |
| `App\Support\LucideIcon` | Safe loader: kebab-case names only, default stroke **2.25** |
| `<x-public.icon name="…" />` | Blade tag for templates |
| `.public-icon-well` | Azure/cyan gradient square wells for stronger public icons |

Icons are inlined as SVG with `currentColor`, so they inherit text colour from the parent control.

## 2. Blade usage

```blade
<x-public.icon name="search" :size="18" class="text-brand-royal" />

<x-public.link href="https://example.com" external>
    Site partenaire
</x-public.link>
```

| Prop | Default | Notes |
|------|---------|-------|
| `name` | required | Lowercase kebab-case Lucide id from the allowlist |
| `size` | `20` | Sets width and height (design system: 20 or 24) |
| `class` / attributes | — | Passed through to the `<svg>` |

Decorative icons are `aria-hidden="true"` by default. Icon-only buttons must keep a visible or screen-reader name on the control (header search and menu already do).

## 3. Where Lucide is wired

| Surface | Icons |
|---------|-------|
| Header | `phone`, `search`, `menu`, `x` |
| Link (`external`) | `external-link` |
| Alert | `info`, `circle-check`, `triangle-alert`, `circle-alert` |
| Empty state | `inbox` |
| Pagination | `chevron-left`, `chevron-right` |
| Homepage / About | curated set incl. `layers`, `clipboard-check`, `cpu`, `database`, `fingerprint`, `gauge`, `car-front`, `sparkles`, `badge-check`, … |
| Link / chrome | `arrow-right`, `phone`, `search`, `menu`, `x`, … |

Adding a glyph: append the Lucide name to `scripts/sync-lucide-icons.mjs`, run `npm run icons:sync`, then use `<x-public.icon>`.

## 4. Out of this step

| Item | Step |
|------|------|
| Alpine / vanilla JS behaviours | 129 → [public-javascript.md](public-javascript.md) |
| CMS-bound homepage content | 130 (done) |
| Expertise `icon` field rendering on public pages | 134 |

## 5. Verification

- `npm run icons:sync` — curated SVGs + manifest (see `scripts/sync-lucide-icons.mjs`)
- `php artisan test --filter=PublicLucideTest`
- `php artisan test --filter=PublicComponentsTest`
- `php artisan test --filter=PublicNavigationTest`

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Lucide integrated as the public icon set (step 128 executed) | 2026-10-27 |

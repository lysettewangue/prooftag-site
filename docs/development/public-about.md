# Public About Us templates

| | |
|--|--|
| **Step** | 131 — Implement About Us |
| **Parent** | [130 — Homepage](public-homepage.md) · [corporate-entities.md](../database/corporate-entities.md) |
| **Next** | 132 — Solutions index → [public-products-index.md](public-products-index.md) (COMPLETED) · 133 product detail |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | CMS-bound About section · careers → [public-careers.md](public-careers.md) (COMPLETED) |

Five frozen About templates reuse the public shell, page hero, breadcrumbs, and content-block renderer. Careers index/detail landed in step **143**.

## 1. Routes

| `PageCode` | FR | EN | Route name |
|------------|----|----|------------|
| `about` | `/a-propos` | `/en/about` | `{locale}.about` |
| `organisation` | `/a-propos/organisation` | `/en/about/organisation` | `{locale}.about.organisation` |
| `history` | `/a-propos/historique` | `/en/about/history` | `{locale}.about.history` |
| `team` | `/a-propos/equipe` | `/en/about/team` | `{locale}.about.team` |
| `partners` | `/a-propos/partenaires` | `/en/about/partners` | `{locale}.about.partners` |
| `careers` | `/a-propos/carrieres` | `/en/about/careers` | `{locale}.about.careers` |

Unpublished (draft) pages return **404**. Seeds remain Draft by design. Careers detail: [public-careers.md](public-careers.md).

## 2. Data

| Source | Query | Rule |
|--------|-------|------|
| `Page` by code | `CorporatePageQuery` → cached `pages.{code}.{locale}` (id only) | Must be publicly visible in the active locale |
| Blocks | `PageTranslation.blocks` | Rendered by `<x-public.content-blocks>`; empty slots omitted (H-07) |
| Team members | `TeamCatalog` | Team page only; empty → W11-style empty state (VER-02) |
| Partners | `PartnerCatalog` | Partners page only; empty → same pattern (VER-09) |
| Careers | `CareerCatalog` | Careers page only; empty → W11 / P-11 + contact CTA |

`team` / `partners` / careers **blocks** are intro copy only. Listings come from the collections.

## 3. SEO & chrome

- Document meta via `SeoMetaResolver::forModel`
- Breadcrumbs: hub alone; children = About → current
- Section aside lists **published** siblings (organisation, history, team, partners, careers)
- Primary nav keeps `about` active on all `/a-propos/*` and `/en/about/*` paths

## 4. VER gates

| Item | Behaviour |
|------|-----------|
| VER-12 / VER-13 | Do not invent About / organisation launch copy in seeds |
| VER-02 / VER-09 | Empty team / partners listings until verified rows are Published |
| Statistics block | Omitted when `items` empty — no live counters |

## 5. Verification

```powershell
php artisan test --filter=PublicAboutCmsTest
php artisan cache:clear
```

## 6. Acceptance

- [x] Five About routes FR/EN match frozen slugs
- [x] Draft → 404; published → 200 with shell + SEO
- [x] Content blocks render; empty / statistics-empty omitted
- [x] Team / partners empty states without invented people or logos
- [x] Breadcrumbs + about nav active on children
- [x] Careers → [public-careers.md](public-careers.md)

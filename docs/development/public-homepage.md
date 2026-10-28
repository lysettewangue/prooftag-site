# Public homepage (CMS-bound)

| | |
|--|--|
| **Step** | 130 — Implement the homepage |
| **Parent** | [129 — JavaScript foundation](public-javascript.md) · [W02 / HF02](../design/wireframes.md) |
| **Next** | 131 — Implement About Us → [public-about.md](public-about.md) (COMPLETED) · 132 solutions index |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Bound to published CMS rows |

The homepage keeps the visual narrative from the [visual refresh](../design/visual-refresh.md). CMS binding adds published product cards (0–5), published expertise title cards, and `SeoMeta` from the `home` page when that page is publicly visible.

## 1. Data

| Source | Query | Rule |
|--------|-------|------|
| Page `home` | `HomePageQuery` → cached `pages.home.{locale}` | Optional. SEO meta only when Published + due `published_at` + locale translation. Hero copy stays in `lang/*/ui.php` until VER-01 closes. |
| Products | `ProductCatalog::publishedForLocale` | Max **5** cards, `sort_order`, active locale. Drafts never render as ghost cards. |
| Expertises | `ExpertiseCatalog::publishedForLocale` | Title-only cards, `sort_order`, active locale. Empty state when none published. |

`ContentCache::flush()` clears product, expertise, and home page keys (already called from publication and translate actions). Catalog caches store **ids only** (not Eloquent models) so the file cache store cannot resurface `__PHP_Incomplete_Class` on the homepage.

## 2. Paths

`config/cms.php` path prefix `expertise`: FR `/expertises`, EN `/expertise`. `PublicPath::for(Expertise)` builds `/expertises/{slug}` and `/en/expertise/{slug}`. Product paths unchanged. Detail routes land in steps 133–134; the homepage already emits the reserved URLs.

## 3. Empty states (W02b)

When no product is published:

- Show the empty message (no placeholder cards)
- Keep Contrôle technique + Contact CTAs in that band
- Hero CTAs stay as-is

Draft seeds leave both product and expertise bands empty by design.

## 4. SEO

| Case | Title / description |
|------|---------------------|
| `home` page published with `meta_*` | Use those values via `SeoMetaResolver::forModel` |
| Otherwise | `ui.home_heading` / `ui.home_body` with canonical + hreflang for `/` and `/en` |

No exclusive-operator claim (VER-01 Open). No live counters.

## 5. Verification

```powershell
php artisan test --filter=PublicHomeCmsTest
php artisan test --filter=PublicHomeVisualTest
```

## 6. Acceptance

- [x] Homepage FR/EN loads with shell + visual narrative
- [x] Published products appear; drafts do not
- [x] Published expertises appear as title cards; empty state when none
- [x] SEO meta from published `home` page when present
- [x] VER-01 exclusive claim absent

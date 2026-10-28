# Public sitemap and robots

| | |
|--|--|
| **Step** | 149 — Implement sitemap and robots |
| **Parent** | [148 — SEO output](public-seo.md) · [SitemapQuery](local-backend.md) · A-14 |
| **Next** | 150 — Review the public frontend → [public-frontend-gate.md](public-frontend-gate.md) (COMPLETED) · 151 migrate corporate |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | `/sitemap.xml` and `/robots.txt` served from CMS rules |

## 1. Routes

| Path | Name | Notes |
|------|------|-------|
| `/sitemap.xml` | `sitemap` | Locale-agnostic; absolute locs on `cms.canonical_host` |
| `/robots.txt` | `robots` | Dynamic (static `public/robots.txt` removed) |

Admin, downloads, and draft content are out of the sitemap.

## 2. Sitemap contents

| Source | Rule |
|--------|------|
| Indexes | Home, products, expertises, CT security points, equipment, centres, resources, news, search — FR + EN always |
| Pages | Published pages with a translation for that locale (About, contact, legal, CT hub, …) |
| Products / expertises / equipment / posts / resources / careers | `publiclyVisible` + locale translation |
| Centres | `publiclyListed` (Active / verified) + locale |
| Alternates | `xhtml:link` hreflang pairs + `x-default` → French when present |
| Cache | `seo.sitemap` via `ContentCache` (flushed on publish/write) |

Hidden / draft products stay reserved in routing but **omitted** here.

## 3. robots.txt

| Env | Body |
|-----|------|
| Indexable (`cms.robots` without `noindex`) | `Disallow: /admin` (+ `/admin/`); `Sitemap:` canonical URL |
| Local / staging (`noindex`) | `Disallow: /`; still lists `Sitemap:` |

Override with `CMS_ROBOTS` when needed.

## 4. Implementation

| Piece | Role |
|-------|------|
| `App\Queries\SitemapQuery` | URL list + alternates |
| `App\Support\RobotsTxt` | Plain-text body |
| `SitemapController` / `RobotsController` | HTTP responses |
| `resources/views/seo/sitemap.blade.php` | XML urlset |

## 5. Manual check

1. Open `/sitemap.xml` — valid XML; locs use `https://www.prooftagcatis.com`; published product appears; draft product does not.
2. Open `/robots.txt` locally — `Disallow: /` and Sitemap line present.
3. After publishing a centre/product, cache refresh (or wait TTL) shows the new loc.
4. Admin paths never appear as `<loc>`.

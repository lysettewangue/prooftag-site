# Public SEO output

| | |
|--|--|
| **Step** | 148 — Implement SEO output |
| **Parent** | [147 — Language switch](public-language-switch.md) · A-14 / [seo-entities.md](../database/seo-entities.md) |
| **Next** | 149 — Sitemap and robots → [public-sitemap-robots.md](public-sitemap-robots.md) (COMPLETED) · 150 → [public-frontend-gate.md](public-frontend-gate.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Title, description, canonical, hreflang, Open Graph, Twitter cards in public `<head>` |

## 1. Behaviour

| Tag | Source |
|-----|--------|
| `<title>` / `og:title` / `twitter:title` | `SeoMeta::$title` (CMS `meta_title` or record title / UI copy) |
| `meta description` / OG / Twitter | `SeoMeta::$description` |
| `link rel=canonical` / `og:url` | `SeoMeta::$canonical` on `cms.canonical_host` |
| `hreflang` + `x-default` | Locale pairs from resolver; `x-default` = French URL when present |
| `og:image` / `twitter:image` | Model `images` media → Settings logo → `images/brand/logo.png` |
| `og:type` | `article` for `Post`, else `website` |
| `og:locale` / alternate | `fr_FR` / `en_GB` from hreflang keys |
| `meta robots` | `config('cms.robots')` — production `index,follow`; local/staging `noindex,nofollow` unless `CMS_ROBOTS` |

## 2. Implementation

| Piece | Role |
|-------|------|
| `App\Services\Seo\SeoMeta` | Value object: title, description, canonical, hreflang, image, type |
| `App\Services\Seo\SeoMetaResolver` | `forPage` / `forLocalizedIndex` / `forModel` |
| `App\Support\OpenGraph` | Absolute URLs, locale tags, robots, fallback image |
| `config/cms.php` | `canonical_host`, `robots` |
| `layouts/public` | Emits all head tags; Twitter `summary_large_image` when image present |

Public views pass `:title`, `:description`, `:canonical`, `:hreflang`, `:image`, `:type` from `$seo`.

## 3. Manual check

1. View source on `/` and `/en` — title, description, canonical, `hreflang` fr/en + `x-default`, OG + Twitter present.
2. Open a published news article — `og:type` is `article`; image is media or brand logo.
3. Local env — `meta name="robots"` is `noindex,nofollow`.
4. Canonical host is always `https://www.prooftagcatis.com` (not `APP_URL`).

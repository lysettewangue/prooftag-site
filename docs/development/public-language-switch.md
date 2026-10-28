# Public language switching

| | |
|--|--|
| **Step** | 147 — Implement language switching |
| **Parent** | [146 — Search](public-search.md) · P-09 / H-10 / W07b |
| **Next** | 148 — SEO output → [public-seo.md](public-seo.md) (COMPLETED) · 149 sitemap |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | FR ↔ EN targets equivalent content; no invented paths |

## 1. Behaviour

| Rule | Detail |
|------|--------|
| Source | Page `hreflang` (from `SeoMeta` / `SeoMetaResolver`) |
| Available locale | Relative path of the equivalent URL (detail slug or index pair) |
| Missing locale | Target that locale’s **home** (`/` or `/en`) — never invent a slug (P-09 / H-10) |
| Chrome | Equal visual weight FR \| EN; `title` explains fallback when translation missing |
| Body | Still no cross-locale content fallback (111) |

## 2. Index path pairs

`SeoMetaResolver::forLocalizedIndex` builds correct FR/EN hreflang for indexes with different segments (products, expertises, centres, CT equipment/points, etc.). Model pages keep `forModel` (independent slugs).

## 3. Implementation

| Piece | Role |
|-------|------|
| `App\Support\LocaleSwitcher` | Absolute/relative hreflang → switch URLs + availability |
| `layouts/public` → `site-header` | Passes `:hreflang` |
| Header utility strip | `data-locale-switcher` links |

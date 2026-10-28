# Public solutions index

| | |
|--|--|
| **Step** | 132 — Implement the solutions index |
| **Parent** | [131 — About Us](public-about.md) · [solution-entities.md](../database/solution-entities.md) |
| **Next** | 133 — Product detail → [public-product-detail.md](public-product-detail.md) (COMPLETED) · 134 expertise |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Listing query (not a `Page`, not a Product row) |

`/produits` and `/en/products` list **published** products for the active locale in `sort_order`. Drafts never appear as ghost cards. Detail templates land in step **133**; this index already emits reserved `PublicPath` URLs.

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/produits` | `fr.products.index` |
| EN | `/en/products` | `en.products.index` |

## 2. Data

| Source | Query | Rule |
|--------|-------|------|
| Products | `ProductCatalog::publishedForLocale` via `ProductIndexQuery` | Visible + locale translation; max five codes in the catalogue |
| SEO | `SeoMetaResolver::forPage` + `ui.products.meta_*` | Canonical + hreflang pair for the index paths |

Empty published set → W02b-style empty state with Contrôle technique CTA + Contact link.

## 3. Chrome

- Page hero: eyebrow, H1, lede from `lang/*/ui.php` (`ui.products`)
- Cards: `public_name`, optional summary, link to product path
- Primary nav marks **products** active on the index (and later on detail paths under the same prefix)

## 4. Out of this step

| Item | Step |
|------|------|
| Product detail body / related expertises | 133 (done) |
| Expertise index | 134 |

## 5. Verification

```powershell
php artisan test --filter=PublicProductsIndexTest
```

## 6. Acceptance

- [x] FR/EN index routes return 200
- [x] Draft seeds → empty state, no ghost cards, no DITROS-CT
- [x] Published products appear in `sort_order` with reserved detail URLs
- [x] Missing EN translation hides the product on `/en/products` only
- [x] Nav active on `/produits`

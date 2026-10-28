# Public product detail

| | |
|--|--|
| **Step** | 133 — Implement the product detail template |
| **Parent** | [132 — Solutions index](public-products-index.md) · [W03](../design/wireframes.md) |
| **Next** | 134 — Expertise → [public-expertise.md](public-expertise.md) (COMPLETED) · 135 technical inspection |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | One layout for all five product codes |

`/produits/{slug}` and `/en/products/{slug}` use a single Blade template. Unpublished products (and missing locale translations) return **404**. DITROS-CT is not a product and has no route.

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/produits/{slug}` | `fr.products.show` |
| EN | `/en/products/{slug}` | `en.products.show` |

Slugs are the frozen fragments (`certidocs-ct`, `certidocs-ct-reporting`, `ct-verif`, `doser`, `video-enforcement`).

## 2. Template (W03)

| Zone | Source |
|------|--------|
| Breadcrumb | Index → `public_name` |
| H1 | Branded `public_name` (not translated) |
| Summary | Translation `summary` |
| Optional media | First item in Spatie `images` collection |
| Body | Translation `body.html` (CMS HTML) |
| Outbound CTA | `external_url` + `external_label` (or default chrome). Omitted when URL empty — no fake “open app” button |
| Related expertises | Published linked `Expertise` rows only |
| Related resources | Published linked `Resource` rows only |

SEO via `SeoMetaResolver::forModel`. Primary nav keeps **products** active under the `/produits` · `/en/products` prefix.

## 3. VER / safety

- Never render DITROS-CT
- Do not invent capability bullets beyond CMS body
- CT-VERIF / DOSER may show the outbound CTA only when `external_url` is set

## 4. Verification

```powershell
php artisan test --filter=PublicProductShowTest
```

## 5. Acceptance

- [x] All five codes render when Published + locale translation exists
- [x] Draft / missing locale → 404
- [x] External CTA only when `external_url` is filled
- [x] Related expertises / resources omit unpublished rows
- [x] Shared template; no per-product Blade forks

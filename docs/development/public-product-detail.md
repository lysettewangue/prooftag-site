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
| Back + eyebrow | Compact return link, then cyan “Solution · domaine” |
| Two-column first block | Left: H1, lede, domain/access, CTA, overview paragraphs + related links |
| | Right: compact product media + numbered highlights panel (fills the former empty column) |
| Other solutions | Numbered sibling cards below |

`ProductShowQuery` exposes `paragraphs` + `highlights` via `PublicEditorialCopy`. Empty body → prepared state. SEO via `SeoMetaResolver::forModel`. Spacing: `.public-page-start`.

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

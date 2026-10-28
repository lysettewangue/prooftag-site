# Public careers

| | |
|--|--|
| **Step** | 143 — Implement careers |
| **Parent** | [142 — Article page](public-article.md) · [corporate-entities.md](../database/corporate-entities.md) · W11 / P-11 |
| **Next** | 144 — Contact → [public-contact.md](public-contact.md) (COMPLETED) · 145 → [public-legal.md](public-legal.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Page intro + listing; detail; empty listing valid |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/a-propos/carrieres` | `fr.about.careers` |
| EN | `/en/about/careers` | `en.about.careers` |
| FR | `/a-propos/carrieres/{slug}` | `fr.careers.show` |
| EN | `/en/about/careers/{slug}` | `en.careers.show` |

Index is the published `Page` `careers` (CorporatePageController) plus `CareerCatalog` rows. Detail is `CareerShowController`.

## 2. Behaviour

| Zone | Rule |
|------|------|
| Page visibility | Draft careers **page** → **404** (same as other About pages) |
| Listing | Published careers only; empty set = W11 empty state + contact CTA (not 404) |
| Row | Title → kind · optional close date / “closed” → link to detail |
| Detail | Breadcrumb About → Careers → title; body `{html}`; closed = no apply CTA |
| Apply | `application_url` when open; else contact form link; never invent an inbox |
| SEO | Index: page `SeoMetaResolver::forModel`; detail: career model |

## 3. Notes

- Career is not a Post / Product / Resource.
- No career seeder; empty launch is valid (P-11).
- `closes_at` past → still listed as closed; not soft-deleted.

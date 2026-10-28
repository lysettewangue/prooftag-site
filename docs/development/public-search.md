# Public search

| | |
|--|--|
| **Step** | 146 — Implement public search |
| **Parent** | [145 — Legal pages](public-legal.md) · [SiteSearch](local-backend.md) · P-13 / ADR-004 |
| **Next** | 147 — Language switching → [public-language-switch.md](public-language-switch.md) (COMPLETED) · 148 → [public-seo.md](public-seo.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | MySQL `LIKE` over published products, posts, resources, Active centres |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/recherche` | `fr.search` |
| EN | `/en/search` | `en.search` |

Query string: `?q=` (optional). Header search icon already targets these paths.

## 2. Behaviour

| Rule | Detail |
|------|--------|
| Engine | `SiteSearch` — MySQL `LIKE`, no Meilisearch/Elastic (ADR-004) |
| Scope | Published products / posts / resources; Active centres only |
| Cap | Up to 10 hits per group |
| Empty `q` | Form + idle hint; no empty result list |
| No matches | 200 + empty state + reset — **not** 404 |
| Groups | Solutions → Actualités → Centres → Ressources (omit empty groups) |
| Drafts | Never appear |
| SEO | Fixed meta + FR/EN hreflang pair |

## 3. Notes

- Expertise / equipment / careers / pages are out of V1 site search (same as step 084).
- Escapes `%`, `_`, `\` in the user term before `LIKE`.

# Public resource library

| | |
|--|--|
| **Step** | 140 — Implement the resource library |
| **Parent** | [139 — Centre detail](public-centre-detail.md) · [resource-entities.md](../database/resource-entities.md) · W06 / HF05 |
| **Next** | 141 — News listing → [public-news-listing.md](public-news-listing.md) (COMPLETED) · 142 → [public-article.md](public-article.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Index with status filter + pagination; detail; controlled PDF download |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/ressources` | `fr.resources.index` |
| EN | `/en/resources` | `en.resources.index` |
| FR | `/ressources/{slug}` | `fr.resources.show` |
| EN | `/en/resources/{slug}` | `en.resources.show` |
| FR | `/ressources/{slug}/telecharger` | `fr.resources.download` |
| EN | `/en/resources/{slug}/download` | `en.resources.download` |

Query string on index: `?status=current|historical|archive` · `page=` (pagination).

## 2. Behaviour

| Zone | Rule |
|------|------|
| Visibility | Published + locale translation only (`ResourceLibrary`) |
| Status filter | Optional; invalid value ignored; empty filtered = 200 + reset (not 404) |
| Row | Title → **status as text** (not colour-only) → optional summary → download or muted “no file” |
| Download | `DownloadResource` only when a `documents` media file exists; otherwise no download control |
| Detail | Breadcrumb library → title; authority / issued_on when present; same download rule |
| SEO | Index: fixed meta + FR/EN hreflang pair; detail: `SeoMetaResolver::forModel` |

## 3. Notes

- `regulatory_status` `archive` is a **public label**, not soft-delete.
- Missing PDF cannot look like an active download (HF05).
- Binaries stay on the `documents` disk; no public dump of every PDF.

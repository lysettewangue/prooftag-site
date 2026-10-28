# Public news listing

| | |
|--|--|
| **Step** | 141 — Implement the news listing |
| **Parent** | [140 — Resource library](public-resource-library.md) · [editorial-entities.md](../database/editorial-entities.md) · W07 / W07c |
| **Next** | 142 — Article page → [public-article.md](public-article.md) (COMPLETED) · 143 → [public-careers.md](public-careers.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Published posts only; kind / category filters; empty listing valid |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/actualites` | `fr.news.index` |
| EN | `/en/news` | `en.news.index` |

Query string: `?kind=news|article` · `?category=` (published category id) · `page=` (pagination).

Detail URLs (`/actualites/{slug}` · `/en/news/{slug}`) → [public-article.md](public-article.md).

## 2. Behaviour

| Rule | Detail |
|------|--------|
| Visibility | Published + locale translation only (`PostIndex::paginatePublic`) |
| Drafts | Never listed (sample 110 rows stay Draft until editorial publish) |
| Kind filter | Optional; invalid value ignored |
| Category filter | Only **published** categories for the locale; invalid id ignored |
| Empty catalog (W07c) | H1 + neutral empty message — **no** filter form, **no** pagination |
| Empty filtered | 200 + empty state + reset — not 404 |
| Row | Title → category · kind · date → summary |
| SEO | Fixed meta + FR/EN hreflang pair |

## 3. Notes

- Categories unpublished hide from filters and from row metadata.
- Career is not a Post.

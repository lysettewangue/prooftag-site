# Public article page

| | |
|--|--|
| **Step** | 142 — Implement the article page |
| **Parent** | [141 — News listing](public-news-listing.md) · W07b |
| **Next** | 143 — Careers → [public-careers.md](public-careers.md) (COMPLETED) · 144 → [public-contact.md](public-contact.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Published post detail; independent FR/EN slugs; related products / expertises secondary |

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/actualites/{slug}` | `fr.news.show` |
| EN | `/en/news/{slug}` | `en.news.show` |

## 2. Behaviour

| Zone | Rule |
|------|------|
| Visibility | Published + locale translation; else **404** (drafts / wrong locale slug) |
| Chrome | Breadcrumb listing → title; H1; category · kind · date; optional summary |
| Hero | Optional `images` media; omitted when absent |
| Body | Single reading column from `{html}` body; empty body → quiet empty state (not 404) |
| Related | Published linked products / expertises only; aside omitted when both empty |
| Author | Not shown (`author_id` is not a public byline) |
| SEO | `SeoMetaResolver::forModel` — hreflang only for locales that have a translation (missing EN → no phantom `/en/news/…`) |

## 3. Notes

- FR and EN slugs are independent.
- Career is not a Post.

# Public legal pages

| | |
|--|--|
| **Step** | 145 — Implement legal pages |
| **Parent** | [144 — Contact](public-contact.md) · [corporate-entities.md](../database/corporate-entities.md) · W09 |
| **Next** | 146 — Public search → [public-search.md](public-search.md) (COMPLETED) · 147 → [public-language-switch.md](public-language-switch.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Mentions légales + privacy; narrow reading measure; CMS blocks |

## 1. Routes

| `PageCode` | FR | EN | Name |
|------------|----|----|------|
| `legal_notice` | `/mentions-legales` | `/en/legal-notice` | `{locale}.legal.notice` |
| `privacy` | `/politique-de-confidentialite` | `/en/privacy-policy` | `{locale}.legal.privacy` |

Footer links already point at these slugs via `PublicFooter`.

## 2. Behaviour

| Zone | Rule |
|------|------|
| Visibility | Published `Page` for the code + locale; else **404** |
| Layout | Single H1 → CMS blocks in a narrow column (`max-w-reading` ≈ 42.5rem) |
| Typography | `font-reading` (Source Serif 4) for body; headings stay sans |
| Empty blocks | Quiet empty state (not 404) while the page itself is Published |
| Cards | Legal body is not cardified; sections come from ordered CMS blocks / headings |
| SEO | `SeoMetaResolver::forModel` |

## 3. Notes

- Prefer `rich_text` (and similar) blocks for long regulatory copy.
- Cookie banner stays out of scope unless non-essential tracking is added later.

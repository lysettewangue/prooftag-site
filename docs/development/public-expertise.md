# Public expertise index and detail

| | |
|--|--|
| **Step** | 134 — Implement expertise |
| **Parent** | [133 — Product detail](public-product-detail.md) · [expertise-entities.md](../database/expertise-entities.md) |
| **Next** | 135 — CT hub → [public-technical-inspection.md](public-technical-inspection.md) (COMPLETED) · 136 security points |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Listing query + shared detail template |

Five frozen expertise domains. Index is **not** a Page. Titles must not copy product names. Seeds stay Draft → public URLs 404 until Published.

## 1. Routes

| Locale | Index | Detail |
|--------|-------|--------|
| FR | `/expertises` (`fr.expertises.index`) | `/expertises/{slug}` (`fr.expertises.show`) |
| EN | `/en/expertise` (`en.expertises.index`) | `/en/expertise/{slug}` (`en.expertises.show`) |

EN uses the singular prefix `expertise` (sitemap / `cms.path_prefixes`).

## 2. Behaviour

| Surface | Rule |
|---------|------|
| Index | Mesh hero (lede + snapshot + hub visual), then featured + grid cards with domain images |
| Empty index | Empty state + CTA solutions |
| Detail | Two-column first block: copy + overview left; compact media + numbered highlights right; related products; sibling domains |
| Visuals | Static assets via `App\Support\ExpertiseVisual` (`public/images/content/expertise/` + `icons/`) |
| Copy | Sanitized via `PublicEditorialCopy`; aligned to site.prooftagcatis.com/expertise/ |
| Draft / missing locale | **404** |

## 3. Manual verification

See the checklist in the step completion notes / chat for browser checks after publishing rows in `/admin/expertises`.

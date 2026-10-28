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
| Index | Published expertises in `sort_order`; title-first cards; summary optional (H-03) |
| Empty index | Empty state + CTA solutions / contact |
| Detail | Breadcrumb → H1 title → summary → body HTML → related **published** products |
| Draft / missing locale | **404** |

## 3. Manual verification

See the checklist in the step completion notes / chat for browser checks after publishing rows in `/admin/expertises`.

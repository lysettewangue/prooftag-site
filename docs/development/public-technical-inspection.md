# Public Contrôle technique hub

| | |
|--|--|
| **Step** | 135 — Implement technical inspection |
| **Parent** | [134 — Expertise](public-expertise.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) · W04 |
| **Next** | 136 — [Security points](public-security-points.md) (COMPLETED) · 137 Equipment |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-30 |
| **Status** | Single premium hub page (not a product). No DITROS-CT |

`/controle-technique` · `/en/technical-inspection` render Page `technical_inspection` when Published. The hub carries the full journey on **one page**: process, modernised schema, twelve points with icons, and equipment with images + copy. Dedicated points / equipment indexes remain available for deep links.

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/controle-technique` | `fr.technical-inspection.hub` |
| EN | `/en/technical-inspection` | `en.technical-inspection.hub` |

Draft page → **404**.

## 2. Content (single page)

| Zone | Source |
|------|--------|
| Title / SEO | Page translation + `SeoMetaResolver::forModel` |
| Hero | Title + lede + hub visual + in-page anchors |
| Process | Official www 3-step flow (centre → MINT → field) + role sentence; matching images only |
| Schema | Modernised 12-step diagram (`schema-12-points.jpg`) from the official www process chart |
| 12 points | All published `SecurityPoint` rows + Lucide icon per function (`TechnicalInspectionVisual::pointIcon`) |
| Equipment | All published equipment with image (CMS media or static fallback) + full body HTML |
| Visuals | `public/images/content/technical-inspection/` (`hub`, `process/`, `equipment/`, `icons/`) |

## 3. Out of this step

| Item | Step |
|------|------|
| Dedicated security-points index | 136 (done) |
| Equipment index + detail | 137 (done) |

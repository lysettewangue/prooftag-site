# Public Contrôle technique hub

| | |
|--|--|
| **Step** | 135 — Implement technical inspection |
| **Parent** | [134 — Expertise](public-expertise.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) · W04 |
| **Next** | 136 — [Security points](public-security-points.md) (COMPLETED) · 137 Equipment |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Hub page (not a product). No DITROS-CT |

`/controle-technique` · `/en/technical-inspection` render Page `technical_inspection` when Published. The hub also shows published security-point accordions and an equipment preview. Dedicated points / equipment indexes land in steps **136** / **137** (links already reserved).

## 1. Routes

| Locale | Path | Name |
|--------|------|------|
| FR | `/controle-technique` | `fr.technical-inspection.hub` |
| EN | `/en/technical-inspection` | `en.technical-inspection.hub` |

Draft page → **404**.

## 2. Content

| Zone | Source |
|------|--------|
| Title / SEO | Page translation + `SeoMetaResolver::forModel` |
| Overview | Page `blocks` (admin CT overview → `rich_text`) via `<x-public.content-blocks>` |
| 12 points | `SecurityPointCatalog` — numbered accordion, readable titles (not all-caps) |
| Equipment teaser | Up to 4 published `Equipment` rows (current first) |
| Teaser links | Points index (**136**) · equipment index (**137**) — both done |

## 3. Out of this step

| Item | Step |
|------|------|
| Dedicated security-points index | 136 (done) |
| Equipment index + detail | 137 (done) |

# Technical inspection entities

| | |
|--|--|
| **Step** | 043 — Define technical inspection entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [corporate-entities.md](corporate-entities.md) (040) |
| **Next** | 089 — Solutions admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Hub content, twelve points, and equipment specified |

This is the step 043 deliverable. Domain: `App\Domain\TechnicalInspection`. Hub **overview** is Page `technical_inspection` (040). This step adds **SecurityPoint** and **Equipment** only. Not a product. No live CT processing. No DITROS-CT type. **No migrations before gate 055.**

Starting copy for the twelve points is the www list (extracts 2026-10-20). Public UI is numbered components, **not** all-caps (AUD-001). Rewrite before Publish.

## 1. SecurityPoint

Exactly **twelve** seeded rows. Staff cannot add a thirteenth or delete one. Reorder within 1–12 is an admin action (step 092); `sort_order` stays unique 1–12.

No public per-point slug in the sitemap. The index `/controle-technique/points-de-securisation` · `/en/technical-inspection/security-points` is a listing query.

### Base

| Attribute | Rule |
|-----------|------|
| `sort_order` | Integer **1–12**, unique. Identity. |
| Publication | Shared A-09 on the base. Unpublished points omit from the public list but the slot remains. |

### Translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `title` | Short heading (not shouty). |
| `body` | Explanation. Do not invent MINT/GIEGLAN behaviour beyond verified rewrite. |

No `slug`. Order is `sort_order`.

## 2. Equipment

Current vs **legacy**. Do not hard-delete replaced models (data-dictionary rule). Detail path 020: `/controle-technique/equipements/{slug}` · `/en/technical-inspection/equipment/{slug}`.

### Base

| Attribute | Rule |
|-----------|------|
| `lifecycle` | `current` or `legacy`. Legacy stays listable as historical, not as “available now”. |
| `sort_order` | Display among current items. |
| `photo` | Optional Media (047). |
| Publication | Shared A-09 on the base. |

### Translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | Unique per `(locale, slug)`. Independent FR/EN slugs allowed. |
| `title` / `body` | Approved blocks. |
| SEO override | Optional (**049**). |

Index `/controle-technique/equipements` is a listing query (current first, then legacy). Not a Page.

## 3. Relationships

| Association | Rule |
|-------------|------|
| Page `technical_inspection` | Overview copy only. No FK required. |
| Resource → CT knowledge | Optional flag on Resource (**046**), not a required FK to a point. |
| Product | None. CERTIDOCS CT is a Product (041). |
| Operational CT / MINT databases | **Forbidden.** |

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Technical inspection entities specified (step 043 executed) | 2026-10-21 |

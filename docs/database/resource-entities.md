# Resource entities

| | |
|--|--|
| **Step** | 046 — Define resource entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [functional-spec.md](../requirements/functional-spec.md) P-06 / A-10 |
| **Next** | 056 — Implement reference table migrations |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Resources and current / historical / archive specified |

This is the step 046 deliverable. Domain: `App\Domain\Resources`. Binaries stay on the `documents` disk. MySQL stores metadata and path only. VER-08 gates 2020 reform labelling. **No migrations before gate 055.**

## 1. Resource (base)

| Attribute | Rule |
|-----------|------|
| `regulatory_status` | `current` \| `historical` \| `archive`. Public label must match. `archive` is a business label, not soft delete. |
| `authority` | Optional issuing body (e.g. MINT). Empty if unknown. |
| `issued_on` | Optional document date. |
| `file` | Required Media on the **documents** disk (047). Download via controlled route, not a raw public dump of every PDF. |
| `relates_to_ct` | Boolean. True = CT knowledge (hub / points / equipment), not a product. |
| Publication | Shared A-09 on the base. |
| `published_at` / `scheduled_for` | |

A resource may relate to a **product** or to **CT knowledge**, not both required. Both may be empty (general corporate file).

## 2. Resource translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | Unique per `(locale, slug)`. Detail: `/ressources/{slug}` · `/en/resources/{slug}`. |
| `title` / `summary` | |
| SEO override | Optional (**049**). |

Index `/ressources` · `/en/resources` is a listing query with current vs historical labelling.

## 3. Relationships

| Association | Rule |
|-------------|------|
| Resource ↔ Product | Optional many-to-many `resource_product`. |
| Resource → CT | `relates_to_ct`; no required FK to SecurityPoint. |
| Page document-list block | Reads Published resources. |

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Resource entities specified (step 046 executed) | 2026-10-21 |

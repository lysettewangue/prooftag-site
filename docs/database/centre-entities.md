# Centre directory entities

| | |
|--|--|
| **Step** | 044 — Define centre directory entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [functional-spec.md](../requirements/functional-spec.md) P-05 / A-07 |
| **Next** | 090 — Expertise admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Regions, centres, and verification specified |

This is the step 044 deliverable. Domain: `App\Domain\Centres`. Import starts **Unverified**. Public list shows **Active** only. Map pin only if coordinates are verified. ~35 claimed centres stay Unverified until VER-03. **No migrations before gate 055.**

Centre visibility uses **verification_status**, not the editorial A-09 publication enum. The `verify_centre` permission is distinct from **publish**.

## 1. Region

Ten official Cameroon regions, **seeded**. Codes are not freely invented in the CMS.

| `code` | FR name | EN name |
|--------|---------|---------|
| `AD` | Adamaoua | Adamawa |
| `CE` | Centre | Centre |
| `ES` | Est | East |
| `EN` | Extrême-Nord | Far North |
| `LT` | Littoral | Littoral |
| `NO` | Nord | North |
| `NW` | Nord-Ouest | North-West |
| `OU` | Ouest | West |
| `SU` | Sud | South |
| `SW` | Sud-Ouest | South-West |

### Base

| Attribute | Rule |
|-----------|------|
| `code` | Unique. One of the ten above. Not editable after seed. |

### Translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `name` | Display name. |
| `slug` | Unique per `(locale, slug)` for filter URLs. |

Regions are always “on” for filtering. They do not use A-09.

## 2. InspectionCentre

### Base

| Attribute | Rule |
|-----------|------|
| `region_id` | **Required.** FK to Region. |
| `operator_name` | Brand as imported (may repeat across cities). |
| `city` | Locale-independent locality label (e.g. Douala, Yaoundé). |
| `phone` / `email` | Optional. Unverified until VER-03. |
| `latitude` / `longitude` | Optional. Empty = no map pin. |
| `verification_status` | `unverified` (import default) → `active` \| `temporarily_unavailable` \| `archived`. |
| `last_verified_at` / `verified_by` | Set only by the `verify` action. |
| `sort_order` | Optional tie-break after region + city. |

Same operator name in two cities = **two rows** (distinct slugs).

### Translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | Unique per `(locale, slug)`. Detail: `/centres-de-controle/{slug}` · `/en/inspection-centres/{slug}`. |
| `name` | Public centre title. |
| `address_line` / `body` | Optional. Unverified text stays off the public site (status not Active). |
| SEO override | Optional (**049**). |

Index `/centres-de-controle` · `/en/inspection-centres`: search, region filter, pagination. Public query: `verification_status = active` only. Archived: public 404. Unverified and temporarily unavailable: admin only.

## 3. Relationships

| Association | Rule |
|-------------|------|
| Centre → Region | Many-to-one, **required**. |
| Centre → Product | None. |
| Centre → operational CERTIDOCS | **Forbidden.** |

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Centre directory entities specified (step 044 executed) | 2026-10-21 |

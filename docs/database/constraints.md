# Database constraints

| | |
|--|--|
| **Step** | 053 — Define database constraints |
| **Parent** | [data-dictionary.md](data-dictionary.md) (052) · [erd.md](erd.md) (051) |
| **Next** | 089 — Solutions admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Keys, uniqueness, and nullability specified |

This is the step 053 deliverable. Types live in the [data dictionary](data-dictionary.md). **No migrations before gate 055.**

## 1. Primary keys

Every table: `id` bigint unsigned auto-increment (Laravel default), except Spatie package tables keep their package schema.

## 2. Foreign keys

| Child | Parent | On delete |
|-------|--------|-----------|
| `*_translations.{parent}_id` | parent table | CASCADE |
| `inspection_centres.region_id` | `regions.id` | RESTRICT |
| `inspection_centres.verified_by` | `users.id` | SET NULL |
| Pivot `product_id` / `expertise_id` / `post_id` / `category_id` / `resource_id` | respective parents | CASCADE |

No FK from CMS tables to CERTIDOCS, CT-VERIF, DOSER, Video Enforcement, or MINT.

## 3. Uniqueness

| Table | Unique |
|-------|--------|
| `pages` | `code` |
| `products` | `code` |
| `products` | `sort_order` |
| `expertises` | `code` |
| `expertises` | `sort_order` |
| `security_points` | `sort_order` |
| `regions` | `code` |
| `site_settings` | singleton (application: one row; optional check `id = 1`) |
| `{entity}_translations` | `({parent}_id, locale)` |
| `{entity}_translations` with slugs | `(locale, slug)` |
| `redirects` | `(from_host, from_path)` |
| Pivots | pair of FKs |

Product translation `slug` must also equal `products.slug_fragment` (application invariant, 041).

## 4. Nullability (business)

| Must be NOT NULL | May be NULL |
|------------------|-------------|
| codes, sort_order on seeded types | `external_url` except CT-VERIF/DOSER **at publish time** (application) |
| `inspection_centres.region_id` | geo, phone, email, `verified_by` |
| `contact_requests.name`, `email`, `message`, `status` | `phone`, `subject` |
| `resources.regulatory_status` | `authority`, `issued_on` |
| publication_status on publishable bases | `published_at` until Published; `scheduled_for` unless Scheduled |

Enums stored as VARCHAR + CHECK or native ENUM: publication_status, verification_status, regulatory_status, lifecycle, career kind, post kind, contact status, locale `fr`/`en`, redirect status_code `301`/`302`.

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Database constraints specified (step 053 executed) | 2026-10-21 |

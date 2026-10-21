# Local reference data

| | |
|--|--|
| **Step** | 056 — Implement reference table migrations |
| **Parent** | [design-review.md](../database/design-review.md) (055) · [centre-entities.md](../database/centre-entities.md) (044) · [constraints.md](../database/constraints.md) (053) |
| **Next** | 090 — Expertise admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Regions and controlled statuses migrated and seeded |

First CMS migrations after gate **055**. PHP enums are the application source of truth. Seeders iterate enum cases. Later entity columns store matching VARCHAR codes (053), not foreign keys to `controlled_statuses`. Centres will foreign-key `regions.id` at **062**.

## 1. Tables

| Table | Purpose |
|-------|---------|
| `regions` | Ten official Cameroon region codes (`AD`…`SW`). Unique `code`. CHECK restricts to those ten. |
| `region_translations` | FR/EN `name` and `slug`. Unique `(region_id, locale)` and `(locale, slug)`. Locale CHECK `fr`\|`en`. CASCADE from `regions`. |
| `controlled_statuses` | Lookup rows for approved vocabularies. Unique `(set, code)`. `sort_order` follows enum case order. CHECK on `set`. No locale table (050). |

Regions are always available for directory filters. They are not A-09 publishable. There is no eleventh region.

## 2. Controlled status sets

| `set` | Codes (seed order) |
|-------|-------------------|
| `publication` | `draft`, `under_review`, `approved`, `scheduled`, `published`, `archived` |
| `centre_verification` | `unverified`, `active`, `temporarily_unavailable`, `archived` |
| `resource_regulatory` | `current`, `historical`, `archive` |
| `equipment_lifecycle` | `current`, `legacy` |
| `career_kind` | `job`, `internship` |
| `post_kind` | `news`, `article` |
| `contact_request` | `new`, `in_progress`, `responded`, `closed`, `spam` |

Do not add codes here that 039–055 did not approve.

## 3. Seed

```powershell
php artisan migrate
php artisan db:seed
```

`RegionSeeder` and `ControlledStatusSeeder` are idempotent (`updateOrCreate`). `DatabaseSeeder` calls them after `RoleSeeder`. Remaining verified CMS rows (products, pages, expertises, security points) wait for **068**.

## 4. PHPUnit

Same MySQL testing database as [local-permissions.md](local-permissions.md): `prooftag_catis_testing` on `127.0.0.1:3307`. `pdo_sqlite` is still not loaded.

```powershell
php artisan test --filter=ReferenceTablesTest
```

## 5. Out of this step

| Item | Step |
|------|------|
| Users, permissions, settings schema | 057 |
| Corporate / CMS entity tables | 058–067 |
| Remaining verified seeds | 068 |
| Database structure gate | 069 |
| Inspection centres | 062 |
| Filament modules | 088–106 |

V1 still has exactly five products and eight staff roles. Publish, translate, and update stay separate. DITROS-CT is not an entity. No foreign keys to operational systems.

## 6. Verification (2026-10-21)

- `php artisan migrate` — `regions`, `region_translations`, `controlled_statuses` on `prooftag_catis_local` (MySQL 8.4.9, `127.0.0.1:3307`)
- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors (level 5, `app/`)
- `php artisan test` — **24 passed** (ReferenceTablesTest 5, plus existing feature tests)

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Reference table migrations implemented (step 056 executed) | 2026-10-21 |

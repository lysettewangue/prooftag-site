# Local CMS schema

| | |
|--|--|
| **Steps** | 057–069 — System tables through the database structure gate |
| **Parent** | [design-review.md](../database/design-review.md) (055) · [local-reference-data.md](local-reference-data.md) (056) |
| **Next** | 089 — Solutions admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Schema migrated, verified seeds loaded, structure tests passing |

Users, Spatie roles/permissions, and the activity log were already installed (033 / 036 / 037). Step **057** does not recreate them. It adds the Settings singleton. Steps **058–067** add the approved CMS tables. **068** seeds verified identity only. **069** is the structure gate.

## 1. What landed

| Step | Tables |
|-----:|--------|
| 057 | `site_settings` (id = 1 only), `site_setting_translations` |
| 058 | `pages`, `team_members`, `partners`, `careers` + translations |
| 059 | `products` + translations |
| 060 | `expertises` + translations, `product_expertise` |
| 061 | `security_points`, `equipment` + translations |
| 062 | `inspection_centres` + translations (`region_id` RESTRICT, `verified_by` SET NULL) |
| 063 | `categories`, `posts` + translations, `post_category`, `post_product`, `post_expertise` |
| 064 | `resources` + translations, `resource_product` |
| 065 | Spatie `media` (`spatie/laravel-medialibrary` **11.23.10**). Alt text stays in `custom_properties` (`alt.fr` / `alt.en`). Disks: `public`, `documents` |
| 066 | `contact_requests` |
| 067 | `redirects` unique `(from_host, from_path)` |

Publishable bases store A-09 `publication_status`, `published_at`, `scheduled_for`, and `deleted_at` (recovery only). Centres use `verification_status`, not A-09.

## 2. Verified seeds (068)

Seeded as **draft** (or Settings, which are not A-09):

- Contact singleton from the frozen contact record. Social URLs and geo stay empty (VER-10).
- Eleven Page codes and frozen path slugs. `blocks` = `[]`.
- Five Product codes, public names, sort order 1–5, identical FR/EN slug fragments. No DITROS-CT row. `external_url` empty.
- Five Expertise codes and titles. Titles do not copy product names.
- Twelve security points (sentence-case titles from the 2020 list; `body` empty until rewrite).

Not seeded: team, partners, careers, centres, posts, resources, redirects. Import of unverified centres waits for VER-03.

```powershell
php artisan migrate
php artisan db:seed
```

Seeders are idempotent.

## 3. PHPUnit (069)

Same testing database as [local-permissions.md](local-permissions.md): `prooftag_catis_testing` on `127.0.0.1:3307`.

```powershell
php artisan test --filter=DatabaseStructureTest
```

The gate checks: eleven pages, five products, five expertises, twelve points, ten regions, Settings singleton, no sixth/DITROS product, no thirteenth point, product slugs = fragment, expertise titles ≠ product names, centre→region RESTRICT, translation CASCADE, `media` present, redirect uniqueness. Existing feature tests still pass.

## 4. Out of this band

| Item | Step |
|------|------|
| Domain model behaviour, visibility scopes | 070–072 |
| Actions, policies, validation | 073–080 |
| Media validation and derivatives | 082 |
| Filament modules | 088–106 |
| Representative published content | 107–113 |

V1 still has exactly five products and eight staff roles. Publish, translate, and update stay separate. DITROS-CT is not an entity. No foreign keys to operational systems.

## 5. Verification (2026-10-21)

- `php artisan migrate` — CMS tables on `prooftag_catis_local` (MySQL 8.4.9, `127.0.0.1:3307`)
- `php artisan db:seed` — Settings, pages, products, expertises, security points
- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors (level 5, `app/`)
- `php artisan test` — **34 passed** (DatabaseStructureTest 10, ReferenceTablesTest 5, plus existing)

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | System through SEO schema, verified seeds, and structure tests (steps 057–069 executed) | 2026-10-21 |

# Centre directory admin

| | |
|--|--|
| **Step** | 094 — Centre directory admin |
| **Parent** | [local-equipment-admin.md](local-equipment-admin.md) · [centre-entities.md](../database/centre-entities.md) |
| **Next** | 099 — Careers admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Centres importable and verifiable; ten regions frozen |

Filament module at `/admin/centres`. Staff **may import** a row. Import always starts **Unverified**. Public list is **Active** only. Visibility uses `verification_status`, not A-09. `verify_centre` is not `publish`. An empty directory is valid; there is no centre seeder. Regions are the ten seeded Cameroon codes — there is no region CMS module and no eleventh region.

The resource is thin: `ImportCentre`, `UpdateCentre`, `TranslateCentre`, `VerifyCentre`, `MarkCentreTemporarilyUnavailable`, and `ArchiveCentre`. Same operator in two cities is two rows. Empty coordinates mean no map pin. Phone, email, and copy stay off the public site until Active (VER-03).

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `region_id` | Required. One of the ten seeded regions. |
| `operator_name`, `city` | Required. Distinct slugs when the same brand appears in two cities. |
| Phone, email, lat/lng, sort | Optional. Lat/lng empty = no pin. |
| Name, slug, address, body, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the name. Unique per `(locale, slug)`. |
| `verification_status` | Disabled on the form. Header actions only. |
| Hard-delete | Impossible. Archive instead. Filament `canDelete` stays false even for Super Admin. |

Header: **Vérifier** (`verify_centre`), **Marquer indisponible** and **Archiver** (admin). Unverified and temporarily unavailable stay admin-only. Archived is a public 404 (138).

## 2. Who may open it

| Role | List | Create / update FR | Translate EN | Verify | Unavailable / archive |
|------|------|--------------------|--------------|--------|-----------------------|
| Super Admin / Admin | yes | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no | no |
| Technical editor | yes | yes | no | yes | no |
| Translator | yes | no | yes | no | no |
| Support | yes | no | no | no | no |
| Reviewer / Communications | no | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=CentresAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Team admin | 095 |
| Public directory search / filters | 138 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=CentresAdminTest` — passed
- Browser: `/admin/centres` empty list; create Unverified centre in Littoral; verify; no delete; no `/admin/regions`

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Centre directory admin implemented (step 094 executed) | 2026-10-22 |

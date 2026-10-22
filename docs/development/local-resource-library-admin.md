# Resource library admin

| | |
|--|--|
| **Step** | 098 — Resource library admin |
| **Parent** | [local-editorial-admin.md](local-editorial-admin.md) · [resource-entities.md](../database/resource-entities.md) · [verification-register.md](../content/verification-register.md) |
| **Next** | 105 — Controlled content blocks |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Documents editable as drafts; VER-08 still Open |

Filament module at `/admin/resources`, under **Ressources**. Staff **may create** a row. Staff **must not** hard-delete one: archive via A-09. `regulatory_status` `archive` is a **public label**, not Eloquent soft-delete. An empty list is valid; there is no resource seeder. VER-08 (2020 reform: current vs historical) remains Open — helper text, not a hidden button. A PDF on the `documents` disk is required before Published.

The resource is thin: `CreateResource`, `UpdateResource`, `TranslateResource`, `AttachMedia` (collection `documents`), and `TransitionPublication`. Download stays `DownloadResource` (100 / public later). Not a Post. Not a product.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `regulatory_status` | `current` / `historical` / `archive`. Public label must match. |
| `authority` | Optional issuing body. Empty is stored as null. |
| `issued_on` | Optional document date. |
| `relates_to_ct` | Boolean. True = CT knowledge, not a product. |
| Products | Optional tagging. Synced by `UpdateResource`. |
| File | PDF, collection `documents`, private disk. Required before publish. |
| Title, slug, summary, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the title. Unique per `(locale, slug)`. |
| Publication | A-09 header actions. Draft cannot jump to Published. |
| Hard-delete | Impossible. Filament `canDelete` stays false even for Super Admin. |

## 2. Who may open it

| Role | List / edit | Create / update FR | Translate EN | Publish |
|------|-------------|--------------------|--------------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Technical editor | yes | yes | no | no |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Communications editor / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=ResourceLibraryAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Careers admin | 099 |
| Media library admin | 100 |
| Public resource listing / download | 139+ |
| Close VER-08 / migrate 2020 docs | 157 / 151+ |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=ResourceLibraryAdminTest` — 15 passed
- `php artisan test` — 186 passed
- Browser: `/admin/resources` empty list; create draft “Guide CT”; VER-08 helper; edit shows Soumettre à relecture / Archiver; no delete; slug `guide-ct`

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Resource library admin implemented (step 098 executed) | 2026-10-22 |

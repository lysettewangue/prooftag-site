# Equipment admin

| | |
|--|--|
| **Step** | 093 — Equipment admin |
| **Parent** | [local-security-points-admin.md](local-security-points-admin.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) |
| **Next** | 097 — News and articles admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Current and legacy equipment editable; create allowed; no hard-delete |

Filament module at `/admin/technical-inspection/equipment`, under cluster **Contrôle technique**. Staff **may create** a row. Staff **must not** hard-delete one: mark it `legacy` or archive via A-09. An empty list is valid; there is no equipment seeder. Not a product. No live CT processing.

The resource is thin: `CreateEquipment`, `UpdateEquipment`, `TranslateEquipment`, `AttachMedia` (optional photo), and `TransitionPublication`. Independent FR/EN slugs are allowed. Photo is Media collection `images` on edit only.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `lifecycle` | `current` (en service) or `legacy` (historique). Replaced models stay as legacy. |
| `sort_order` | Display order among current items. |
| Title, body, slug, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the title. Unique per `(locale, slug)`. |
| Photo | Optional. Images require `alt.fr` and `alt.en`. |
| Hard-delete | Impossible. Filament `canDelete` / `canForceDelete` stay false even for Super Admin. |

Header actions follow A-09. Draft cannot jump to Published. Unpublished items omit from the public list (137).

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
php artisan test --filter=EquipmentAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Centre directory admin | 094 |
| Public equipment presentation | 137 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=EquipmentAdminTest` — passed
- Browser: `/admin/technical-inspection/equipment` empty list; create current item; mark legacy; no delete; communications editor 403

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Equipment admin implemented (step 093 executed) | 2026-10-22 |

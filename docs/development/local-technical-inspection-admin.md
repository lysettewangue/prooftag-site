# Technical inspection admin

| | |
|--|--|
| **Step** | 091 — Technical inspection admin |
| **Parent** | [local-expertise-admin.md](local-expertise-admin.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) · [corporate-entities.md](../database/corporate-entities.md) |
| **Next** | 099 — Careers admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Hub page `technical_inspection` editable; not the twelve points |

Filament module at `/admin/technical-inspection/overview/{id}/edit`, under cluster **Contrôle technique**. Exactly **one** Page row (`technical_inspection`). Staff cannot create or delete it, and cannot open the other ten pages from this module. Not a product. No live CT processing.

The resource is thin: `UpdatePage`, `TranslatePage`, and `TransitionPublication` (submit / approve / publish / schedule / archive). Overview copy is stored as a single `rich_text` block. Approved block types beyond that wait for **105**.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `code` | Frozen `technical_inspection`. Disabled. |
| Translation `slug` | Frozen: `controle-technique` / `technical-inspection`. |
| Title, overview, meta | Per locale. FR requires `update`. EN requires `translate`. |
| Twelve security points | **092** |
| Equipment | **093** |

Header actions follow A-09. Draft cannot jump to Published.

## 2. Who may open it

| Role | List / edit | Update FR overview | Translate EN | Publish |
|------|-------------|--------------------|--------------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Technical editor | yes | yes | no | no |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Communications editor / Support | no | no | no | no |

Communications editors own corporate pages later; they do **not** own this hub.

## 3. PHPUnit

```powershell
php artisan test --filter=TechnicalInspectionAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Twelve security points, reorder | 092 |
| Equipment current / legacy | 093 |
| Other ten CMS pages | later corporate / 105 |
| Public CT templates | 135–137 |

## 5. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=TechnicalInspectionAdminTest` — passed
- Browser: hub form shows frozen code and slugs; FR overview saved; create URL 404

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Technical inspection admin implemented (step 091 executed) | 2026-10-21 |

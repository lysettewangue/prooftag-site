# Security points admin

| | |
|--|--|
| **Step** | 092 — Security points admin |
| **Parent** | [local-technical-inspection-admin.md](local-technical-inspection-admin.md) · [technical-inspection-entities.md](../database/technical-inspection-entities.md) |
| **Next** | 095 — Team admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Twelve security points editable and reorderable; no thirteenth |

Filament module at `/admin/technical-inspection/security-points`, under cluster **Contrôle technique**. Exactly **twelve** seeded rows. Staff cannot create or delete a row. Visibility is publication; the slot `sort_order` 1–12 remains. Not a product. No live CT processing.

The resource is thin: `UpdateSecurityPoint`, `TranslateSecurityPoint`, `ReorderSecurityPoints`, and `TransitionPublication`. Drag-reorder on the list calls `ReorderSecurityPoints` (not a raw column update). Identity is `sort_order`, not a slug.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `sort_order` 1–12 | Identity. Disabled on the form. Reorder on the list (technical roles). Unique. CHECK allows 0 only as a temporary swap slot. |
| Title, body | Per locale. FR requires `update`. EN requires `translate`. Short headings, not all-caps (AUD-001). |
| A thirteenth row | Impossible. Create is false; the database rejects `sort_order` 13. |
| Equipment | **093** |

Header actions follow A-09. Draft cannot jump to Published. Unpublished points omit from the public list; the number stays reserved.

## 2. Who may open it

| Role | List / edit | Update FR / reorder | Translate EN | Publish |
|------|-------------|---------------------|--------------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Technical editor | yes | yes | no | no |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Communications editor / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=SecurityPointsAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Equipment current / legacy | 093 |
| Public 12-point presentation | 136 |

## 5. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=SecurityPointsAdminTest` — passed
- Browser: `/admin/technical-inspection/security-points` lists twelve points; order field disabled; create URL 404

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Security points admin implemented (step 092 executed) | 2026-10-21 |

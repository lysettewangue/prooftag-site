# Media library admin

| | |
|--|--|
| **Step** | 100 — Media library admin |
| **Parent** | [local-careers-admin.md](local-careers-admin.md) · [media-entities.md](../database/media-entities.md) |
| **Next** | 105 — Controlled content blocks |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Central library for attached files; empty set valid |

Filament module at `/admin/media`, under **Ressources**. Staff **must not** create an orphan file here: attach on the owner (settings, team, partners, products, equipment, posts, resources). Staff **may** delete a file only through `SafeDeleteMedia`, which blocks while the owner is public. An empty list is valid; there is no media seeder. Bilingual alt lives in Spatie `custom_properties` (`alt.fr` / `alt.en`) — exception to ADR-001. Empty `rights` means do not use the file on the public site (helper, not a publish block). Public resource download stays `DownloadResource` (123+).

The resource is thin: `UpdateMedia`, `TranslateMedia`, and `SafeDeleteMedia`. `AttachMedia` stays on owner modules.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| File, MIME, disk, collection, owner | Read-only. MIME whitelist jpeg/png/webp/svg/pdf, 8 MB. |
| `alt.fr` | Required for images. Admin `update`. |
| `alt.en` | Required for images. `translate`. |
| `rights` | Optional provenance note. Empty is stored empty. |
| Create | Impossible. Even Super Admin. |
| Delete | Admin only, via `SafeDeleteMedia`. Blocked while the owner is public. |

## 2. Who may open it

| Role | List / edit | Update FR / rights | Translate EN | Delete unused |
|------|-------------|--------------------|--------------|---------------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | no | yes | no |
| Translator | yes | no | yes | no |
| Technical editor / Communications / Reviewer / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=MediaLibraryAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Contact request admin | 101 |
| Public resource download | 139+ |
| Approved content blocks with media | 105 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=MediaLibraryAdminTest` — 15 passed
- `php artisan test` — 214 passed
- Browser: `/admin/media` empty list « Aucun(e) médias »; Médiathèque under Ressources; no create action

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pending | |

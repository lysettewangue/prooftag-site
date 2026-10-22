# Partners admin

| | |
|--|--|
| **Step** | 096 — Partners admin |
| **Parent** | [local-team-admin.md](local-team-admin.md) · [corporate-entities.md](../database/corporate-entities.md) · [verification-register.md](../content/verification-register.md) |
| **Next** | 105 — Controlled content blocks |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Partners editable as drafts; VER-09 still Open |

Filament module at `/admin/partners`, under **Corporate**. Staff **may create** a row. Staff **must not** hard-delete one: archive via A-09. An empty list is valid; there is no partner seeder. Records may exist as Draft; **do not Publish** until VER-09 (MINT, Prooftag SAS, others — logos and URLs) is closed — helper text, not a hidden button.

The resource is thin: `CreatePartner`, `UpdatePartner`, `TranslatePartner`, `AttachMedia` (optional logo), and `TransitionPublication`. `name` is not translated. Blurb is per locale. Empty `url` means name/logo only, no outbound link.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `name` | Institutional name. Not translated. Required. |
| `sort_order` | Display order on `/a-propos/partenaires`. |
| `url` | Optional outbound site. Empty is stored as null. |
| Slug, blurb, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the name. Unique per `(locale, slug)`. Blurb is optional. |
| Logo | Optional until VER-09. Images require `alt.fr` and `alt.en`. Collection `images`. |
| Publication | A-09 header actions. Draft cannot jump to Published. VER-09 remains Open. |
| Hard-delete | Impossible. Filament `canDelete` stays false even for Super Admin. |

## 2. Who may open it

| Role | List / edit | Create / update FR | Translate EN | Publish |
|------|-------------|--------------------|--------------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Communications editor | yes | yes | no | yes |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Technical editor / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=PartnersAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| News and articles admin | 097 |
| Public partners page | 128+ |
| Close VER-09 | Editorial (151+) |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=PartnersAdminTest` — 13 passed
- `php artisan test` — 158 passed
- Browser: `/admin/partners` empty list; create draft “MINT”; VER-09 helper; edit shows Soumettre à relecture / Archiver; no delete; empty site; slug `mint`

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Partners admin implemented (step 096 executed) | 2026-10-22 |

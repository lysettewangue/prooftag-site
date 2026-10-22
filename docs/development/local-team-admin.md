# Team admin

| | |
|--|--|
| **Step** | 095 — Team admin |
| **Parent** | [local-centre-directory-admin.md](local-centre-directory-admin.md) · [corporate-entities.md](../database/corporate-entities.md) · [verification-register.md](../content/verification-register.md) |
| **Next** | 098 — Resource library admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Team members editable as drafts; VER-02 still Open |

Filament module at `/admin/team-members`, under **Corporate**. Staff **may create** a row. Staff **must not** hard-delete one: archive via A-09. An empty list is valid; there is no team seeder. Do not mix the site named officers with the www organigramme (REC-01 / VER-02). Records may exist as Draft; **do not Publish** until VER-02 is closed — helper text, not a hidden button.

The resource is thin: `CreateTeamMember`, `UpdateTeamMember`, `TranslateTeamMember`, `AttachMedia` (optional photo), and `TransitionPublication`. `full_name` is not translated. Title (function) and bio are per locale.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `full_name` | Person’s name. Not translated. Required. |
| `sort_order` | Display order on `/a-propos/equipe`. |
| Title, slug, bio, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the title. Unique per `(locale, slug)`. |
| Photo | Optional until VER-02. Images require `alt.fr` and `alt.en`. |
| Publication | A-09 header actions. Draft cannot jump to Published. VER-02 remains Open. |
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
php artisan test --filter=TeamAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Partners admin | 096 |
| Public team page | 128+ |
| Close VER-02 | Editorial (151+) |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=TeamAdminTest` — 11 passed
- `php artisan test` — 145 passed
- Browser: `/admin/team-members` empty list; create draft “TCHANGA Jean”; VER-02 helper; edit shows Soumettre à relecture / Archiver; no delete

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Team admin implemented (step 095 executed) | 2026-10-22 |

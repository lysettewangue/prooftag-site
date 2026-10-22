# Careers admin

| | |
|--|--|
| **Step** | 099 — Careers admin |
| **Parent** | [local-resource-library-admin.md](local-resource-library-admin.md) · [corporate-entities.md](../database/corporate-entities.md) |
| **Next** | 101 — Contact request admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Jobs and internships editable as drafts; empty published set valid |

Filament module at `/admin/careers`, under **Corporate**. Staff **may create** a row. Staff **must not** hard-delete one: archive via A-09. An empty list is a valid launch state (P-11); there is no career seeder. Not a Post, not a product, not a Resource. No public application inbox in V1 — empty `application_url` means enquire via the contact form. Custom content blocks wait for **105** — body is a single rich-text payload.

The resource is thin: `CreateCareer`, `UpdateCareer`, `TranslateCareer`, and `TransitionPublication`.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `kind` | `job` or `internship`. |
| `closes_at` | Optional. After this instant the listing may show as closed. |
| `application_url` | Optional outbound apply URL. Empty is stored as null. |
| Title, slug, body, meta | Per locale. FR requires `update`. EN requires `translate`. Empty slug is generated from the title. Unique per `(locale, slug)`. |
| Publication | A-09 header actions. Draft cannot jump to Published. |
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
php artisan test --filter=CareersAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Media library admin | 100 |
| Public careers listing | 143 |
| Approved content blocks | 105 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=CareersAdminTest` — 13 passed
- `php artisan test` — 199 passed
- Browser: `/admin/careers` empty list; create draft “Ingénieur CT” (Emploi); P-11 helper; edit shows Soumettre à relecture / Archiver; no delete; slug `ingenieur-ct`; publication draft

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pending | |

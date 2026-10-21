# Expertise admin

| | |
|--|--|
| **Step** | 090 — Expertise admin |
| **Parent** | [local-solutions-admin.md](local-solutions-admin.md) · [expertise-entities.md](../database/expertise-entities.md) |
| **Next** | 092 — Security points admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Five expertise rows editable; no sixth domain |

Filament module at `/admin/expertises`. Exactly **five** seeded domains. Staff cannot create or delete a row. Visibility is publication, not removal. Titles must **not** copy a product `public_name`. Not a sixth product. Not Contrôle technique.

The resource is thin: `UpdateExpertise`, `TranslateExpertise`, and `TransitionPublication` (submit / approve / publish / schedule / archive).

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `code`, `sort_order` 1–5 | Frozen. Disabled. |
| Translation `slug` | Frozen to the sitemap fragments in FR and EN. |
| `icon` | Optional Lucide key. Not a product mark. |
| Title, summary, body, meta | Per locale via `TranslateExpertise`. Title required; cannot equal a product name. |

Header actions follow A-09. Draft cannot jump to Published.

## 2. Who may open it

| Role | List / edit | Update copy & icon | Translate | Publish |
|------|-------------|--------------------|-----------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Technical editor | yes | yes | no | no |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Communications editor / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=ExpertisesAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Technical inspection cluster | 091–093 |
| Public expertise templates | 134 |

## 5. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=ExpertisesAdminTest` — passed
- Browser: `/admin/expertises` lists five domains; identity and slugs disabled

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Expertise admin implemented (step 090 executed) | 2026-10-21 |

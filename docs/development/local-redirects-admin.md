# SEO and redirect admin

| | |
|--|--|
| **Step** | 102 — SEO and redirect admin |
| **Parent** | [local-contact-inbox-admin.md](local-contact-inbox-admin.md) · [seo-entities.md](../database/seo-entities.md) |
| **Next** | 103 — Admin user management |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Redirect table editable; meta stays on settings and records |

Filament module at `/admin/redirects`, under **Corporate**. SEO **metadata is not a standalone type**: defaults live on Settings, per-record meta on translations. This module edits **Redirect** rows only. Staff **may create**. Staff **must not** hard-delete: set `is_active` false. An empty list is valid until the legacy register is loaded (165–167). Canonical host remains `https://www.prooftagcatis.com`. DITROS-CT is not decided here (166).

The resource is thin: `UpsertRedirect`. Resolution stays `RedirectResolver`.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `from_host` | One of the four approved hosts. |
| `from_path` / `to_path` | Must start with `/`. Unique `(from_host, from_path)`. |
| `status_code` | `301` or `302`. |
| `is_active` | Boolean. Inactive rows do not match. |
| Hard-delete | Impossible, even for Super Admin. |

## 2. Who may open it

| Role | List / create / update |
|------|------------------------|
| Super Admin / Admin | yes |
| Other staff | no |

## 3. PHPUnit

```powershell
php artisan test --filter=RedirectsAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Admin user management | 103 |
| Load legacy URL register | 165–167 |
| DITROS-CT target | 166 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=RedirectsAdminTest` — 11 passed
- Browser: `/admin/redirects` empty list « Aucun(e) redirections »; Corporate / Redirections; Créer visible

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pending | |

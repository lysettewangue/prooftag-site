# Admin audit interface

| | |
|--|--|
| **Step** | 104 — Admin audit interface |
| **Parent** | [local-staff-users-admin.md](local-staff-users-admin.md) · [local-audit.md](local-audit.md) |
| **Next** | 106 — Test admin permissions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Read-only activity log for auth and staff |

Filament module at `/admin/audit`, under **Administration**. Read-only view of `activity_log`. Staff **must not** create, edit, or delete rows, even Super Admin. Passwords are never stored. Streams: `auth` (login / logout / login_failed) and `staff` (user and role changes).

The resource is thin: it reads `App\Models\Activity`. Writers stay the 037 listeners.

## 1. What staff can see

| Field | Rule |
|-------|------|
| `log_name` / `event` / `description` | Read-only. |
| Causer email | When a staff user caused the event. |
| Subject | Morph type and id. |
| `properties` | JSON. No password hashes. |
| Filters | `auth` or `staff`. |

## 2. Who may open it

| Role | List / view |
|------|-------------|
| Super Admin / Admin | yes |
| Other staff | no |

## 3. PHPUnit

```powershell
php artisan test --filter=AuditAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Controlled content blocks | 105 |
| Permission gate | 106 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=AuditAdminTest` — 6 passed
- `php artisan test` — 253 passed
- Browser: `/admin/audit` lists auth login / login_failed and staff events; no create action

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pending | |

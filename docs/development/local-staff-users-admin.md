# Admin user management

| | |
|--|--|
| **Step** | 103 — Admin user management |
| **Parent** | [local-redirects-admin.md](local-redirects-admin.md) · [roles.md](../admin/roles.md) |
| **Next** | 104 — Admin audit interface |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Eight frozen roles; last Super Admin protected |

Filament module at `/admin/users`, under **Administration**. V1 still has **exactly eight** roles. Each staff user has **exactly one** role. No public registration. Role catalogue stays Shield Super Admin only (`/admin/shield/roles`).

The resource is thin: `CreateStaffUser`, `UpdateStaffUser`, `DeleteStaffUser`.

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `name` / `email` | Required. Email unique. |
| `password` | Required on create, min 8. Optional on edit. Never shown back. |
| `role` | One of the eight codes. Administrator cannot assign Super Admin. |
| Last Super Admin | Cannot be demoted or deleted. |
| Self-delete | Impossible. |

## 2. Who may open it

| Role | List / create | Edit Super Admin | Assign Super Admin |
|------|---------------|------------------|--------------------|
| Super Admin | yes | yes | yes |
| Administrator | yes | no | no |
| Other staff | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=StaffUsersAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Admin audit interface | 104 |
| Per-role smoke gate | 106 |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=StaffUsersAdminTest` — 11 passed
- Browser: `/admin/users` lists the bootstrap Super administrateur; Créer visible

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pending | |

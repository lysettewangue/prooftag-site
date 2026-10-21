# Permission foundation

| | |
|--|--|
| **Step** | 036 — Implement the permission foundation |
| **Parent** | [roles.md](../admin/roles.md) · [local-filament.md](local-filament.md) |
| **Next** | 070 — Implement domain models |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Enforced on the server |

Spatie + Filament Shield enforce the eight roles from [roles.md](../admin/roles.md). CMS module policies wait for **088–106**. Activity log is [local-audit.md](local-audit.md). Quality tools: [local-quality.md](local-quality.md).

## 1. Packages

| Package | Version |
|---------|---------|
| `bezhansalleh/filament-shield` | **4.3.1** (constraint `^4.3`) |
| `spatie/laravel-permission` | **8.3.2** |

No `panel_user` role. V1 has exactly the eight codes in the register.

## 2. What is enforced

- Spatie tables: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`
- `User` uses `HasRoles`. `canAccessPanel()` is true only when the user has one of the eight roles
- `User::assignStaffRole()` `syncRoles()` so a staff user keeps **exactly one** role
- Super Admin is a real `super_admin` role **and** a `Gate::before` (break-glass)
- Custom verbs kept snake_case: `translate`, `publish`, `verify_centre`, `manage_redirects`, `contact_inbox`, `manage_users`
- Domain **update** is not a global verb here — it lands with CMS resources (088+)
- Shield Role UI at `/admin/shield/roles` is Super Admin only
- Spatie middleware aliases: `role`, `permission`, `role_or_permission`

Local bootstrap user (`FILAMENT_ADMIN_EMAIL`, on this machine `admin@prooftag-catis.test`) receives `super_admin` from `DatabaseSeeder`.

## 3. Seed

```powershell
php artisan migrate
php artisan db:seed
```

`RoleSeeder` is idempotent. It does not create users.

## 4. PHPUnit

This machine has `pdo_mysql` and not `pdo_sqlite`. `phpunit.xml` uses MySQL **8.4.9** on `127.0.0.1:3307`, database **`prooftag_catis_testing`** (created at this step). `RefreshDatabase` must never run against `prooftag_catis_local`.

```powershell
php artisan test --filter=PermissionFoundationTest
```

## 5. Out of this step

| Item | Step |
|------|------|
| Activity log | 037 (done — [local-audit.md](local-audit.md)) |
| Pint, Larastan, Pest | 038 (done — [local-quality.md](local-quality.md)) |
| User CRUD in Filament | 103 |
| Per-module policies and smoke tests | 088–106 |

## 6. Verification (2026-10-20)

- `php artisan test` — 13 passed (PermissionFoundationTest 7, LocalisationTest 4, examples 2)
- `GET /admin` unauthenticated → redirect to `/admin/login`
- Signed-in user with no staff role → **403**
- Each of the eight roles can open `/admin`
- Super Admin can open `/admin/shield/roles`; Administrator and Translator cannot
- Browser (signed in as the local bootstrap user): `/admin` dashboard plus Filament Shield → Rôles listing all eight roles; `GET /admin/register` → **404**

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Permission foundation enforced (step 036 executed) | 2026-10-20 |

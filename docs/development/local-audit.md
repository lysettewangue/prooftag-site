# Audit logging

| | |
|--|--|
| **Step** | 037 — Establish audit logging |
| **Parent** | [local-permissions.md](local-permissions.md) · [system-architecture.md](../architecture/system-architecture.md) |
| **Next** | 042 — Define expertise entities |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Administrative actions recorded |

`spatie/laravel-activitylog` writes staff actions to `activity_log`. The Filament audit screen is step **104**. CMS model logging waits for domain models (070+). Quality tools: [local-quality.md](local-quality.md).

## 1. Package

| Package | Version |
|---------|---------|
| `spatie/laravel-activitylog` | **5.1.3** (constraint `^5.1`, Laravel 13) |

Composer audit: no advisories.

## 2. What is recorded

| Stream | `log_name` | Events |
|--------|------------|--------|
| Auth | `auth` | `login`, `logout`, `login_failed` |
| Staff | `staff` | User `name` / `email` changes; Role `name` / `guard_name`; role and permission attach/detach |

Passwords and `remember_token` are never stored. Failed logins keep the email, not the password.

`User` is both subject (`LogsActivity`) and causer (`CausesActivity`). Roles use `App\Models\Role` so Shield edits are logged.

`RoleSeeder` disables logging so the eight-role seed is not an audit flood.

## 3. Out of this step

| Item | Step |
|------|------|
| Filament audit interface | 104 |
| Logging CMS content models | 070+ / 088+ |
| Pint, Larastan, Pest | 038 (done — [local-quality.md](local-quality.md)) |

## 4. Verification (2026-10-20)

`php artisan test` — 19 passed (AuditLoggingTest 6, PermissionFoundationTest 7, LocalisationTest 4, examples 2).

- Seeding roles writes **0** activity rows
- Creating/updating a user logs `created` / `updated` without password hashes
- Assigning a staff role logs `role_attached` with the actor as causer
- Creating a role logs `created`
- Filament `/admin/login` success → `logged_in`; bad credentials → `login_failed` without password
- `GET /` writes no activity
- Browser: sign out and sign in as the local bootstrap user records `logged_out` then `logged_in` on `activity_log` (causer = that user)

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Administrative actions recorded (step 037 executed) | 2026-10-20 |

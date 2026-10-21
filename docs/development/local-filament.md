# Filament foundation

| | |
|--|--|
| **Step** | 033 — Install the Filament foundation |
| **Parent** | [laravel-app.md](laravel-app.md) |
| **Next** | 088 — Global settings admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Admin login only |

Panel at `/admin`. No public registration. No CMS resources. Roles are [roles.md](../admin/roles.md). Permissions are [local-permissions.md](local-permissions.md). Public locales are [local-localisation.md](local-localisation.md).

## 1. Packages

| Package | Version |
|---------|---------|
| `filament/filament` | **5.10.2** (constraint `^5.10.2`) |
| Livewire | **4.4.7** |

5.10.2 includes the Filament 5 security patches (Composer audit: no advisories).

## 2. Panel

`app/Providers/Filament/AdminPanelProvider.php`:

- Path `/admin`
- `->login()` only (no `->registration()`)
- Brand name `PROOFTAG-CATIS`
- Default Filament dashboard widgets; **no** Filament resources

Routes present: `admin/login`, `admin/logout`, `admin` (dashboard). `GET /admin/register` → **404**.

## 3. Identity tables (not business schema)

Laravel's `users`, `password_reset_tokens`, and `sessions` tables were migrated so Filament can authenticate. Cache/jobs tables and content schema wait for later steps (gate **055**).

Staff users implement `FilamentUser`. `canAccessPanel()` requires one of the eight roles ([local-permissions.md](local-permissions.md)).

Create a local user (not committed):

```powershell
php artisan make:filament-user
```

On this machine: email `admin@prooftag-catis.test`. Password is only in the gitignored `.env`.

## 4. Verification (2026-10-20)

- `GET /admin/login` → **200**, title `Connexion - PROOFTAG-CATIS`, locale `fr`
- Sign-in as `admin@prooftag-catis.test` → `/admin` dashboard (`Tableau de bord`)
- Sidebar has Dashboard only — no product, page, or media modules
- `GET /admin/register` → **404**

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Filament `/admin` login works (step 033 executed) | 2026-10-20 |

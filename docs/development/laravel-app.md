# Laravel 13 application

| | |
|--|--|
| **Step** | 029 — Create the Laravel 13 project |
| **Next** | 038 — Establish quality tooling |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | App starts locally |

## 1. What was created

Skeleton `laravel/laravel` **v13.0.0** merged into `d:\websiteprooftag` (docs and Git history kept).

| Item | Version |
|------|---------|
| Framework | Laravel **13.38.0** |
| PHP | 8.4.25 |
| Composer package | `prooftag-catis/website` |
| Frontend | Vite build succeeded (`npm run build`) |

Verified: `php artisan --version`, `php artisan about`, `GET http://127.0.0.1:8000/` → **200**.

## 2. Local runtime (030–032 applied)

See [local-settings.md](local-settings.md), [local-database.md](local-database.md), and [local-mail.md](local-mail.md).

| Setting | Now | Owned by |
|---------|-----|----------|
| `APP_NAME` | `PROOFTAG-CATIS` | 030 |
| Timezone | `Africa/Douala` | 030 |
| Locale | `fr` at `/`, `en` at `/en` | 034 — [local-localisation.md](local-localisation.md) |
| Session / cache | `file` | 030 |
| Queue | `sync` | charter |
| Database | MySQL 8.4.9 `127.0.0.1:3307` / `prooftag_catis_local` | 031 |
| Mail | SMTP → Mailpit `127.0.0.1:1025` | 032 |

Do not use MySQL 5.7 `:3306`. Start project MySQL with `docs/development/start-mysql84.ps1`.

`.env` is gitignored. `.env.example` records the 030–032 settings plus file/sync drivers.

## 3. How to start

```powershell
$env:Path = [Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [Environment]::GetEnvironmentVariable("Path","User")
cd d:\websiteprooftag
php artisan serve
```

Admin panel: [local-filament.md](local-filament.md) (`/admin`, login only).

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Laravel 13 starts locally (step 029 executed) | 2026-10-20 |

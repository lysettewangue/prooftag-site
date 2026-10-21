# Localisation

| | |
|--|--|
| **Step** | 034 — Establish localisation |
| **Parent** | [laravel-app.md](laravel-app.md) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | French default, English secondary |

Public French has **no** `/fr` prefix. English lives under `/en`. `/admin` stays outside locale groups. Content translation tables are ADR-001 (gate 055). Roles are [roles.md](../admin/roles.md).

## 1. URL rules

| Locale | Home | Other public pages (later) |
|--------|------|----------------------------|
| `fr` (default) | `/` | `/…` |
| `en` | `/en` | `/en/…` |

`GET /fr` and `GET /fr/…` **301** to the unprefixed path.

Helper: `locale_route('home')` (see `app/Support/helpers.php`). Named routes are `fr.home` and `en.home`.

Middleware: `locale:fr` / `locale:en` (`SetLocale`) sets `app()->setLocale()` from the route group, not from a cookie.

## 2. UI strings

| Path | Role |
|------|------|
| `lang/fr/ui.php` | Public UI, French |
| `lang/en/ui.php` | Public UI, English |
| `lang/en/auth.php` (and siblings) | Laravel framework strings (English fallback) |

UI fallback is `en` (`APP_FALLBACK_LOCALE`). Marketing body still requires a real EN record before an EN URL publishes (ADR-001). This step only proves the **switch is not a French copy**.

## 3. Verification (2026-10-20)

`php artisan test --filter=LocalisationTest` — 4 passed.

Browser:

- `/` → `lang="fr"`, heading *Plateforme numérique PROOFTAG-CATIS*
- Language link **English** → `/en`, heading *PROOFTAG-CATIS digital platform*
- `/en/admin` → 404
- `/admin` unchanged (Filament, step 033)

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | FR default and EN secondary routes work (step 034 executed) | 2026-10-20 |

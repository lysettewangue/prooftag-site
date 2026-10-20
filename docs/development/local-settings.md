# Core local application settings

| | |
|--|--|
| **Step** | 030 — Configure core local settings |
| **Parent** | [laravel-app.md](laravel-app.md) |
| **Next** | 039 — Begin conceptual database design |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Applied locally |

`.env` is gitignored. Copy values from [`.env.example`](../../.env.example). Database is [local-database.md](local-database.md) (031). Mail is [local-mail.md](local-mail.md) (032).

## 1. Identity and clock

| Setting | Value |
|---------|--------|
| `APP_NAME` | `PROOFTAG-CATIS` |
| `APP_URL` | `http://127.0.0.1:8000` |
| `APP_TIMEZONE` | `Africa/Douala` |
| Config default | `env('APP_TIMEZONE', 'Africa/Douala')` |

PHP `date.timezone` on this machine is already `Africa/Douala` (step 026).

## 2. Locales

| Setting | Value |
|---------|--------|
| Default | `fr` (`APP_LOCALE`) |
| Fallback | `en` (`APP_FALLBACK_LOCALE`) |
| Faker | `fr_FR` |
| `available_locales` | `fr`, `en` |

French remains the default at `/`. Prefixed English routes (`/en/`) are [local-localisation.md](local-localisation.md).

## 3. Storage

| Disk | Root | Visibility |
|------|------|------------|
| `local` | `storage/app/private` | private (Laravel default) |
| `public` | `storage/app/public` | public via `public/storage` |
| `documents` | `storage/app/documents` | private |

`php artisan storage:link` created `public/storage` → `storage/app/public`. The link is gitignored.

## 4. Cache, session, queue, logs

| Setting | Value |
|---------|--------|
| Cache | `file`, prefix `prooftag_catis_` |
| Session | `file` |
| Queue | `sync` (unchanged; charter) |
| Logs | `stack` → `daily`, 14 days, `LOG_LEVEL=debug` |

Cache and session stay on `file`. MySQL cache/session tables are not created in 031 (no migrations before gate 055).

## 5. Verification (2026-10-20)

`php artisan about`:

- Application name `PROOFTAG-CATIS`
- Timezone `Africa/Douala`
- Locale `fr`
- Cache `file`, session `file`, logs `stack / daily`
- `public/storage` **LINKED**

A test `Log::info` wrote `storage/logs/laravel-2026-10-20.log`.

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Core local settings applied (step 030 executed) | 2026-10-20 |

# Local quality tooling

| | |
|--|--|
| **Step** | 038 — Establish quality tooling |
| **Parent** | [standards.md](standards.md) · [system-architecture.md](../architecture/system-architecture.md) |
| **Next** | 074 — Implement solution actions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Pint, Larastan, Pest, and frontend lint pass |

Local gates only. There is no GitHub Actions workflow: the repository has no private remote. Husky is not installed.

## 1. Stack

| Tool | Version | Role |
|------|---------|------|
| Laravel Pint | **1.32.1** (constraint `^1.27`) | PHP style (`pint.json` Laravel preset) |
| Larastan | **3.13.0** (constraint `^3.0`) | Static analysis via PHPStan **2.3.1**, level **5** on `app/` |
| Pest | **5.3.3** (constraint `^5.0`) plus `pestphp/pest-plugin-laravel` **5.0.1** | Test runner (PHPUnit **13.4.3**) |
| ESLint | **10.13.0** plus `@eslint/js` and `globals` | Frontend lint of `resources/js` and `vite.config.js` |

Composer audit: no advisories. `npm audit`: 0 vulnerabilities.

Existing feature tests remain PHPUnit classes; Pest runs them. `tests/Pest.php` binds `Tests\TestCase` for `Feature/`.

## 2. Commands

| Check | Command |
|-------|---------|
| Format (write) | `composer format` or `vendor/bin/pint` |
| Format (CI) | `composer lint` or `vendor/bin/pint --test` |
| Static analysis | `composer analyse` or `vendor/bin/phpstan analyse --memory-limit=1G` |
| Tests | `composer test` or `php artisan test` |
| Frontend lint | `npm run lint` |
| All PHP gates | `composer quality` |

Local command set from this step onward:

`composer install` · Pint `--test` · Larastan · `php artisan test` · `npm ci && npm run build` · `npm run lint`.

## 3. Windows note (phpstan dist)

The PHPStan 2.3.1 dist is a ~28 MB GitHub zipball. Writing that archive into `vendor/composer/tmp-*.zip` can fail with **Permission denied** on this machine (likely antivirus on large zip/phar files).

Workaround used for this install: download the zipball with `curl.exe` into `%TEMP%`, run `composer install` with `COMPOSER_VENDOR_DIR` under `%TEMP%` and a temporary `file://` dist URL in `composer.lock`, then copy `vendor/` back and restore the Packagist/GitHub dist URL. `composer dump-autoload -o` may hang; `composer dump-autoload --no-plugins --no-scripts` without classmap optimisation completes.

Do not commit a `file://` dist URL. Do not add `replace` for `phpstan/phpstan`.

## 4. Out of this step

| Item | Step |
|------|------|
| Conceptual database design | 039 (done — [principal-entities.md](../database/principal-entities.md)) |
| Remote CI / GitHub Actions | After a private remote exists |
| Larastan above level 5 | Later quality steps (175+) |
| Browser tests on critical journeys | Later |

V1 still has exactly eight staff roles. Publish, translate, and update stay separate.

## 5. Verification (2026-10-20)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors (level 5, `app/`)
- `php artisan test` — **19 passed** under Pest 5.3.3
- `npm run lint` — 0 findings
- `composer audit` — no advisories

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Pint, Larastan, Pest, and frontend lint pass (step 038 executed) | 2026-10-20 |

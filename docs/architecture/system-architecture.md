# System architecture

| | |
|--|--|
| **Step** | 023 — Confirm system architecture |
| **Layers** | [layers.md](layers.md) (024) |
| **Environments** | [environments.md](environments.md) (025) |
| **ADRs** | [adr/README.md](adr/README.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

**Modular monolith:** one Laravel application, one MySQL database, one deployment unit.

## 1. Shape

```text
Browser  →  public/ (Blade)     →  Application actions  →  Domain  →  Eloquent / files / SMTP
             Filament /admin  ↗
```

Filament and public controllers call the **same** actions. Neither layer owns publication, translation, or redirect rules.

## 2. Stack (charter)

| Layer | Choice |
|-------|--------|
| App | Laravel 13, PHP 8.4, MySQL 8, Eloquent |
| Public | Blade, Tailwind CSS 4, Vite, vanilla JS, Alpine where useful, Lucide |
| Admin | Filament 5, Livewire 4 |
| Quality | Pest, Larastan, Pint, Git private |
| Host | LWS Starter. No production Docker, Node server, Redis, Horizon, Octane |

Locales: `fr` default (`/…`), `en` (`/en/…`). Timezone: Africa/Douala.

## 3. Planned packages

- `spatie/laravel-permission` + Filament Shield
- `spatie/laravel-activitylog`
- `spatie/laravel-medialibrary`
- `spatie/laravel-sitemap`
- `spatie/laravel-honeypot`
- npm `leaflet` + OpenStreetMap for the centre map (only if verified coordinates exist)

Translation: **relational tables** (ADR-001). Do not mix `spatie/laravel-translatable`.

## 4. Runtime on LWS Starter

- Cache / sessions: `file` or `database`
- Queue: `sync`
- Cron: `php8.4 /path/to/artisan schedule:run` (PHP cron, not HTTP, if Cloudflare is used)
- Mail: LWS SMTP; SPF/DKIM for info@prooftagcatis.com
- Assets: prebuilt `public/build`
- Document root: Laravel `public/` only

Quotas to respect: ~60 concurrent connections, ~45 GB/month bandwidth, 768 MB `memory_limit`, 180s web timeout, 200 GB storage. No SSH on Starter (web terminal).

## 5. Storage

| Disk | Use |
|------|-----|
| `public` | Images |
| `documents` | PDFs / downloads via controlled route |
| `local` / `private` | Admin-only if needed |

No large binaries in MySQL.

## 6. Security boundary

No connection from this CMS to CERTIDOCS, DOSER, CT-VERIF, Video Enforcement, or MINT operational databases.

## 7. CI (from local foundation onward)

`composer install` · Pint `--test` · Larastan · `php artisan test` · `npm ci && npm run build` · `npm run lint`. Local runbook: [local-quality.md](../development/local-quality.md).

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 system architecture (step 023 executed) | 2026-10-20 |

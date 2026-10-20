# Local development setup

| | |
|--|--|
| **Step** | 026 — Prepare the local development machine |
| **Next** | 032 — Configure local email testing |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Verified on this machine |

The Laravel app is created at step 029. The schema database is created at step 031. Local mail trapping is step 032.

## 1. Verified toolchain (2026-10-20)

| Tool | Version | Notes |
|------|---------|--------|
| PHP | **8.4.25** ZTS VC17 x64 | winget `PHP.PHP.8.4`; `php.ini` with required extensions |
| Composer | **2.10.3** | `%LOCALAPPDATA%\Programs\Composer` |
| MySQL (project) | **8.4.9** | `127.0.0.1:3307`, user `root`, empty password (local only) |
| MySQL (pre-existing) | 5.7.28 | Service `MySQL57` on **3306** — do **not** use for this project |
| Node.js | **v24.16.0** | Current LTS line; npm **11.13.0** |
| Git | **2.44.0.windows.1** | |
| IDE | Cursor | |
| DB client | MySQL Workbench 8.0 | Connect to `127.0.0.1:3307` |

PHP extensions present: bcmath, ctype, curl, dom, fileinfo, json, mbstring, openssl, pdo_mysql, tokenizer, xml, zip, intl, gd.

PDO from PHP 8.4 to MySQL 8.4.9 on port 3307: **OK**.

## 2. Local MySQL 8.4 (port 3307)

Binaries: `%LOCALAPPDATA%\Programs\mysql-8.4-extract\PFiles64\MySQL\MySQL Server 8.4\`  
Data dir: `%LOCALAPPDATA%\MySQL84-data\`  
Option file: `%LOCALAPPDATA%\Programs\mysql-8.4-extract\my.ini`

After a reboot, start the instance:

```powershell
powershell -File docs/development/start-mysql84.ps1
```

Installing it as a Windows service was denied without elevation (`MySQL84`). Optional later: run that service install as Administrator.

Do not point Laravel at 3306. Database `prooftag_catis_local` is created at step 031 — see [local-database.md](local-database.md).

## 3. PHP

Loaded ini: winget package directory `php.ini` (copied from `php.ini-development`).  
`date.timezone = Africa/Douala`.

Open a **new** terminal if `php` is not found (PATH was updated).

## 4. Not in this step

| Item | Step |
|------|------|
| Private Git remote / `main` | 027 |
| `.gitignore` conventions | 028 (done) |
| `composer create-project` Laravel 13 | 029 |
| `.env`, locales, cache | 030 |
| Create `prooftag_catis_local` | 031 (done) |
| Mailpit / Mailhog | 032 |

Never send development mail to real recipients.

## 5. After Laravel exists (029+)

```bash
cp .env.example .env
php artisan key:generate
# DB_HOST=127.0.0.1  DB_PORT=3307  DB_DATABASE=prooftag_catis_local
composer install
npm install
php artisan migrate
npm run dev
```

Timezone / locale (step 030): `APP_TIMEZONE=Africa/Douala`, default `fr`, fallback `en`.

## 6. Completion of step 026

PHP 8.4, Composer 2, MySQL 8.4 (project port 3307), Node, Git, IDE, and a DB client are installed and checked on this machine.

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Verified local toolchain (step 026 executed) | 2026-10-20 |

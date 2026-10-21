# Local MySQL database

| | |
|--|--|
| **Step** | 031 — Configure the local MySQL database |
| **Parent** | [local-setup.md](local-setup.md) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Connectivity verified |

No content schema is created here. Step 033 added Laravel identity tables (`users`) for Filament login. Principal entities: [principal-entities.md](../database/principal-entities.md) (039). Content migrations wait for gate **055**. Mail trapping is [local-mail.md](local-mail.md). Filament is [local-filament.md](local-filament.md).

## 1. Instance (do not use 3306)

| Item | Value |
|------|--------|
| Server | MySQL **8.4.9** |
| Host | `127.0.0.1` |
| Port | **3307** |
| User | `root` |
| Password | empty (local only) |
| Database | `prooftag_catis_local` |
| Charset / collation | `utf8mb4` / `utf8mb4_unicode_ci` |

MySQL 5.7.28 on port **3306** (`MySQL57`) is a different instance. Laravel must not connect there.

Start the project instance after a reboot:

```powershell
powershell -File docs/development/start-mysql84.ps1
```

Workbench: `127.0.0.1:3307`.

## 2. Laravel connection

`.env` is gitignored. [`.env.example`](../../.env.example) records:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=prooftag_catis_local
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

`config/database.php` defaults to `mysql` on port **3307** and database `prooftag_catis_local`, so a missing env value cannot fall back onto 3306.

PHPUnit uses a separate database, `prooftag_catis_testing`, on the same instance ([local-permissions.md](local-permissions.md)). Do not run `RefreshDatabase` against `prooftag_catis_local`.

Cache, session, and queue stay `file` / `sync` (step 030). This step does not create `cache` or `sessions` tables.

## 3. Verification (2026-10-20)

`php artisan db:show`: MySQL **8.4.9**, host `127.0.0.1`, port **3307**, database `prooftag_catis_local`, **0 tables**.

`php artisan db:monitor`: connection `mysql` **OK**.

Query through Eloquent: `VERSION() = 8.4.9`, `@@port = 3307`, charset `utf8mb4`.

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Local MySQL connectivity verified (step 031 executed) | 2026-10-20 |

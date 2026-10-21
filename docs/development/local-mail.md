# Local email testing

| | |
|--|--|
| **Step** | 032 — Configure local email testing |
| **Parent** | [laravel-app.md](laravel-app.md) |
| **Next** | 041 — Define solution entities |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Mail cannot reach real recipients |

Local mail goes only to **Mailpit** on localhost. Do not point `MAIL_HOST` at LWS or any public SMTP. Filament is [local-filament.md](local-filament.md).

## 1. Mailpit (localhost only)

| Item | Value |
|------|--------|
| Binary | Mailpit **1.31.6** (`winget` `axllent.mailpit`) |
| SMTP | `127.0.0.1:1025` |
| UI | http://127.0.0.1:8025 |
| Bind | localhost only — not `0.0.0.0` |

After a reboot:

```powershell
powershell -File docs/development/start-mailpit.ps1
```

## 2. Laravel

`.env` is gitignored. [`.env.example`](../../.env.example) records:

```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@prooftag-catis.test"
```

`config/mail.php` still defaults `MAIL_MAILER` to `log` if the env key is missing, so a stripped `.env` cannot talk to the internet. SMTP port default is **1025** (Mailpit), not 2525.

The From address uses the reserved `.test` TLD. Production `info@prooftagcatis.com` is never used as From in local `.env`.

## 3. Verification (2026-10-20)

`php artisan about`: Mail driver **smtp**.

A raw message addressed to `info@prooftagcatis.com` was sent through Laravel. Mailpit API `GET /api/v1/messages` showed:

- From: `noreply@prooftag-catis.test`
- To: `info@prooftagcatis.com`
- Subject: `032 Mailpit trap`
- Captured at `127.0.0.1:8025`

Nothing left this machine.

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Local mail cannot reach real recipients (step 032 executed) | 2026-10-20 |

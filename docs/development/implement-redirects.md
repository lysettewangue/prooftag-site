# Implement redirects locally

| | |
|--|--|
| **Step** | 167 — Implement redirects locally |
| **Parent** | [redirect-register.md](../legacy/redirect-register.md) · [ditros-ct-url.md](../legacy/ditros-ct-url.md) · [local-redirects-admin.md](local-redirects-admin.md) |
| **Next** | 168–173 — [Test suite](test-suite-168-173.md) (COMPLETED) · 174 static analysis |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | **Working locally** — 23 active rows; HTTP 301 before routing |

## 1. What shipped

| Piece | Role |
|-------|------|
| `LegacyRedirectSeeder` | Upserts [redirect-register.csv](../legacy/redirect-register.csv) on `(from_host, from_path)` |
| `ApplyLegacyRedirect` | Global middleware (prepend) → `RedirectResolver` → `Location: {canonical_host}{to_path}` |
| `DatabaseSeeder` | Calls the seeder after media migration |

Canonical base: `config('cms.canonical_host')` → `https://www.prooftagcatis.com`. Admin / Livewire / storage / build / `/up` are skipped. Same-host www loops (path already `to_path`) are skipped.

Middleware is **global**, not only on the `web` group: legacy paths with no Laravel route must still 301 (e.g. `/solutions/ditros-ct`).

## 2. Seed

```powershell
php artisan db:seed --class=LegacyRedirectSeeder
```

Idempotent. Expect **23** active rows (includes DITROS → `/controle-technique`).

## 3. Manual check (local)

`php artisan serve` on `127.0.0.1:8000`. Spoof legacy `Host` and read status + `Location` (do not require following to production DNS).

| Host | Path | Expect Location |
|------|------|-----------------|
| `site.prooftagcatis.com` | `/solutions/ditros-ct` | `https://www.prooftagcatis.com/controle-technique` |
| `site.prooftagcatis.com` | `/catis` | `…/a-propos` |
| `site.prooftagcatis.com` | `/solutions` | `…/produits` |
| `www.prooftagcatis.com` | `/services` | `…/produits/certidocs-ct` |
| `www.prooftagcatis.com` | `/partenaires/centres-de-controle` | `…/centres-de-controle` |
| `net.prooftagcatis.com` | `/` | `https://www.prooftagcatis.com/` |
| `prooftagcatis.com` | `/` | `https://www.prooftagcatis.com/` |

Plain local browse (`Host: 127.0.0.1`) is unchanged — no register match.

```powershell
curl.exe -sI -H "Host: site.prooftagcatis.com" http://127.0.0.1:8000/solutions/ditros-ct
```

## 4. Verification (2026-10-29)

- Seeder: 23 upserted; DITROS `to_path=/controle-technique`
- Spot matrix (10 Host/path pairs): all **301** + expected `Location`
- Local `/a-propos` without legacy Host: **200**

## 5. Out of this step

| Item | Step |
|------|------|
| Staging / production host panels | 202 / 218 / 231 |
| Automated redirect suites | 168+ |
| net catch-all beyond homepage row | Ops / server at cutover |

# Migrate product content

| | |
|--|--|
| **Step** | 152 — Migrate product content |
| **Parent** | [151 — Corporate](migrate-corporate-content.md) · [product-baselines.md](../content/product-baselines.md) · [products.md](../charter/products.md) |
| **Next** | 153 — Migrate expertise content → [migrate-expertise-content.md](migrate-expertise-content.md) (COMPLETED) · 154 CT |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Five products migrated; only CERTIDOCS CT published |

## 1. Seeder

```powershell
php artisan db:seed --class=ProductContentMigrationSeeder
php artisan cache:clear
```

Runs after `ProductSeeder` in `DatabaseSeeder`. Idempotent.

## 2. Publication matrix

| Code | Status | Rule |
|------|--------|------|
| `CERTIDOCS_CT` | **Published** | Verified rewrite of 015 themes; no DITROS-CT; MINT authority preserved |
| `CERTIDOCS_CT_REPORTING` | Draft | Hidden until VER-06 |
| `CT_VERIF` | Draft | Needs VER-04 + `external_url` (VER-11) before Published |
| `DOSER` | Draft | Needs VER-05 + `external_url` (VER-11) before Published |
| `VIDEO_ENFORCEMENT` | Draft | Hidden until VER-07; no ANPR / ticketing / live cameras |

Reserved paths stay in the CMS for drafts; public index, homepage cards, search, and sitemap omit unpublished products.

## 3. CERTIDOCS CT copy

Marketing themes only: centre server / test-bench data, secured PV and decisions, vignette security model (bubble code, QR), process-quality support. Operational app is out of scope on this site.

## 4. Manual check

1. `/produits` and `/en/products` → 200; only CERTIDOCS CT listed.
2. `/produits/certidocs-ct` → 200; no “DITROS-CT”.
3. `/produits/ct-verif`, `/doser`, reporting, video-enforcement → **404**.
4. Homepage product band shows CERTIDOCS CT when published products exist.
5. VER-04–07 / VER-11 remain **Open**.

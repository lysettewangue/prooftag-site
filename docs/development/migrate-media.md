# Migrate media

| | |
|--|--|
| **Step** | 159 — Migrate media |
| **Parent** | [158 — Editorial](migrate-editorial-content.md) · [media-entities.md](../database/media-entities.md) · AUD-028 · [media-classification.csv](../content/media-classification.csv) |
| **Next** | 160 — Verify migrated content → [verify-migrated-content.md](verify-migrated-content.md) (COMPLETED) · 161 FR review |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | 50 binaries pulled; classified; brand + draft product marks attached; DITROS rejected |

## 1. Seeder

```powershell
php artisan db:seed --class=MediaContentMigrationSeeder
php artisan cache:clear
```

Runs after `EditorialContentMigrationSeeder` in `DatabaseSeeder`. Idempotent. Expects binaries under `docs/legacy/archive/2026-10-20/media/` (logos) and `…/media/binaries/` (full WP set).

## 2. Classification (50 WP items)

Register: [media-classification.csv](../content/media-classification.csv).

| Status | Count (approx.) | CMS action |
|--------|----------------:|------------|
| **current** | brand logos + favicon | SiteSetting `images` + `public/images/brand/*` |
| **verification** | CT-VERIF / DOSER marks; team photos | Product marks on **draft** products only; team held (VER-02) |
| **archive** / **historical** | sliders, stock, PV | Binaries kept; JPEG optimised fixtures for PV/team |
| **reject** | DITROS marks, UI chrome, screenshots | Not attached |

DITROS never attaches to corporate chrome or CERTIDOCS CT. Empty `rights` still means “do not use publicly”.

## 3. Attached owners

| Owner | File | Public? |
|-------|------|---------|
| SiteSetting `#1` | `logo-prooftag-catis.png` (AUD-028) | Yes — header/OG fallback via Spatie + static brand copy |
| Product `CT_VERIF` | CT-VERIF logo | No — draft until VER-04 |
| Product `DOSER` | DOSER mark (`Image1.png`) | No — draft until VER-05 |
| Resource PDFs | (step 157) | Yes — unchanged |

## 4. Manual check

1. `/` → logo from `/images/brand/logo.png`; `og:image` points at SiteSetting media (canonical host).
2. `/admin/media` → SiteSetting logo + draft product marks + resource PDFs listed; rights non-empty on brand/product marks.
3. `/produits/ct-verif` and `/produits/doser` → still **404** (draft).
4. No DITROS mark in header/footer or CERTIDOCS CT page.
5. Classification CSV covers all 50 `site-media-p1.json` ids.

# Migrate retained news and articles

| | |
|--|--|
| **Step** | 158 — Migrate retained news and articles |
| **Parent** | [157 — Resources](migrate-resource-documents.md) · [editorial-entities.md](../database/editorial-entities.md) · AUD-027 |
| **Next** | 159 — Migrate media → [migrate-media.md](migrate-media.md) (COMPLETED) · 160 verify |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | Categories Published; 1 news + 1 article Published; draft sample kept off public |

## 1. Seeder

```powershell
php artisan db:seed --class=EditorialContentMigrationSeeder
php artisan cache:clear
```

Runs after `RepresentativeEditorialSeeder` in `DatabaseSeeder`. Legacy blog had **zero** posts (AUD-027); this rewrites the 110 sample rows into publishable copy aligned with migrated CT / CERTIDOCS CT facts — not a scrape of live articles.

## 2. Rows

| Kind | FR slug | EN slug | Notes |
|------|---------|---------|-------|
| Category | (sort 10 / 20) | — | Both categories Published |
| **news** | `reconstruction-du-site-corporate` | `corporate-website-rebuild` | Site rebuild announcement; links CT / centres |
| **article** | `themes-controle-technique-securise` | `secured-technical-inspection-themes` | CERTIDOCS CT themes; tagged product + expertises |
| draft | `nouvelle-ct` | — | Left **Draft** — must stay 404 |

## 3. Manual check

1. `/actualites` → 200; both published titles visible; draft absent.
2. `/actualites/reconstruction-du-site-corporate` and `/actualites/themes-controle-technique-securise` → 200.
3. `/en/news/corporate-website-rebuild` → 200.
4. `/actualites/nouvelle-ct` → **404**.
5. Filters `?kind=news` / `?kind=article` still work.

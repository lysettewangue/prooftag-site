# Migrate expertise content

| | |
|--|--|
| **Step** | 153 — Migrate expertise content |
| **Parent** | [152 — Products](migrate-product-content.md) · [expertise-entities.md](../database/expertise-entities.md) · AUD-010 / AUD-015–018 / AUD-026 · REC-09 |
| **Next** | 154 — Migrate technical inspection → [migrate-technical-inspection-content.md](migrate-technical-inspection-content.md) (COMPLETED) · 155 equipment |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Five domains rewritten and **Published** FR/EN |

## 1. Seeder

```powershell
php artisan db:seed --class=ExpertiseContentMigrationSeeder
php artisan cache:clear
```

Runs after `ExpertiseSeeder` in `DatabaseSeeder`. Idempotent. Republishes a previously archived row if needed.

## 2. Domains

| Code | FR slug | Published |
|------|---------|-----------|
| `RESEARCH_DEVELOPMENT` | `recherche-et-developpement` | Yes |
| `TECHNICAL_SUPPORT` | `support-technique` | Yes |
| `DATA_ANALYSIS_REPORTING` | `analyse-donnees-reporting` | Yes |
| `DOCUMENT_SECURITY` | `securite-documentaire` | Yes (six axes from legacy index) |
| `ROAD_SAFETY_PREVENTION` | `securite-prevention-routiere` | Yes |

Titles stay domain names — never product `public_name`. Copy is a rewrite of site expertise pages, not a scrape of broken legacy `/en`.

## 3. Product links

CERTIDOCS CT is synced to: Technical support, Document security, Road safety & prevention, Data analysis & reporting (optional `product_expertise`).

## 4. Manual check

1. `/expertises` and `/en/expertise` → 200 with five cards.
2. Each FR/EN detail slug → 200 with summary + body.
3. No product brand used as expertise title.
4. Homepage expertise band lists the five published domains.
5. CERTIDOCS CT detail shows related expertises when linked.

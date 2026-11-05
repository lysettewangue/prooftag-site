# Migrate equipment content

| | |
|--|--|
| **Step** | 155 — Migrate equipment content |
| **Parent** | [154 — CT hub / points](migrate-technical-inspection-content.md) · AUD-006 · [public-equipment.md](public-equipment.md) |
| **Next** | 156 — Migrate centres → [migrate-centre-directory.md](migrate-centre-directory.md) (COMPLETED) · 157 resources |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Four equipment rows Published (3 current + 1 legacy) |

## 1. Seeder

```powershell
php artisan db:seed --class=EquipmentContentMigrationSeeder
php artisan cache:clear
```

Runs after `EquipmentSeeder` in `DatabaseSeeder`. Idempotent.

## 2. Catalogue

| Order | Lifecycle | FR slug | Role |
|------:|-----------|---------|------|
| 1 | current | `banc-de-tests` | Test-bench measurements → centre system |
| 2 | current | `tablette-controle-visuel` | Structured visual inspection |
| 3 | current | `serveur-de-centre` | Analyse, PV/decision, vignette prep |
| 4 | legacy | `workflow-legacy-2020` | Historical 2020 workflow note — not “available now” |

Copy is process pedagogy from AUD-006, not a commercial fleet / SKU list. No DITROS-CT. No invented prices or stock.

## 3. Manual check

1. `/controle-technique/equipements` → 200; current items before legacy.
2. Each detail slug FR/EN → 200.
3. Legacy page states it is historical.
4. CT hub equipment teaser links to published current items.

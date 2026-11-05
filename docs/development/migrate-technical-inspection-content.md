# Migrate technical inspection content

| | |
|--|--|
| **Step** | 154 — Migrate technical inspection content |
| **Parent** | [153 — Expertise](migrate-expertise-content.md) · AUD-001 / AUD-006 · [extracts.md](../legacy/archive/2026-10-20/extracts.md) |
| **Next** | 155 — Migrate equipment → [migrate-equipment-content.md](migrate-equipment-content.md) (COMPLETED) · 156 centres |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | CT hub + 12 security points Published FR/EN |

## 1. Seeder

```powershell
php artisan db:seed --class=TechnicalInspectionContentMigrationSeeder
php artisan cache:clear
```

Runs after `SecurityPointSeeder` in `DatabaseSeeder`. Idempotent.

## 2. What was migrated

| Record | Source | Result |
|--------|--------|--------|
| Page `technical_inspection` | AUD-006 www workflow | Published hub overview + CTAs (points, equipment, centres) |
| Twelve `SecurityPoint` rows | AUD-001 titles (already frozen) + short bodies | All **Published** |

No DITROS-CT naming. CATIS supports process quality; MINT authority is preserved.

## 3. Out of this step

| Item | Step |
|------|------|
| Equipment catalogue copy | **155** |
| Centre directory import | **156** |
| Resource PDFs / VER-08 labels | **157** |

## 4. Manual check

1. `/controle-technique` and `/en/technical-inspection` → 200 with workflow text.
2. Hub accordion / teaser shows published points.
3. `/controle-technique/points-de-securisation` → 200 with 12 numbered panels and bodies.
4. Page source does not contain `DITROS-CT` as a product name (only the explicit “not used” disclaimer is fine).

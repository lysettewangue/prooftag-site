# Migrate the centre directory

| | |
|--|--|
| **Step** | 156 — Migrate the centre directory |
| **Parent** | [155 — Equipment](migrate-equipment-content.md) · AUD-007 · [extracts.md](../legacy/archive/2026-10-20/extracts.md) · VER-03 |
| **Next** | 157 — Migrate resources → [migrate-resource-documents.md](migrate-resource-documents.md) (COMPLETED) · 158 news |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Captured operator×city rows imported; verification statuses preserved |

## 1. Seeder

```powershell
php artisan db:seed --class=CentreDirectoryMigrationSeeder
php artisan cache:clear
```

Runs after `RepresentativeCentreSeeder` in `DatabaseSeeder`. Idempotent upsert on `(operator_name, city)`.

## 2. Rules

| Rule | Detail |
|------|--------|
| Source | www `/partenaires/centres-de-controle/` capture (incomplete vs ~35 claim) |
| Identity | Same operator in two cities = **two rows** |
| Default status | **Unverified** for new imports |
| Preserve | Existing `verification_status`, `last_verified_at`, `verified_by`, phone/email/geo already set |
| Invent nothing | No phone, email, or coordinates invented |
| Public | Active only (directory + detail + map pins when coords exist) |
| VER-03 | Remains **Open** — remaining centres / contacts / open-closed still unresolved |

## 3. Inventory (capture upsert)

| Status | Count | Notes |
|--------|------:|-------|
| Active (preserved) | 3 | CADCIA Douala, HYDRAC Douala Bassa, AUTO VISION Yaoundé (from 109) |
| Unverified | 14 | SATELLITE NGONO Garoua + extra city rows (AUTO VISION, SATELLITE NGONO, WORLD CAR INDUSTRY, GILBERT-JOSEPHINE, DIAGNOSTIC AUTOMOBILE) |
| Capture total | 17 | Incomplete vs ~35 claim — VER-03 |

The seeder does **not** delete other centres already in the database (e.g. local test rows).

## 4. Manual check

1. `/centres-de-controle` → 200; only Active centres listed.
2. Active detail (e.g. `/centres-de-controle/auto-vision-yaounde`) → 200.
3. Unverified slug (e.g. `/centres-de-controle/auto-vision-douala`) → **404**.
4. Admin still lists Unverified rows for VER-03 work.

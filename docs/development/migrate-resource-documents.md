# Migrate resource documents

| | |
|--|--|
| **Step** | 157 — Migrate resource documents |
| **Parent** | [156 — Centres](migrate-centre-directory.md) · [resource-entities.md](../database/resource-entities.md) · VER-08 |
| **Next** | 158 — Migrate retained news and articles → [migrate-editorial-content.md](migrate-editorial-content.md) (COMPLETED) · 159 → [migrate-media.md](migrate-media.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Current vs historical separated; both Published with placeholder PDFs |

## 1. Seeder

```powershell
php artisan db:seed --class=ResourceContentMigrationSeeder
php artisan cache:clear
```

Runs after `LibraryDocumentSeeder` in `DatabaseSeeder`. Writes PDF fixtures under `database/seeders/fixtures/resources/` if missing.

## 2. Rows

| Status | FR slug | Role |
|--------|---------|------|
| **current** | `guide-processus-controle-technique` | Pedagogical CT process guide; linked to CERTIDOCS CT; not a MINT decree |
| **historical** | `reforme-vignettes-2020` | 2020 vignette reform labelled **historical** (VER-08 separation) |

Placeholder PDFs enable `mayEnterPublished` and controlled download. Rights note says replace with official binaries when Legal supplies them. VER-08 stays **Open** for additional official texts.

## 3. Manual check

1. `/ressources` → 200; both rows with explicit status text.
2. `?status=current` / `?status=historical` → filtered lists.
3. Detail pages → 200; download control present.
4. `/ressources/{slug}/telecharger` → file response when PDF attached.
5. Historical is never labelled “current”.

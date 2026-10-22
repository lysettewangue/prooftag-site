# V1 data-model freeze

| | |
|--|--|
| **Step** | 113 — Freeze the V1 data model |
| **Parent** | [design-review.md](design-review.md) (055) · [model-review.md](model-review.md) (112) |
| **Next** | 114 — Prepare the UI/UX brief |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | **Gate passed.** Further schema change is exceptional |

This freeze covers the implemented CMS schema (056–069), domain rules (070–087), Filament modules (088–106), and representative drafts (107–110). It does **not** close VER-01–13 and does **not** publish those facts.

## 1. Frozen catalogues

| Item | Freeze |
|------|--------|
| Products | Exactly five codes. No DITROS-CT entity. |
| Staff roles | Exactly eight. Publish ≠ translate ≠ update. |
| Pages | Eleven `PageCode` values. No extra pages. |
| Security points | Twelve `sort_order` values 1–12. |
| Regions | Ten Cameroon codes. |
| Settings | Singleton `id = 1`. Identity fields not CMS-editable. |
| Locales | `fr` default unprefixed; `en` under `/en`. No locale table. |
| Publication | A-09 on publishable bases. Centres use `verification_status`. |
| Translations | Parent + FR/EN tables (ADR-001). Media alt in `custom_properties`. |
| Operational systems | No FK. Outbound URLs on Product only. |

CMS tables remain those listed in [local-schema.md](../development/local-schema.md) and asserted by `DatabaseStructureTest`.

## 2. Exceptional change

After this gate, adding or renaming a table/column, a sixth product, a ninth role, or a thirteenth security point requires:

1. A new version of the affected spec under `docs/database/` or `docs/charter/`.
2. A recorded design exception on this freeze document.
3. Then a migration.

Content edits, seeds, Filament copy, and public Blade (123+) are **not** schema changes.

## 3. Still later, without schema change

| Work | Step |
|------|------|
| UI/UX then public templates | 114–150 |
| Close VER rows / migrate retained copy | 151–163 |
| Redirect cutover / DITROS URL | 164–166 |
| LWS staging and production | 187+ |

## 4. PHPUnit

```powershell
php artisan test --filter=ModelFreezeTest
php artisan test --filter=DatabaseStructureTest
php artisan test
```

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=ModelFreezeTest` — 2 passed
- `php artisan test` — 284 passed

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | V1 data model frozen (step 113 executed). Further schema change is exceptional. | 2026-10-22 |

# Controlled content blocks

| | |
|--|--|
| **Step** | 105 — Controlled content blocks |
| **Parent** | [content-blocks.md](../admin/content-blocks.md) · [corporate-entities.md](../database/corporate-entities.md) |
| **Next** | 106 — Test admin permissions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Approved block schema live on the ten corporate pages |

Editors compose pages from **approved blocks only**. Brand, type, spacing, and primary navigation stay in code. Empty `blocks` is valid.

## 1. Stored JSON

Each translation stores an ordered list. Filament Builder `{type, data}` is flattened to `{type, ...payload}`. Unknown types and brand keys (`color`, `font`, `margin`, `padding`, `width`, `nav`, `brand`, …) throw `DomainRuleViolation`.

| Type | Purpose |
|------|---------|
| `rich_text` | Copy |
| `text_and_media` | Copy plus optional media id |
| `gallery` | Caption only — layout in code |
| `timeline` | Year / text items |
| `statistics` | Label / value. No live counters (AUD-013) |
| `faq` | Question / answer |
| `document_list` | Intro; documents come from the library |
| `specifications` | Label / value rows |
| `related_solutions` | Product ids from the frozen five |
| `related_resources` | Intro |
| `partners` | Intro |
| `team` | Intro |
| `call_to_action` | Label + site path |

The Contrôle technique hub stays on `TechnicalInspectionResource` with a single `rich_text` overview. That payload still passes `ContentBlocks::normalize`.

## 2. Filament

`/admin/pages` under **Corporate**. Eleven frozen `PageCode` values; this module lists ten (not the CT hub). `canCreate()` / `canDelete()` are false even for Super Admin. Slugs stay frozen to the sitemap. VER-01 / VER-12 / VER-13 helper text: do not publish an unresolved fact.

Communications domain: Super Admin, Admin, Content Manager, Communications Editor, Translator, Reviewer. Technical Editor and Support Agent receive **403**.

Thin resource: `UpdatePage`, `TranslatePage`, `TransitionPublication`.

## 3. PHPUnit

```powershell
php artisan test --filter=ContentBlocksTest
php artisan test --filter=SitePagesAdminTest
```

## 4. Out of this step

| Item | Step |
|------|------|
| Permission smoke gate | 106 |
| Representative product copy | 107 |
| Public Blade rendering of blocks | 123+ |

## 5. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test` — 275 passed
- Browser: `/admin/pages` lists ten frozen corporate pages (not the CT hub); About edit offers the thirteen approved blocks; slugs stay disabled

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Controlled content blocks implemented (step 105 executed) | 2026-10-22 |

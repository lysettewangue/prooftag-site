# Representative technical content

| | |
|--|--|
| **Step** | 108 — Enter representative technical content |
| **Parent** | [verification-register.md](../content/verification-register.md) · [extracts.md](../legacy/archive/2026-10-20/extracts.md) |
| **Next** | 109 — Enter representative centres |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Points, equipment, and one historical resource seeded as Draft |

Documenting security is not inventing 2020-as-current.

| Record | Rule |
|--------|------|
| Twelve security points | Titles already from the www list, rewritten (AUD-001). Body is a rewrite-required note (AUD-006). Still Draft. |
| Equipment | Three **current** drafts (test bench, visual-inspection tablet, centre server) plus one **legacy** 2020 workflow. Independent FR/EN slugs. Staff may still create more. |
| Resource | One **historical** VER-08 placeholder (2020 vignette reform). No PDF on `documents`, so `mayEnterPublished` is false. |

## 1. PHPUnit

```powershell
php artisan test --filter=RepresentativeTechnicalContentTest
php artisan test --filter=EquipmentAdminTest
php artisan test --filter=ResourceLibraryAdminTest
```

## 2. Out of this step

| Item | Step |
|------|------|
| Publish points / equipment / resources | After VER-08 and related rows close |
| Representative centres | 109 |

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Representative technical drafts seeded (step 108 executed) | 2026-10-22 |

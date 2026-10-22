# Admin permissions gate

| | |
|--|--|
| **Step** | 106 — Test admin permissions |
| **Parent** | [roles.md](../admin/roles.md) · [services-catalog.md](../backend/services-catalog.md) |
| **Next** | 107 — Enter representative products |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Gate. Each of the eight roles smoke-tested on every Filament module |

GET 200 vs 403. Authorization stays in policies. Super Admin still cannot create extra pages/products/expertises/security points or hard-delete no-delete entities (`canCreate` / `canDelete` overrides).

## 1. Module access (list or singleton edit)

| Module | Super | Admin | Content Mgr | Tech | Comms | Translator | Reviewer | Support |
|--------|:-----:|:-----:|:-----------:|:----:|:-----:|:----------:|:--------:|:-------:|
| Dashboard `/admin` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Settings | ✓ | ✓ | ✓ | | | ✓ | | |
| Pages | ✓ | ✓ | ✓ | | ✓ | ✓ | ✓ | |
| Products / Expertises / CT hub / Points / Equipment / Resources | ✓ | ✓ | ✓ | ✓ | | ✓ | ✓ | |
| Centres | ✓ | ✓ | ✓ | ✓ | | ✓ | | ✓ |
| Team / Partners / Posts / Categories / Careers | ✓ | ✓ | ✓ | | ✓ | ✓ | ✓ | |
| Media | ✓ | ✓ | ✓ | | | ✓ | | |
| Contact inbox | ✓ | ✓ | | | ✓ | | | ✓ |
| Redirects / Users / Audit | ✓ | ✓ | | | | | | |
| Shield roles | ✓ | | | | | | | |

Content Manager reaches settings/media via `translate`. Reviewer cannot list centres. Communications `publish` does not open technical catalogues.

## 2. PHPUnit

```powershell
php artisan test --filter=AdminPermissionsGateTest
php artisan test
```

## 3. Out of this step

| Item | Step |
|------|------|
| Representative product copy | 107 |
| Public site | 123+ |

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Admin permissions gate passed (step 106 executed) | 2026-10-22 |

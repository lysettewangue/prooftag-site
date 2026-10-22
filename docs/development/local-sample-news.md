# Sample news and articles

| | |
|--|--|
| **Step** | 110 — Create sample news and articles |
| **Parent** | [local-editorial-admin.md](local-editorial-admin.md) · [editorial-entities.md](../database/editorial-entities.md) |
| **Next** | 111 — Test French and English management |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Two categories and two posts seeded as Draft |

`RepresentativeEditorialSeeder` loads realistic CMS rows for local work. The legacy blog had **zero** posts (AUD-027); this is not a migration of live articles. Career is not a Post. Drafts are never public. `author_id` stays null (no public byline). VER-01–13 stay **Open**.

| Kind | FR slug | Notes |
|------|---------|-------|
| Category | `controle-technique` | Label only. Draft. |
| Category | `institutionnel` | Label only. Draft. |
| News | `reconstruction-du-site-corporate` | Process placeholder. Independent EN slug. |
| Article | `themes-controle-technique-securise` | 015 CERTIDOCS CT themes, rewrite required. Tagged to CERTIDOCS CT. CATIS is not the MINT. DITROS-CT is not a product. |

## 1. PHPUnit

```powershell
php artisan test --filter=RepresentativeEditorialTest
php artisan test --filter=EditorialAdminTest
php artisan test
```

## 2. Out of this step

| Item | Step |
|------|------|
| FR/EN slug, publication, missing translation tests | 111 |
| Publish any post | After editorial review; do not publish VER-open facts |
| Public listing / article page | 141–142 |

## 3. Verification (2026-10-22)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=RepresentativeEditorialTest` — 1 passed
- `php artisan test --filter=EditorialAdminTest` — 13 passed
- `php artisan test` — 276 passed
- Browser: `/admin/posts` and `/admin/categories`

## 4. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Sample news and articles seeded (step 110 executed) | 2026-10-22 |

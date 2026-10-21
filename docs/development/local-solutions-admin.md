# Solutions admin

| | |
|--|--|
| **Step** | 089 — Solutions admin |
| **Parent** | [local-admin.md](local-admin.md) · [solution-entities.md](../database/solution-entities.md) · [products.md](../charter/products.md) |
| **Next** | 091 — Technical inspection admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Five product rows editable; no sixth product |

Filament module at `/admin/products`. Exactly **five** seeded solutions. Staff cannot create or delete a row. Visibility is publication, not removal. DITROS-CT is not a product.

The resource is thin: `UpdateProduct`, `TranslateProduct`, and `TransitionPublication` (submit / approve / publish / schedule / archive).

## 1. What staff can edit

| Field | Rule |
|-------|------|
| `code`, `public_name`, `slug_fragment`, sort 1–5 | Frozen. Disabled. |
| Translation `slug` | Frozen to `slug_fragment` in FR and EN. |
| `external_url` | Editable. Required before publish for CT-VERIF and DOSER. |
| Expertises | Optional links to the five expertise rows (module 090). |
| Summary, body, CTA label, meta | Per locale via `TranslateProduct`. Product names stay branded. |

Header actions follow A-09. Draft cannot jump to Published.

## 2. Who may open it

| Role | List / edit | Update copy & URL | Translate | Publish |
|------|-------------|-------------------|-----------|---------|
| Super Admin / Admin | yes | yes | yes | yes |
| Content manager | yes | yes | yes | no |
| Technical editor | yes | yes | no | no |
| Translator | yes | no | yes | no |
| Reviewer / publisher | yes | no | no | yes |
| Communications editor / Support | no | no | no | no |

## 3. PHPUnit

```powershell
php artisan test --filter=ProductsAdminTest
php artisan test
```

## 4. Out of this step

| Item | Step |
|------|------|
| Expertise module | 090 |
| Technical inspection cluster | 091–093 |
| Public product templates | 123+ |

## 5. Verification (2026-10-21)

- `vendor/bin/pint --test` — passed
- `vendor/bin/phpstan analyse --memory-limit=1G` — no errors
- `php artisan test --filter=ProductsAdminTest` — passed
- Browser: `/admin/products` lists five solutions; identity fields disabled

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Solutions admin implemented (step 089 executed) | 2026-10-21 |

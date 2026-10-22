# Representative products

| | |
|--|--|
| **Step** | 107 — Enter representative products |
| **Parent** | [product-baselines.md](../content/product-baselines.md) · [products.md](../charter/products.md) |
| **Next** | 108 — Enter representative technical content |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Five solutions seeded as Draft from 015 themes |

`ProductSeeder` fills FR/EN summary and body from the frozen baselines. Publication status stays **Draft**. CT-VERIF and DOSER keep `external_url` null (VER-04, VER-05, VER-11). No invented capability bullets. DITROS-CT is not a product. VER-04–07 / VER-11 stay **Open**.

## 1. PHPUnit

```powershell
php artisan test --filter=RepresentativeProductsTest
php artisan test --filter=DatabaseStructureTest
```

## 2. Out of this step

| Item | Step |
|------|------|
| Publish any solution | After the matching VER row closes |
| Sample news | 110 |

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Representative product drafts seeded (step 107 executed) | 2026-10-22 |

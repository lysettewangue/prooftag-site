# Representative centres

| | |
|--|--|
| **Step** | 109 — Enter representative centres |
| **Parent** | [verification-register.md](../content/verification-register.md) · [extracts.md](../legacy/archive/2026-10-20/extracts.md) |
| **Next** | 111 — Test French and English management |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Small verified subset seeded. VER-03 remains Open |

`RepresentativeCentreSeeder` loads four rows from the 2026-10-20 www capture. Same operator in two cities would be two rows; this subset is one city each. Ten Cameroon regions stay frozen. Phone, email, and coordinates are **null** (not invented).

| Operator | City | Region | Status |
|----------|------|--------|--------|
| CADCIA SARL | Douala | Littoral | Active |
| HYDRAC | Douala Bassa | Littoral | Active |
| AUTO VISION | Yaoundé | Centre | Active |
| SATELLITE NGONO | Garoua | Nord | Unverified |

This is local representative data, not a claim that the public directory of ~35 centres is resolved. VER-03 stays **Open**.

## 1. PHPUnit

```powershell
php artisan test --filter=RepresentativeCentresTest
php artisan test --filter=CentresAdminTest
php artisan test --filter=DatabaseStructureTest
```

## 2. Out of this step

| Item | Step |
|------|------|
| Close VER-03 / import the full list | Later editorial work |
| Sample news | 110 (done — [local-sample-news.md](local-sample-news.md)) |

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Representative centre subset seeded (step 109 executed) | 2026-10-22 |

# Migrate corporate content

| | |
|--|--|
| **Step** | 151 — Migrate corporate content |
| **Parent** | [150 — Public frontend gate](public-frontend-gate.md) · [AUD / VER](../content/verification-register.md) · [REC](../content/reconciliation.md) |
| **Next** | 152 — Migrate product content → [migrate-product-content.md](migrate-product-content.md) (COMPLETED) · 153 expertise |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | Approved About / contact / legal published; VER-blocked rows stay unpublished |

## 1. Seeder

```powershell
php artisan db:seed --class=CorporateContentMigrationSeeder
php artisan cache:clear
```

Included after `PageSeeder` in `DatabaseSeeder` for fresh installs. Idempotent: re-run updates copy and publication flags.

## 2. Published pages

| `PageCode` | Source / rule | Notes |
|------------|---------------|-------|
| `home` | Charter positioning | Empty blocks — hero stays in `lang/*/ui.php`; **no** VER-01 exclusive claim |
| `about` | AUD-002 / REC-02 site narrative | Transport & road safety + UN 74/299; **no** VER-12 Prooftag SAS wording |
| `history` | AUD-009 historical | 2020 vignette context in a **timeline**, labelled historical |
| `team` | Intro only | Empty `TeamMember` list (VER-02 / REC-01) |
| `partners` | Intro only | Empty `Partner` list (VER-09) |
| `careers` | AUD-021 | Intro + empty listing OK (P-11) |
| `contact` | AUD-023 / step 004 | Intro; address from Settings |
| `legal_notice` | Frozen contact record | Mentions from legal name + address + email |
| `privacy` | Contact-form scope | Form fields only; no invented trackers |

EN bodies are **new rewrites** (not legacy `/en` scrape — REC-10). Full EN editorial pass remains **162–163**.

## 3. Still Draft

| `PageCode` | Why |
|------------|-----|
| `organisation` | VER-13 / REC-01 — no mixed organigramme |
| `technical_inspection` | Hub body → step **154** |

No team members, partners, or career rows created.

## 4. VER register

VER-01–13 stay **Open**. This step does not close verification rows; it publishes only copy that does not invent unresolved facts.

## 5. Manual check

1. `/a-propos`, `/en/about` → 200 with narrative + contact CTA.
2. `/a-propos/historique` → 200 with 2020 timeline.
3. `/a-propos/equipe`, `/partenaires`, `/carrieres` → 200 + empty listings.
4. `/contact`, `/mentions-legales`, `/politique-de-confidentialite` → 200.
5. `/a-propos/organisation`, `/controle-technique` → **404**.
6. Homepage hero still has no exclusive-operator claim.

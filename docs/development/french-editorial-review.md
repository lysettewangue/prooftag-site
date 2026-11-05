# French editorial review

| | |
|--|--|
| **Step** | 161 — Complete the French editorial review |
| **Parent** | [160 — Audit verification](verify-migrated-content.md) · [glossary-fr-en.md](../content/glossary-fr-en.md) · [product-baselines.md](../content/product-baselines.md) |
| **Next** | 162 — Complete the English translation → [english-translation.md](english-translation.md) (COMPLETED) · 163 terminology |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | **French approved** for published public copy |

## 1. Scope

Reviewed all **Published** French surfaces:

- Page blocks (À propos, historique, équipe, partenaires, carrières, contact, mentions, confidentialité, hub CT)
- CERTIDOCS CT, five expertises, 12 security points, four equipment rows
- Two resources, two editorial posts
- `lang/fr/ui.php` chrome (home, indexes, empty states, nav)

Draft-only rows (organisation, CT-VERIF, DOSER, reporting, video, sample team/partner/career) stay out of public FR approval. VER-01–13 remain **Open**.

## 2. Criteria applied

| Check | Result |
|-------|--------|
| Spelling / grammar / typography | Pass after polish |
| Glossary (contrôle technique, PV, vignette, MINT, Code à Bulles) | Consistent |
| No invented VER-blocked facts as current claims | Pass |
| No DITROS product branding | Pass (public disclaimers naming DITROS removed; constraint kept in code/docs) |
| No internal ticket IDs (`VER-*`, `étape 004`) on public pages | Pass after polish |
| No EN enum leaks (`Active`, `legacy`) in FR UI/copy | Pass after polish |
| Empty listings where verification blocks people/partners | Pass |

## 3. Polish applied (161)

| Area | Change |
|------|--------|
| Corporate pages | Softened history / équipe / partenaires / contact / privacy wording |
| CERTIDOCS CT + CT hub | Removed public “DITROS-CT n’est pas utilisé” meta-line |
| News | « statut Active » → « centres actifs » |
| Article | Dropped DITROS mention; kept MINT / draft-product caveat |
| Resources | Historical summary without `(VER-08)` |
| Equipment serveur | « marketing / pédagogique » → « pédagogique » |
| `lang/fr/ui.php` | CT lede, equipment teaser, equipment index lede |

Re-run:

```powershell
php artisan db:seed --class=CorporateContentMigrationSeeder
php artisan db:seed --class=ProductContentMigrationSeeder
php artisan db:seed --class=TechnicalInspectionContentMigrationSeeder
php artisan db:seed --class=EquipmentContentMigrationSeeder
php artisan db:seed --class=ResourceContentMigrationSeeder
php artisan db:seed --class=EditorialContentMigrationSeeder
php artisan cache:clear
php artisan view:clear
```

## 4. Approval

| Role | Decision | Date |
|------|----------|------|
| Product owner (agent pass, local CMS) | French published copy approved for V1 engineering baseline | 2026-10-29 |

English quality remains **162–163**. Route parity check remains **164**.

# Brand constraints

| | |
|--|--|
| **Step** | 005 — Document brand constraints |
| **Parent** | [project-charter.md](project-charter.md) v1.0 |
| **Next** | 006 — Initial sitemap |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

This is the step 005 deliverable. It constrains identity on the public site and in the CMS. Exact colour tokens are in [brand-tokens.md](../design/brand-tokens.md) (step 115). Typefaces are chosen at step 120. The sitemap is step 006.

## 1. Palette

| Role | Rule |
|------|------|
| Primary | Royal Blue + White only |
| Supporting | Darker blue for depth, pale blue for surfaces, neutrals for text and borders |
| Functional | Success, warning, error — for status, not as brand accents |
| Forbidden as core brand | Orange, gold, unrelated rainbow accents |

Editors cannot change colours, fonts, spacing scale, or primary navigation in the CMS ([content-blocks.md](../admin/content-blocks.md)).

## 2. Logo

Authoritative files (archived 2026-10-20):

| Use | File |
|-----|------|
| Colour on light | `docs/legacy/archive/2026-10-20/media/Logo_Prooftag_CATIS_SA_Redesigne_Ok-removebg.png` |
| Colour source URL | https://site.prooftagcatis.com/wp-content/uploads/2024/07/Logo_Prooftag_CATIS_SA_Redesigne_Ok-removebg.png |
| White on blue | `Logo_Prooftag_CATIS_SA_Redesigne_Ok-white-e1719944621102.png` |
| Black | `Logo_Prooftag_CATIS_SA_Redesigne_Ok-black.png` |
| Favicon | `Favicone.png` |

Do not use DITROS, CT-VERIF, or DOSER marks as the corporate logo. Product marks may appear on product pages only.

## 3. Names in the UI

- Legal / header: **PROOFTAG-CATIS** (with hyphen), not “Prooftag Catis” as a new brand.
- Short form: **CATIS** is allowed after first mention.
- Tagline (EN): Cameroon Technology Information Services — from the contact record.
- Product names follow [products.md](products.md). They are not translated.

## 4. Navigation principles (implemented by the sitemap)

1. No Accueil / Home menu item.
2. The logo opens the homepage of the **active locale** (`/` or `/en`).
3. Eight top-level sections:

| FR | EN |
|----|-----|
| À propos | About us |
| Produits & Solutions | Products & Solutions |
| Expertises | Expertise |
| Contrôle technique | Technical inspection |
| Centres de contrôle | Inspection centres |
| Ressources | Resources |
| Actualités & Articles | News & Articles |
| Contact | Contact |

4. Language switcher: equivalent translated URL when it exists; otherwise the policy in the functional spec (step 017).
5. Footer: contact from [contact-master-record.md](contact-master-record.md); legal links; social icons only for verified URLs.

## 5. Homepage (content policy)

- Hero: CATIS as a transport / road-safety technology company (UN 74/299 narrative — **verified copy only**).
- Primary CTAs: Products, Contrôle technique, Contact.
- Secondary: five product cards, hiding unpublished products.
- Tertiary: expertise grid.
- No live UTILISATEURS / RÉGIONS counters.
- Do not lead the homepage with “opérateur exclusif de sécurisation des vignettes” until VER-01 closes.

## 6. Voice

Institutional, precise, bilingual. No all-caps body lists in the public UI (the twelve security points are numbered components, not a shouty block). No invented statistics.

## 7. Change control

Palette role, logo files, and the eight-section nav cannot change without a new version. Hex values and typefaces are later refinements inside this constraint.

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 brand constraints (step 005 executed) | 2026-10-20 |
| Communications | | |

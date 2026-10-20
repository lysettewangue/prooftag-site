# Legacy source register

| | |
|--|--|
| **Step** | 007 — Document legacy website sources |
| **Captured** | 2026-10-20 |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Status** | Frozen |

This is the step 007 deliverable. How to capture: [preservation-guide.md](preservation-guide.md). URLs: [legacy-url-register.csv](legacy-url-register.csv). Text/media: [archive/2026-10-20/](archive/2026-10-20/).

## 1. Properties

| Host | Role | CMS | REST | Capture 2026-10-20 |
|------|------|-----|------|--------------------|
| www.prooftagcatis.com | Operational / CT depth (theme Business Consultr). Homepage still frames CATIS as exclusive vignette operator (2020). | WordPress | **401** | HTML text of home, organisation, CT workflow, centres, services |
| site.prooftagcatis.com | 2024 corporate redesign (Astra). Transport / UN 74/299 positioning. 16 published pages. 0 posts. EN switch still French (not re-fetched as EN). | WordPress | **Open** | Pages JSON, media JSON (50 items), posts JSON `[]` |
| net.prooftagcatis.com | Mirror of old www. Publishes `info@net.prooftagcatis.com`. | WordPress | Not used | Homepage text |

## 2. External systems (list only — do not scrape UIs)

| System | On this website |
|--------|-----------------|
| CERTIDOCS CT | Describe + optional CMS link |
| CERTIDOCS CT Reporting | Describe when VER-06 closes |
| CT-VERIF | Describe + required CMS link after VER-04 |
| DOSER | Describe + required CMS link after VER-05 |
| Video Enforcement | Describe when VER-07 closes |

Production URLs for those apps are VER-11. They are not legacy **website** sources.

## 3. site.prooftagcatis.com — published pages (REST)

16 pages (`modified` from API):

| ID | Slug | Link | Modified |
|----|------|------|----------|
| 40 | accueil | https://site.prooftagcatis.com/ | 2025-04-09 |
| 44 | catis | /catis/ | 2024-08-05 |
| 177 | carriere | /catis/carriere/ | 2024-07-11 |
| 48 | expertise | /expertise/ | 2024-08-05 |
| 112 | recherche-et-developpement | /expertise/recherche-et-developpement/ | 2024-07-11 |
| 116 | support-technique | /expertise/support-technique/ | 2024-07-11 |
| 119 | data-analysis-reporting | /expertise/data-analysis-reporting/ | 2024-07-11 |
| 254 | securite-documentaire | /expertise/securite-documentaire/ | 2024-07-16 |
| 121 | securite-prevention-routiere | /expertise/securite-prevention-routiere/ | 2024-07-11 |
| 51 | solutions | /solutions/ | 2024-07-25 |
| 126 | ditros-ct | /solutions/ditros-ct/ | 2024-08-05 |
| 128 | ct-verif | /solutions/ct-verif/ | 2024-07-25 |
| 130 | doser | /solutions/doser/ | 2024-08-05 |
| 60 | contact | /contact/ | 2024-09-11 |
| 57 | partenaires | /partenaires/ | 2024-07-02 |
| 52 | blog | /blog/ | 2024-07-02 |

Posts: **none**.

## 4. www.prooftagcatis.com — captured paths

REST locked. Captured by public GET:

- `/` — 12 security points; 2020 operator claim
- `/organisation/` — organigramme (DG, IT, RH, engineers) — **conflicts with site team names**
- `/solutions/controle-technique/` — centre server, MINT centralisation, field smartphones, CATIS quality role
- `/partenaires/centres-de-controle/` — regional list (~35 claimed; HTML listing is incomplete vs claim)
- `/services/` — thin / empty body in the capture

A full HTTrack / host-panel dump of remaining www menus (galerie, lois, industries, actualité) is still required before cutover if those URLs return content. They stay in the URL register as strategic-to-verify.

## 5. Production backup

WordPress files, databases, and DNS/MX exports are **step 211**, not this step. This register does not claim a restorable host backup.

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as legacy source register (step 007 executed) | 2026-10-20 |

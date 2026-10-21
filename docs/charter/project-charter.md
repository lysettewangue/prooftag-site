# Project charter — PROOFTAG-CATIS Corporate Digital Platform

| | |
|--|--|
| **Step** | 001 — Project charter |
| **Next** | 041 — Define solution entities |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Accepted as the V1 engineering baseline |

This document is the step 001 deliverable. It defines the project. Detailed registers (scope, products, contact, brand, sitemap) are frozen in steps 002–006 and must not contradict this charter.

## 1. Project identity

| Field | Value |
|-------|--------|
| Name | PROOFTAG-CATIS Corporate Digital Platform |
| Organisation | PROOFTAG-CATIS S.A. (brand: PROOFTAG-CATIS · CATIS) |
| Tagline | Cameroon Technology Information Services |
| Canonical URL | https://www.prooftagcatis.com |
| Workspace | `d:\websiteprooftag` |

## 2. Background

Public information is split across three WordPress properties:

| Host | Role today |
|------|------------|
| www.prooftagcatis.com | Operational / technical-inspection depth |
| site.prooftagcatis.com | 2024 corporate shell |
| net.prooftagcatis.com | Mirror of the older www variant |

That split produces duplicate URLs, thin or conflicting copy, a broken English switch, and no single CMS. This project replaces those properties with one greenfield platform. WordPress is not carried forward.

## 3. Goal

Deliver **one** modern, bilingual, custom-developed corporate website that:

- presents CATIS as a **transport and road-safety technology company**;
- consolidates approved knowledge from the legacy sites;
- preserves SEO value through a planned redirect map;
- lets authorised staff update content without changing code.

The site **describes and links** operational systems. It does not run them.

## 4. Objectives

The V1 website must:

1. Publish from a single canonical host: `https://www.prooftagcatis.com`.
2. Present **five** confirmed solutions, with CMS-managed links to live platforms where they exist.
3. Provide a **Contrôle technique** knowledge area (workflow, twelve security points, equipment). This is not a sixth product.
4. Provide an inspection-centre directory with a verification workflow. Imported centres start as Unverified.
5. Support news and articles with an editorial workflow.
6. Provide a Filament administration interface at `/admin` (no public self-registration).
7. Support **French as the default locale** and **English as secondary**, with real translated content — not a language switch that still shows French.
8. Remain compatible with **LWS Starter** shared hosting.
9. Target **WCAG 2.2 AA** and modern SEO (canonical URLs, hreflang, sitemap, robots, legacy 301s).

## 5. In scope (charter level)

The following belongs to V1. Frozen in [scope-and-exclusions.md](scope-and-exclusions.md) (step 002, v1.0).

- One corporate website (public + CMS)
- Bilingual FR/EN public pages and administration
- Five product marketing pages with **verified** copy only
- Contrôle technique knowledge hub
- Inspection-centre directory
- Resources / documents library (current vs historical)
- News and articles
- Contact form and admin inbox
- Careers listings
- Legal pages (mentions légales, privacy)
- SEO migration and host redirects (`site.`, `net.`, and apex as decided at step 006 / 165)
- Deployment: local → LWS staging → LWS production

## 6. Out of scope

The corporate website is **not**:

- CERTIDOCS CT (operational application)
- CERTIDOCS CT Reporting Platform (operational application)
- CT-VERIF (public verification app — linked only)
- DOSER (operational data platform — linked only)
- Video Enforcement / ticketing operating platform
- Live queries to Ministry of Transport, insurance, customs, or ANPR systems
- A public REST or GraphQL API
- Redis, Horizon, WebSockets, Octane, or Docker in production
- WordPress on the new platform
- Native mobile applications

**DITROS-CT** is not a product. Do not create a CMS record or navigation item under that name. The legacy URL is decided at step 166.

VPS, Redis, queues, and institutional API integrations wait on a documented requirement change (step 248).

## 7. Product catalogue (charter level)

Recorded here. Frozen in [products.md](products.md) (step 003, v1.0).

| # | Product | Notes |
|---|---------|--------|
| 1 | CERTIDOCS CT | Core CT registration and centre workflow software |
| 2 | CERTIDOCS CT Reporting Platform | Centre activity and dashboards — **copy gap** on legacy web |
| 3 | CT-VERIF | Public verification of visites techniques — linked outbound |
| 4 | DOSER | Road-safety data and statistics — linked outbound |
| 5 | Video Enforcement / Video Ticketing Solution | **Copy gap** — do not publish until verification closes |

External platform URLs are stored in the CMS. They are not hardcoded in templates.

## 8. Corporate identity (charter level)

Recorded here. Frozen in [contact-master-record.md](contact-master-record.md) (step 004, v1.0).

| Field | Value |
|-------|--------|
| Legal name | PROOFTAG-CATIS S.A. |
| Address | Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso, Douala, Cameroun |
| Postal box | BP 4601 |
| Telephone | +237 699 19 29 19 |
| Email | info@prooftagcatis.com |
| Do not publish | info@net.prooftagcatis.com |

Social URLs and map coordinates are verification-required. They are not charter facts.

## 9. Brand and navigation (charter level)

Recorded here. Brand tokens are extracted at step 115. The sitemap is frozen at step 006.

- **Colour:** Royal Blue + White. Supporting neutrals and functional status colours only. No orange or unrelated accent as core branding.
- **Home:** the logo opens the homepage of the active locale. There is **no** Accueil / Home menu item.
- **Top-level sections:** À propos · Produits & Solutions · Expertises · Contrôle technique · Centres de contrôle · Ressources · Actualités & Articles · Contact.
- **Homepage:** transport / road-safety positioning; CTAs to products, Contrôle technique, and contact. No live “users / regions” counters unless leadership supplies verified statistics and an update process.

## 10. Technology baseline

| Layer | Choice |
|-------|--------|
| Application | Laravel 13, PHP 8.4, MySQL, Eloquent — modular monolith |
| Public | Blade, Tailwind CSS 4, Vite, vanilla JS, Alpine where useful, Lucide |
| Admin | Filament 5, Livewire 4 |
| Quality | Pest, Larastan, Pint, Git (private) |
| Host | LWS Starter. Document root = Laravel `public/` |
| Locales | `fr` default (`/…`), `en` secondary (`/en/…`). No `/fr` prefix |
| Timezone | Africa/Douala |

Queue driver in V1: **sync**. Scheduled work: one LWS cron calling `php artisan schedule:run`. Cache and sessions: `file` or `database`. No production Node server.

Execution follows [STEP-BY-STEP.md](../STEP-BY-STEP.md) and [engineering-roadmap.md](../engineering-roadmap.md): one active step; local release candidate (step 186) before any LWS work.

## 11. Success criteria

V1 is successful when all of the following are true:

1. One authoritative public site on `https://www.prooftagcatis.com` over HTTPS.
2. French and English routes serve real content for published pages.
3. The five solutions are represented according to the frozen product register; unpublished copy gaps stay hidden.
4. Contrôle technique, centre directory, resources, news, contact, and legal pages operate from CMS data.
5. Staff can update approved content in Filament without a deployment.
6. Legacy strategic URLs redirect as recorded; `site.` and `net.` do not remain competing sites.
7. The Royal Blue / White identity is applied consistently.
8. Technical documentation, an administrator manual, and a tested restore path exist at handover.

## 12. Constraints

- Content that is unverified (team titles, centre data, legal claims, product capabilities, social URLs) is not published as current fact.
- The corporate CMS has no connection to CERTIDOCS, DOSER, or ministry operational databases.
- Later steps may refine registers; they may not silently change sections 3–10 of this charter. A charter change is a new version.

## 13. Completion of step 001

Step 001 is complete when this file exists at version 1.0 with the identity, goal, objectives, exclusions, catalogue, contact, brand/nav principles, technology baseline, and success criteria recorded, and the tracker marks 001 **COMPLETED**.

Wet signatures below are optional countersignatures. They do not reopen this charter unless a decision in this document changes.

## 14. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Accepted as V1 engineering baseline (step 001 executed) | 2026-10-20 |
| Technical lead | | |
| Communications | | |

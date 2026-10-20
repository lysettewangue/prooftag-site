# V1 scope specification

| | |
|--|--|
| **Step** | 002 — Freeze the initial project scope |
| **Parent** | [project-charter.md](project-charter.md) v1.0 |
| **Next** | 005 — Document brand constraints |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

This is the step 002 deliverable. It states what V1 **will** and **will not** deliver. It does not freeze the product register (003), the contact record (004), brand tokens (005), or the sitemap (006). Those steps may add detail; they may not enlarge or shrink this scope without a new version of this file.

## 1. V1 in one sentence

One bilingual corporate website and CMS for PROOFTAG-CATIS S.A., replacing the three WordPress hosts, describing five solutions and technical-inspection knowledge, hosted on LWS Starter.

## 2. In scope — public website

| Area | Included in V1 | Boundary |
|------|----------------|----------|
| Canonical site | `https://www.prooftagcatis.com` over HTTPS | `site.`, `net.`, and apex redirect in; they are not remaining public sites |
| Locales | French default (`/…`); English (`/en/…`) | No `/fr` prefix. English URLs publish only when a real EN record exists |
| Home | Logo opens the active-locale homepage | No Accueil / Home menu item |
| About | Company profile, organisation, history, team, partners | Team and partners publish only when verified (later content gates) |
| Products | Five marketing pages plus an index | Capabilities and outbound URLs from CMS; unverified copy stays unpublished |
| Expertise | Index and domain pages | Not duplicate product names; not a sixth product |
| Contrôle technique | Knowledge hub, twelve security points, equipment, related document-security pages | Not a product. Not live CT processing |
| Centres | Directory: search, region filter, pagination, detail pages | Import starts Unverified. Public list shows Active only. Map only if coordinates are verified |
| Resources | Browse and download authorised files | Current vs historical labelling. No binaries in the database |
| News / articles | Listing, categories, article pages | Editorial workflow required. Drafts never public |
| Careers | Job and internship listings | Empty listing is acceptable; the module is still built |
| Contact | Public form, confirmation, admin inbox | Anti-abuse (honeypot / rate limit). Mail via LWS SMTP |
| Legal | Mentions légales, privacy policy | Cookie banner only if non-essential tracking is added |
| Search | Public search of published content | MySQL search. No specialist search engine |
| Language switch | Equivalent translated URL when it exists | Missing EN body → no fake EN page |
| System pages | Branded 404 and 500 | Maintenance page for deploy windows |

Audiences (government, centres, motorists, partners, job seekers, media, etc.) are served **inside** these sections. They are not extra top-level navigation items. Audience modelling is step 016.

## 3. In scope — administration

Filament at `/admin`. No public registration.

In V1 the CMS must let authorised roles manage:

- Global settings (company, contact, logos, social, default SEO)
- Pages and approved content blocks (brand, type, and primary nav stay in code)
- The five product identities, expertise, Contrôle technique content, twelve ordered security points, equipment
- Inspection centres and verification actions
- Team, partners, careers
- News / articles, resources / documents, media library (bilingual alt text)
- Contact-request inbox
- SEO metadata and legacy redirects
- Users, roles, permissions, audit of administrative actions

Eight roles are in scope ([roles.md](../admin/roles.md)): Super Administrator, Administrator, Content Manager, Technical Editor, Communications Editor, Translator, Reviewer/Publisher, Support Agent. **Publish** and **translate** are separate from **update**.

## 4. In scope — content rules

- Publish only **verified** facts. Unverified items stay in CMS as draft / unverified.
- Product copy gaps (Reporting Platform, Video Enforcement) may have CMS records; their public URLs stay hidden until verification closes.
- DITROS-CT is not a product name anywhere in V1. Factual CT text from that legacy page may be rewritten into CERTIDOCS CT and Contrôle technique. The URL decision is step 166.
- Regulatory documents distinguish **current**, **historical**, and **archive**.
- French editorial review and English translation are in V1 (steps 161–163). Shipping French-only with a broken EN switch is out of scope.

Legacy capture, audit, and migration **work** is in V1 (steps 007–015 and 151–167). Inventing missing capabilities is not.

## 5. In scope — platform and delivery

| Area | Included |
|------|----------|
| Stack | Laravel 13, PHP 8.4, MySQL, Blade, Tailwind 4, Filament 5, as in the charter |
| Environments | Local → LWS staging (`noindex` + access control) → production |
| Host | LWS Starter; document root = `public/` |
| Runtime | Sync queue; file or database cache/sessions; one PHP cron for `schedule:run` |
| Quality | Pint, Larastan, Pest (unit, feature, authorization, browser on critical journeys), CI on pull requests |
| SEO | Canonical, hreflang, sitemap, robots, 301 register |
| Accessibility target | WCAG 2.2 AA |
| Security (site) | Auth on admin, CSRF, upload whitelist, no operational-DB connections |
| Handover | Technical docs, admin manual, content governance, backup + restore test |

## 6. Out of scope (V1)

Do not build, embed, or imply any of the following:

**Operational systems**

- Running CERTIDOCS CT, CERTIDOCS CT Reporting, CT-VERIF, DOSER, or Video Enforcement inside this website
- Live feeds or queries to Ministry of Transport, DTR, insurance, customs, or ANPR platforms
- Video processing, plate recognition, or ticketing on this site
- Login to those products other than an outbound CMS-managed URL

**Product / naming**

- DITROS-CT as a product, slug, or nav item
- A sixth marketed solution
- Live homepage counters (users, regions, centres) without an approved data source and update process

**Platform**

- Public REST or GraphQL API
- Redis, Horizon, Octane, WebSockets, or a production Node server
- Docker as the production runtime
- WordPress (themes, plugins, or content editors) on the new site
- Native mobile apps
- Headless / SPA public frontend (Inertia, Vue, React, Next)

**Content and features deferred**

- Publishing unverified team, centre, legal, or product claims
- Non-essential analytics / marketing pixels (and therefore a cookie banner) unless separately approved
- Paid maps APIs
- Multi-language beyond FR and EN
- Public user accounts, comments, or community forums

## 7. Later than V1 (explicit change required)

These wait on a written requirement change and, where relevant, step 248:

- VPS or other hosting upgrade
- Redis, queues with workers, search appliances
- Institutional API integrations
- Additional locales
- Additional products beyond the five named in the charter

## 8. Change control

- This file is the frozen V1 scope.
- A scope change is a new version of this document, with the reason recorded, **before** any step that would implement the change.
- Steps 003–006 refine registers inside this boundary. They do not add operational apps, extra products, or excluded infrastructure.

## 9. Completion of step 002

Step 002 is complete when this specification exists at version 1.0, agrees with charter sections 3–6 and 10–11, and the tracker marks 002 **COMPLETED**.

## 10. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 scope (step 002 executed) | 2026-10-20 |
| Technical lead | | |
| Communications | | |

# UI/UX brief

| | |
|--|--|
| **Step** | 114 — Prepare the UI/UX brief |
| **Parent** | [design-brief.md](design-brief.md) · [audience-matrix.md](../requirements/audience-matrix.md) · [v1-model-freeze.md](../database/v1-model-freeze.md) |
| **Next** | 115 — Extract official brand tokens |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Audiences, templates, and accessibility frozen for wireframes |

This is the step 114 deliverable. It binds public design to the frozen V1 catalogues. It does **not** extract hex (115), draw wireframes (116), or write Blade (123). Filament `/admin` is out of this brief.

Personality, palette role, and typeface *recommendations* remain in [design-brief.md](design-brief.md). Names and nav follow [brand-and-navigation.md](../charter/brand-and-navigation.md).

## 1. Audiences (context, not nav)

Top-level navigation stays the eight frozen sections. Audiences appear as blocks on product, expertise, and Contrôle technique pages — never as extra menu items.

| Priority | IDs | Design for |
|----------|-----|------------|
| Primary | A-01 government / MINT / DTR · A-02 centre operators · A-08 investors / partners | Institutional home, About, products, CT hub, resources |
| Primary path | A-05 motorists | CT-VERIF (when published), centre directory, contact |
| Secondary | A-03 owners · A-04 transport pros · A-06 NGOs · A-07 insurers · A-09 tech partners · A-10 media · A-11 job seekers | Reporting (when published), expertise, DOSER, news, careers |

Do not design an operational login, a public API, live UTILISATEURS / RÉGIONS counters, or a DITROS-CT page.

## 2. Content the UI must respect

| Rule | UX consequence |
|------|----------------|
| Drafts never public | Nav, cards, search, and `sitemap.xml` omit unpublished rows |
| Missing EN translation | No EN URL, no fake English body (P-09). Switcher must not invent a path |
| VER-01–13 Open | Do not present unresolved claims as current facts (exclusive-operator hero, team, partners, unverified centres, unverified product copy) |
| Five products only | One product-detail pattern. Hide unpublished cards; reserved URLs stay off nav |
| CT is a Page, not a product | Hub lives under Contrôle technique, not under Produits |
| Careers empty is valid (P-11) | Dedicated empty state, not a 404 |
| Team / partners 0 rows | Unpublished / empty until VER-02 / VER-09 |
| Centres: Active only | Unverified and unavailable stay off the directory. Map pin only with coordinates |
| Resource labels | Current / historical / archive as text, not colour alone |
| Footer contact | Frozen record only. Social icons only when VER-10 URLs exist |
| Cookie banner | Omit unless non-essential tracking is added |

Representative CMS drafts (107–110) are **wireframe fixtures**, not launch copy.

## 3. Key templates (step 116)

Wireframe these first. Desktop lo-fi. Tablet and mobile wait for 119.

| # | Template | CMS source | Must-show states |
|--:|----------|------------|------------------|
| 1 | Global shell | Settings + eight-section nav | Logo = locale home; **no** Accueil / Home item; FR `/` · EN `/en`; language switch; footer from contact record |
| 2 | Homepage | Page `home` + published products + expertises | Hero without VER-01 claim; CTAs Produits / Contrôle technique / Contact; 0–5 product cards; no live counters |
| 3 | Product detail | Product (one layout for all five) | Summary, body, optional `external_url` CTA, related expertise/resources. Unpublished products are not linked |
| 4 | Contrôle technique hub + 12 points | Page `technical_inspection` + SecurityPoint 1–12 | Numbered accordion, readable (not all-caps). Equipment teaser |
| 5 | Centre directory + detail | InspectionCentre Active + Region | Search, region filter, pagination, list primary; map secondary; missing phone/email/coords |
| 6 | Resource library | Resource | Browse/download; current vs historical vs archive; missing PDF cannot appear as Published |
| 7 | News listing + article | Post news/article + Category | Drafts hidden; independent FR/EN slugs; empty listing |
| 8 | Contact | Page `contact` + form | Frozen address/phone/email; validated fields; success and error |
| 9 | Legal | Pages `legal_notice`, `privacy` | Long regulatory reading measure |
| 10 | System | — | Branded 404, 500, maintenance |

### Same-family templates (reuse, do not invent a new visual system)

About, organisation, history — page + approved blocks. Expertise index/detail — card + page. Equipment index/detail. Careers index/detail (empty allowed). Team / partners (empty until verified). Search (`/recherche` · `/en/search`). Product index.

Approved page blocks: rich text, text and media, gallery, timeline, statistics, FAQ, document list, specifications, related solutions, related resources, partners, team, call to action. Statistics show only CMS-verified figures.

## 4. Components the wireframes must include

| Component | Rule |
|-----------|------|
| Header | Logo, eight sections, language switch, optional search entry |
| Language switch | Equivalent translated URL when it exists; otherwise do not mint a path (typical V1: EN control → `/en` when the entity has no EN row). Visible current locale |
| Footer | Display lines from [contact-master-record.md](../charter/contact-master-record.md); legal links; social only if verified |
| Hero | Institutional; primary CTAs as above |
| Product grid | Bento / cards of **published** products only |
| Expertise cards | Five domains; titles must not copy product names |
| 12-point accordion | Numbered 1–12; one panel open at a time is allowed; full text readable |
| Directory | Filters + pagination; list works with JavaScript off; map is enhancement |
| Forms | Visible labels, errors next to fields, honeypot not shown to users |
| Pagination | Centres, news, resources |
| Breadcrumbs | From the eight-section tree |
| Empty state | Careers, news, directory-with-no-matches, unpublished product grid |
| Status text | Resource regulatory status; do not use colour as the only signal |

Public icons will be Lucide (128). Wireframes use generic placeholders, not Heroicons.

## 5. Accessibility (WCAG 2.2 AA)

Design and later code target **WCAG 2.2 AA**. Contrast on Royal Blue is measured after hex extraction (115) and reviewed at 121.

| Requirement | Design implication |
|-------------|--------------------|
| Contrast | Text and UI on blue/white must meet AA. Do not put small grey text on pale blue |
| Focus | Visible focus ring on every control; do not remove outline without a replacement |
| Touch | 44×44 px minimum interactive target (nav, language, filters, accordion, pagination) |
| Labels | Every input has a visible `<label>` (or equivalent). Placeholders are not labels |
| Language | `lang="fr"` / `lang="en"` on the document; switcher announces the target locale |
| Skip link | “Skip to content” in the shell |
| Motion | Honour `prefers-reduced-motion` (accordion, map, carousels). No auto-playing video |
| Images | Alt from CMS `alt.fr` / `alt.en`. Decorative logo in header may be empty alt with adjacent name |
| Structure | One `h1` per template; heading order without skips |
| Keyboard | Directory, accordion, language switch, and form usable without a pointer |
| Map | List remains the accessible source of centres; map is optional |
| Language of UI chrome | Translated chrome; marketing body never falls back to the other locale |

Admin accessibility is Filament’s responsibility, not this brief.

## 6. Out of this step

| Item | Step |
|------|------|
| Extract logo hex | 115 |
| Low-fidelity wireframes | 116 |
| Hierarchy vs real copy | 117 |
| High-fidelity and responsive screens | 118–119 |
| Type, colour tokens, component states | 120 |
| Accessibility review of the designs | 121 |
| Stakeholder approval | **122 (gate)** |
| Tailwind / Blade | 123+ |

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | UI/UX brief prepared: audiences, templates, accessibility (step 114 executed) | 2026-10-22 |

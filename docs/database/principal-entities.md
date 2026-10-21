# Principal entities

| | |
|--|--|
| **Step** | 039 — Begin conceptual database design |
| **Parent** | [sitemap-and-content-types.md](../ia/sitemap-and-content-types.md) (021–022) · [layers.md](../architecture/layers.md) (024) |
| **Next** | 088 — Global settings admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Principal entities listed |

This is the step 039 deliverable. It names the V1 records. It does **not** define columns, keys, indexes, or cardinalities. Those wait for **040–054**. **No migrations before gate 055.**

Identity already in the app (users, eight roles, permissions, activity log) is listed so it is not reinvented. Content types match [021](../ia/sitemap-and-content-types.md). Domain modules match [024](../architecture/layers.md).

## 1. Rules carried forward

- Exactly **five** products ([products.md](../charter/products.md)). DITROS-CT is not an entity.
- Exactly **eight** staff roles ([roles.md](../admin/roles.md)). Publish, translate, and update stay separate permissions, not extra entities.
- Translatable types use a **parent record plus FR/EN translations** (ADR-001). Translation tables are specified at **050**, not here.
- Publication lives on the **base** record (021). A locale is public only if its translation exists and the base is published.
- No connection from this CMS to CERTIDOCS, DOSER, CT-VERIF, Video Enforcement, or MINT databases.
- Binaries stay on disk. MySQL holds paths and metadata only.

## 2. Catalogue

| Entity | Domain (024) | Detail step | Translatable (021) | Purpose |
|--------|--------------|------------:|--------------------|---------|
| User | Identity (in app) | 057 | no | Staff accounts. No public registration. |
| Role | Identity (in app) | 057 | display names only | The eight frozen staff roles. |
| Permission | Identity (in app) | 057 | no | Shield / Spatie abilities, including separate publish / translate / update. |
| Activity | Identity (in app) | 057 / 104 | no | Administrative action log (Spatie). |
| Settings | Corporate | 040 | mixed | Singleton: contact, logos, social, default SEO. Contact is not copied per page. |
| Page | Corporate | 040 | yes | Static public pages (about, legal, CT hub overview, contact page shell). |
| TeamMember | Corporate | 040 | yes | About / team. Publish only when verified. |
| Partner | Corporate | 040 | yes | About / partners. Publish only when verified. |
| Career | Corporate | 040 | yes | Jobs and internships. Empty listing is allowed. |
| Product | Products | 041 | yes | The five solutions. Identity is `code`. Hidden products keep a record and reserved path. |
| Expertise | Expertise | 042 | yes | Five expertise domains. Names must not copy product names. |
| SecurityPoint | TechnicalInspection | 043 | yes | The twelve ordered securisation points. |
| Equipment | TechnicalInspection | 043 | yes | CT equipment. Current vs legacy; do not hard-delete replaced models. |
| Region | Centres | 044 | yes | Cameroon regions for the directory. |
| InspectionCentre | Centres | 044 | yes | Directory row. Region required. Import starts Unverified; public list is Active only. |
| Post | Editorial | 045 | yes | News and articles. Drafts never public. |
| Category | Editorial | 045 | yes | Editorial grouping for posts. |
| Resource | Resources | 046 | yes | Downloadable files. Current / historical / archive. |
| Media | Media | 047 | alt per locale | Path, MIME, rights. Spatie Media Library at implementation, not this step. |
| ContactRequest | Communication | 048 | no | Public form inbox and processing statuses. |
| Redirect | Seo | 049 | no | Legacy host+path → canonical path (301 register). |

SEO **metadata** (canonical, title, description per locale) is not a standalone entity. It belongs on translatable records and default Settings. Shape is step **049**.

Contrôle technique **hub copy** (overview, related document-security pages) uses **Page**. **043** adds SecurityPoint and Equipment only. Do not add a sixth product or a DITROS-CT type.

## 3. Associations (named only)

Already frozen at 022. Cardinalities and join tables are step **051**.

| Association | Note |
|-------------|------|
| Product ↔ Expertise | Optional many-to-many |
| Post ↔ Product | Optional tagging |
| Post ↔ Expertise | Optional tagging |
| Resource ↔ Product | Optional; a resource may instead relate to CT knowledge |
| InspectionCentre → Region | Required |
| Settings | Not related to products |
| Redirect | Independent of content types |

## 4. Not entities in V1

| Item | Why |
|------|-----|
| DITROS-CT | Not a product. URL decision is 166. |
| Locale | `fr` / `en` in routing and translation rows, not a locale table. |
| PublicationState | Shared fields on the base record (045 / 050), not a CMS type. |
| Operational systems | CERTIDOCS, CT-VERIF, DOSER, Video Enforcement, MINT — outbound URLs on Product only. |
| Search index | MySQL search of published rows. No search engine. |
| WordPress / WP users | Greenfield. Not migrated as entities. |
| Ninth staff role | Forbidden without a new roles register. |

## 5. Out of this step

| Item | Step |
|------|------|
| Corporate fields (settings, pages, team, partners, careers) | 040 (done — [corporate-entities.md](corporate-entities.md)) |
| Product fields | 041 (done — [solution-entities.md](solution-entities.md)) |
| Expertise through SEO | 042–049 (done — see [erd.md](erd.md)) |
| Translation table strategy | 050 (done — [translation-strategy.md](translation-strategy.md)) |
| ERD with cardinalities | 051 (done — [erd.md](erd.md)) |
| Data dictionary, constraints, indexes | 052–054 (done) |
| Design approval | 055 (done — [design-review.md](design-review.md)) |
| Migrations | 056+ |

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Principal entities listed (step 039 executed) | 2026-10-21 |

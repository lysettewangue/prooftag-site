# Solution entities

| | |
|--|--|
| **Step** | 041 — Define solution entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [products.md](../charter/products.md) (003) · [product-baselines.md](../content/product-baselines.md) (015) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Five solutions, translations, and relationships specified |

This is the step 041 deliverable. It specifies **Products** (`App\Domain\Products`). Column types, keys, and indexes wait for **052–054**. Translation **tables** wait for **050**. Expertise **attributes** wait for **042**. **No migrations before gate 055.**

V1 has **exactly five** solution rows. DITROS-CT is not a product. Contrôle technique is a knowledge hub (Page `technical_inspection` + **043**), not a sixth solution. The CMS does not connect to CERTIDOCS, CT-VERIF, DOSER, Video Enforcement, or MINT databases.

## 1. Invariants

- Codes, public names, slug fragments, and sort order **1–5** are frozen in [products.md](../charter/products.md). Adding, renaming, splitting, or merging products requires a new register version and a scope check.
- The five base rows are **seeded**. Staff cannot create a sixth Product or delete one of the five. Visibility is publication, not row removal.
- `code` is unique and **not editable** after create.
- Product **names stay branded** in both locales. Do not translate CERTIDOCS CT, CT-VERIF, or DOSER. “Video Ticketing Solution” is an alternate label in body copy after VER-07 — **never** a sixth CMS record.
- `external_url` is stored on the Product and rendered from the CMS. **Never** hardcode platform URLs in Blade.
- Publication is **independent** of the CT hub and of Expertise. Shared A-09 states on the base (detailed at **045**).
- Publish, translate, and update stay separate. Technical Editor updates product copy; Translator owns EN; Reviewer/Publisher (or the 035 matrix) publishes.
- Exactly eight staff roles. No product-specific extra role.

## 2. Product (base)

Locale-independent identity and outbound link.

| Attribute | Rule |
|-----------|------|
| `code` | Exactly one of: `CERTIDOCS_CT`, `CERTIDOCS_CT_REPORTING`, `CT_VERIF`, `DOSER`, `VIDEO_ENFORCEMENT`. |
| `public_name` | Frozen display name from the register (same in FR and EN). |
| `sort_order` | `1`–`5` matching the register. Not freely reordered in the CMS. |
| `slug_fragment` | Frozen path segment, **identical** in both locales (see §3). |
| `external_url` | Optional except where §4 requires it. Production URL of the linked platform. Empty = no outbound CTA. |
| `card_image` / `hero_image` | Optional Media (047). Product marks may appear here; they are not the corporate logo (040). |
| Publication | Draft → Under Review → Approved → Scheduled → Published → Archived on the **base**. |
| `published_at` / `scheduled_for` | One schedule for the product, all locales. |

A locale URL exists only when the base is Published (`published_at` ≤ now) **and** that locale’s translation exists.

## 3. Product translation

Marketing body and SEO. Physical table shape is **050**.

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | **Must equal** the frozen `slug_fragment` for that `code` in **both** locales. Unique per `(locale, slug)`. Independent FR/EN slugs are **not** allowed for products. |
| `summary` | Short card / index text. Unverified capability lists stay empty (015). |
| `body` / `blocks` | Approved block types only ([content-blocks.md](../admin/content-blocks.md)). Themes from [product-baselines.md](../content/product-baselines.md). Do not invent bullets. |
| `external_label` | Optional CTA caption for `external_url` (locale-specific). |
| SEO override | Optional title/description; else Settings defaults (**049**). |

FR translation is required to publish the French URL; EN translation is required to publish `/en/products/{slug}`. UI chrome may fall back; marketing body may not.

Public paths (006 / 020):

| `code` | `slug_fragment` | FR | EN |
|--------|-----------------|----|----|
| `CERTIDOCS_CT` | `certidocs-ct` | `/produits/certidocs-ct` | `/en/products/certidocs-ct` |
| `CERTIDOCS_CT_REPORTING` | `certidocs-ct-reporting` | `/produits/certidocs-ct-reporting` | `/en/products/certidocs-ct-reporting` |
| `CT_VERIF` | `ct-verif` | `/produits/ct-verif` | `/en/products/ct-verif` |
| `DOSER` | `doser` | `/produits/doser` | `/en/products/doser` |
| `VIDEO_ENFORCEMENT` | `video-enforcement` | `/produits/video-enforcement` | `/en/products/video-enforcement` |

The index `/produits` · `/en/products` is a **listing query**, not a Product row and not a Page.

Hidden products **keep** these paths reserved. They are omitted from nav, homepage cards, `sitemap.xml`, and search until Published in that locale.

## 4. Per-code publication gates

Closing VER-04–VER-07 changes **publication**, not codes or count. Expand capability copy only after the matching VER row closes (015).

| `code` | May Publish when | `external_url` |
|--------|------------------|----------------|
| `CERTIDOCS_CT` | Verified rewrite of allowed themes. Never the name DITROS-CT. | Optional |
| `CERTIDOCS_CT_REPORTING` | VER-06 closed | Optional (VER-11 if a platform URL exists) |
| `CT_VERIF` | VER-04 closed | **Required** before Publish (VER-11) |
| `DOSER` | VER-05 closed | **Required** before Publish (VER-11) |
| `VIDEO_ENFORCEMENT` | VER-07 closed | Optional |

This website **describes and links**. It does not embed CT-VERIF, run DOSER, or operate ANPR / ticketing.

## 5. Relationships

Named at 022. Join-table DDL and cardinalities wait for **051**. Expertise / Post / Resource **fields** wait for **042 / 045 / 046**.

| Association | Rule |
|-------------|------|
| Product ↔ Expertise | Optional many-to-many (`product_expertise`). A product may have zero expertises. Expertise names must not copy product names (**042**). |
| Post ↔ Product | Optional tagging (`post_product`). **045**. |
| Resource ↔ Product | Optional (`resource_product`). A resource may instead relate to CT knowledge, not both required. **046**. |
| Product → Settings | None. Contact and corporate logos stay on Settings (040). |
| Product → InspectionCentre | None. Centres are **044**, not solutions. |
| Product → Page `technical_inspection` | None. Hub copy is a Page; points and equipment are **043**. |
| Product → operational DB | **Forbidden.** |

Homepage product cards and the public index **query** Published products in `sort_order`, with a translation for the active locale. No extra relation.

Related-solutions blocks on Pages read this same collection.

## 6. Not in this model

| Item | Why |
|------|-----|
| `DITROS_CT` / DITROS-CT page | Not a product. Legacy URL is step **166**. Factual CT text may be rewritten into `CERTIDOCS_CT` and the CT hub. |
| Sixth product / “Video Ticketing” row | Alternate label in copy only. |
| ProductCapability child table | Capabilities live in translation body after VER. |
| Hardcoded Blade URLs | `external_url` only. |
| Product as Contrôle technique | Different entity family (040 Page + 043). |

## 7. Out of this step

| Item | Step |
|------|------|
| Expertise attributes and names | 042 |
| Twelve points, equipment | 043 |
| Post / resource field lists | 045–046 |
| Media MIME / alt | 047 |
| SEO field set, Redirect | 049 |
| Translation table DDL | 050 |
| ERD, dictionary, migrations | 051–056 |
| Filament product module | 088+ |

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Solution entities specified (step 041 executed) | 2026-10-21 |

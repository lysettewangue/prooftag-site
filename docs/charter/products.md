# Approved product register

| | |
|--|--|
| **Step** | 003 — Freeze the confirmed product portfolio |
| **Parent** | [project-charter.md](project-charter.md) v1.0 · [scope-and-exclusions.md](scope-and-exclusions.md) v1.0 |
| **Next** | 005 — Document brand constraints |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen |

This is the step 003 deliverable. V1 has **exactly five** solutions. Names, codes, and order below are immutable without a new version of this register. Capability copy is **not** frozen here; it is verified later ([product-baselines.md](../content/product-baselines.md), VER-04–VER-07). Public path prefixes (`/produits`, `/en/products`) are frozen at step 006.

## 1. Register

Product **names stay branded** in both locales. Do not translate CERTIDOCS CT, CT-VERIF, or DOSER.

| # | Code (immutable) | Public name | Slug fragment (both locales) | Public in V1 | Outbound platform |
|---|------------------|-------------|------------------------------|--------------|-------------------|
| 1 | `CERTIDOCS_CT` | CERTIDOCS CT | `certidocs-ct` | Yes, after verified copy | Optional |
| 2 | `CERTIDOCS_CT_REPORTING` | CERTIDOCS CT Reporting Platform | `certidocs-ct-reporting` | Hidden until VER-06 | Optional |
| 3 | `CT_VERIF` | CT-VERIF | `ct-verif` | Yes, after VER-04 | Required (CMS URL) |
| 4 | `DOSER` | DOSER | `doser` | Yes, after VER-05 | Required (CMS URL) |
| 5 | `VIDEO_ENFORCEMENT` | Video Enforcement | `video-enforcement` | Hidden until VER-07 | Optional |

**Alternate label** for #5 (not a sixth product): Video Ticketing Solution. Use in body copy only after VER-07; never as a separate CMS record.

**Sort order** on the public index and homepage cards: 1 → 5 as in this table.

## 2. What each record is

### 1 — CERTIDOCS CT

Software and equipment ecosystem for secured technical inspection in Cameroon (centre workflow, secured PV / vignette process). Marketing page on this website. The operational application is **out of scope**.

- **Legacy:** www Services / Certidocs; factual CT text from site DITROS-CT may be rewritten here. The name DITROS-CT is not used.
- **Do not publish:** internal architecture, credentials, undocumented integrations.

### 2 — CERTIDOCS CT Reporting Platform

Centre activity monitoring, statistics, and dashboards for centre owners and promoters. Marketing page only.

- **Legacy:** no public page. Copy requires a product-owner workshop (VER-06).
- **V1 rule:** CMS record may exist; the public URL stays unpublished until VER-06 closes.

### 3 — CT-VERIF

Public verification of visites techniques. This website **links out**; it does not embed the verifier.

- **Legacy:** site `/solutions/ct-verif/`.
- **V1 rule:** `external_url` is mandatory before publish (VER-04).

### 4 — DOSER

Road-safety data: collection, search, analysis, accident statistics. This website **links out**.

- **Legacy:** site `/solutions/doser/`.
- **V1 rule:** `external_url` is mandatory before publish (VER-05).

### 5 — Video Enforcement

Intelligent vehicle detection, authorised plate reading, and regulatory-status checks for enforcement. Marketing page only. No ANPR or ticketing runs on this site.

- **Legacy:** no public page (VER-07).
- **V1 rule:** CMS record may exist; the public URL stays unpublished until VER-07 closes.

## 3. CMS identity (required fields)

Each of the five records must exist in the CMS with:

| Field | Rule |
|-------|------|
| `code` | One of the five codes above. Unique. Not editable after create. |
| `sort_order` | 1–5 matching this register |
| `external_url` / `external_label` | Admin-managed. Never hardcoded in Blade |
| Publication | Independent of Contrôle technique and expertise |
| Translations | FR required to publish FR; EN required to publish EN |

No sixth product row. No `DITROS_CT` code.

## 4. Excluded from this register

| Name | Rule |
|------|------|
| DITROS-CT | Not a product. No CMS record, slug, or nav item. Legacy URL decided at step 166 |
| Contrôle technique | Knowledge hub, not a product |
| Twelve security points, equipment, Code à Bulles, secured PV | CT content, not products |
| Expertise domains (five) | Related content types; names must not copy product names |
| CERTIDOCS / CT-VERIF / DOSER / Video **applications** | Linked systems. Out of V1 scope |

## 5. Index and navigation

- Public index lists only products whose current locale is **published**.
- Hidden products (Reporting, Video Enforcement, or any unverified page) are omitted from nav, cards, sitemap, and search.
- Homepage product band uses this register’s order and the same visibility rule.

## 6. Change control

- Adding, renaming, splitting, or merging products requires a new version of this register **and** a scope check against [scope-and-exclusions.md](scope-and-exclusions.md).
- Closing VER-04–VER-07 may change **publication** of #2–#5. It does not change codes, names, or count.
- Intended public paths: `/produits/{slug}` and `/en/products/{slug}`. Prefixes confirmed at step 006.

## 7. Completion of step 003

Step 003 is complete when this register exists at version 1.0 with five codes, names, slug fragments, visibility rules, and exclusions, and the tracker marks 003 **COMPLETED**.

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as V1 product register (step 003 executed) | 2026-10-20 |
| Technical lead | | |
| Communications | | |

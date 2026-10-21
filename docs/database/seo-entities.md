# SEO and redirect entities

| | |
|--|--|
| **Step** | 049 — Define SEO and redirect entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [functional-spec.md](../requirements/functional-spec.md) A-14 / 019 SEO |
| **Next** | 090 — Expertise admin |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Metadata and redirects specified |

This is the step 049 deliverable. Domain: `App\Domain\Seo`. Canonical host is always `https://www.prooftagcatis.com`. Staging is `noindex`. **No migrations before gate 055.**

SEO **metadata is not a standalone CMS type**. It lives on Settings (defaults) and on each translatable record. **Redirect** is the only extra table.

## 1. Metadata fields

On **Settings** translations (site-wide fallback) and on Page, Product, Expertise, Equipment, InspectionCentre, Post, Category, Resource, Career, TeamMember, Partner translations:

| Field | Rule |
|-------|------|
| `meta_title` | Optional. Else derived from the record title + site name. |
| `meta_description` | Optional. |
| `canonical_path` | Optional override. Default = localized public path. Host is always www. |

`hreflang` pairs are computed from existing translations (050). `og:image` uses the record’s image or Settings logo. `sitemap.xml` includes Published URLs only (plus Active centres). Robots: allow production www; staging `noindex`.

Hidden products keep reserved paths but are **omitted** from sitemap and search until Published.

## 2. Redirect

Independent of content types (022). Loaded from the legacy URL register at 165–167; editable in admin (A-14).

| Attribute | Rule |
|-----------|------|
| `from_host` | `www.prooftagcatis.com`, `site.prooftagcatis.com`, `net.prooftagcatis.com`, or `prooftagcatis.com` (apex). |
| `from_path` | Path without query, leading `/`. |
| `to_path` | Canonical path on www (localized). |
| `status_code` | `301` (default) or `302` (temporary). |
| `is_active` | Boolean. |

Match `(from_host, from_path)` unique. Longest-prefix / exact path resolution is application logic (RedirectResolver), not an extra entity.

DITROS-CT target is **not** decided here (step **166**). A redirect row may exist as Draft/`is_active = false` until then.

Host policy (006): site → matching www path; net and apex → www.

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | SEO metadata and redirects specified (step 049 executed) | 2026-10-21 |

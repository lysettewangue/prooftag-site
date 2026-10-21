# Corporate entities

| | |
|--|--|
| **Step** | 040 — Define corporate entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [contact-master-record.md](../charter/contact-master-record.md) (004) · [sitemap-and-content-types.md](../ia/sitemap-and-content-types.md) (021) |
| **Next** | 042 — Define expertise entities |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Settings, pages, team, partners, and careers specified |

This is the step 040 deliverable. It specifies **Corporate** records (`App\Domain\Corporate`). Column types, nullability, keys, and indexes wait for **052–054**. Translation **tables** wait for **050**. Media storage waits for **047**. **No migrations before gate 055.**

Career is included here (039 mapping; admin A-11; path `/a-propos/carrieres`). It is not a sixth product and not an editorial Post.

## 1. Shared rules

- Contact facts come from **Settings** only. Pages must not duplicate address, phone, or email.
- Brand colours, type, spacing, and the eight-item primary nav stay in **code**. Editors cannot change them ([content-blocks.md](../admin/content-blocks.md)).
- Publication lives on the **base** record (021). Shared states are those in A-09 (detailed at **045**): Draft → Under Review → Approved → Scheduled → Published → Archived. Soft delete is recovery, not “hidden but published”.
- A locale URL exists only when the base is Published (and `published_at` ≤ now) **and** that locale’s translation exists with a slug.
- Publish, translate, and update remain separate permissions. Communications Editor owns about / careers / pages copy; Translator owns EN records; Reviewer/Publisher (or Comms where the matrix allows) publishes.
- Exactly eight staff roles. Settings are not a ninth.

## 2. Settings (singleton)

One row. Not a translatable “page”. **Mixed:** identity and contact are locale-independent; a few display/SEO strings are per locale (050).

### 2.1 Locale-independent (base)

| Attribute | Rule |
|-----------|------|
| Legal name | Frozen: PROOFTAG-CATIS S.A. |
| Brand | PROOFTAG-CATIS · CATIS |
| Building, city, country, postal box | Frozen contact record. Do not localise the building name. |
| Telephone | `+237 699 19 29 19` (international form in both locales) |
| Email | `info@prooftagcatis.com` only. Never `info@net.prooftagcatis.com`. |
| Canonical URL | `https://www.prooftagcatis.com` |
| Logo colour / white / black / favicon | Four **Media** slots (047). Corporate marks only — not DITROS, CT-VERIF, or DOSER. |
| Social URLs | LinkedIn, Facebook, X, YouTube, Instagram. Empty until VER-10. Empty = **no icon**, not a placeholder URL. |
| Geo latitude / longitude | Optional. Set only after verification. Address does not require a map. |
| Opening hours / fax / second email | **Not in V1** unless the contact record is revised. |

Product outbound URLs are **not** settings (041).

### 2.2 Per locale (settings translation)

| Attribute | Rule |
|-----------|------|
| Tagline | EN frozen: “Cameroon Technology Information Services”. FR only if supplied — do not invent. |
| Default SEO title / description | Site-wide fallback. Field shape at **049**. |

JSON-LD `Organization` reads this singleton (`name`, `url`, `email`, `telephone`, `address`). Footer and the contact page read it too.

Changing a frozen identity field requires a new version of the contact record, not an ad-hoc CMS edit of the legal name.

## 3. Page

CMS-managed static pages. Identity is a stable **`code`** on the base (not the slug). Slug, title, body/blocks, and SEO overrides live on the translation (050).

### 3.1 Base

| Attribute | Rule |
|-----------|------|
| `code` | Unique. V1 inventory below. New codes need a sitemap revision (020). |
| Publication | Shared A-09 states on the base. |
| `published_at` / `scheduled_for` | One schedule for the page, all locales. |

### 3.2 Translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | Unique per `(locale, slug)`. Must realise the frozen public path for that code. |
| `title` | Heading |
| `blocks` | Ordered list using **only** approved types: rich text, text and media, gallery, timeline, statistics, FAQ, document list, specifications, related solutions, related resources, partners, team, call to action. Block JSON schema is step **105**. |
| SEO override | Optional title/description; else Settings defaults (**049**). |

Marketing body does not fall back across locales. Missing EN translation = no `/en/…` URL.

### 3.3 V1 `code` inventory

| `code` | FR path | EN path | Notes |
|--------|---------|---------|--------|
| `home` | `/` | `/en` | Template in code (hero, product cards, expertise). VER-01 gates exclusivity wording. |
| `about` | `/a-propos` | `/en/about` | VER-12 |
| `organisation` | `/a-propos/organisation` | `/en/about/organisation` | VER-13 |
| `history` | `/a-propos/historique` | `/en/about/history` | 2020 positioning only if retained |
| `team` | `/a-propos/equipe` | `/en/about/team` | Intro + SEO. Members are TeamMember. VER-02 |
| `partners` | `/a-propos/partenaires` | `/en/about/partners` | Intro + SEO. Rows are Partner. VER-09 |
| `careers` | `/a-propos/carrieres` | `/en/about/careers` | Intro + SEO. Rows are Career. Empty listing allowed |
| `contact` | `/contact` | `/en/contact` | Intro copy only. Address from Settings. Form is **048** |
| `legal_notice` | `/mentions-legales` | `/en/legal-notice` | |
| `privacy` | `/politique-de-confidentialite` | `/en/privacy-policy` | |
| `technical_inspection` | `/controle-technique` | `/en/technical-inspection` | Hub overview. Points and equipment are **043** |

FR paths and the English **section** prefixes are frozen (006 / 020). English **child** slugs under `/en/about/` (organisation, history, team, partners, careers) are set in this table so each `code` has a pair; they must not collide with `/en/about` itself or `/en/about/careers/{slug}`.

Not Page records: product/expertise/centre/news/resource **indexes** (their own types), public search, branded 404/500/maintenance (P-15, code). Editors cannot add a primary-nav item by creating a Page.

## 4. TeamMember

Listing for `/a-propos/equipe`. Do not mix the site named officers with the www organigramme (REC-01 / VER-02). Records may exist as Draft; **do not Publish** until VER-02 is closed.

| Side | Attribute | Rule |
|------|-----------|------|
| Base | `full_name` | Person’s name. Not translated. |
| Base | `sort_order` | Display order on the team page. |
| Base | `photo` | Media (047). Optional until verified. |
| Base | Publication | Shared A-09 states. |
| Translation | `slug` | Unique per `(locale, slug)`. |
| Translation | `title` | Role / function (translatable). |
| Translation | `bio` | Optional. Unverified claims stay Draft. |

Public index: Published members only, ordered by `sort_order`. The `team` Page may include a `team` block; it still reads this collection, not pasted names.

## 5. Partner

Listing for `/a-propos/partenaires`. VER-09 (MINT, Prooftag SAS, others) must close before Publish.

| Side | Attribute | Rule |
|------|-----------|------|
| Base | `name` | Institutional name (often untranslated). |
| Base | `sort_order` | Display order. |
| Base | `logo` | Media (047). |
| Base | `url` | Optional outbound site. Empty = name/logo only, no link. |
| Base | Publication | Shared A-09 states. |
| Translation | `slug` | Unique per `(locale, slug)`. |
| Translation | `blurb` | Optional short text. |

Settings are **not** related to partners. A Page may use a `partners` block that reads this collection.

## 6. Career

Index `/a-propos/carrieres`; detail `/a-propos/carrieres/{slug}` (EN `/en/about/careers/{slug}`). Empty published set is a valid launch state (P-11).

| Side | Attribute | Rule |
|------|-----------|------|
| Base | `kind` | `job` or `internship`. |
| Base | `closes_at` | Optional. After this instant the listing may show as closed; still not a Post. |
| Base | `application_url` | Optional. Empty = enquire via the contact form (frozen email). |
| Base | Publication | Shared A-09 states. |
| Translation | `slug` | Unique per `(locale, slug)`. |
| Translation | `title` | |
| Translation | `body` | Blocks or rich text from the approved vocabulary. |

Not a Product, not a Post, not a Resource. No public application inbox in V1 (that remains **048**).

## 7. Associations (corporate only)

| From | To | Rule |
|------|----|------|
| Settings | — | Singleton. Not related to products (022). |
| Page `team` / `partners` / `careers` | collections | By query of Published rows, not a required foreign key. |
| TeamMember / Partner | Media | Photo / logo. MIME and rights at **047**. |
| Page | Media | Via approved blocks only. |
| Page `contact` | ContactRequest | None. Form persistence is **048**. |
| Page `technical_inspection` | SecurityPoint / Equipment | None here. **043**. |

Cardinalities for the ERD: **051**.

## 8. Out of this step

| Item | Step |
|------|------|
| Product codes, translations, `external_url` | 041 (done — [solution-entities.md](solution-entities.md)) |
| Expertise | 042 |
| Twelve points, equipment | 043 |
| Media path / MIME / alt | 047 |
| ContactRequest pipeline | 048 |
| SEO field set and Redirect | 049 |
| Translation table DDL | 050 |
| ERD, dictionary, migrations | 051–056 |
| Approved block JSON / Filament pages | 105 / 088+ |

## 9. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Corporate entities specified (step 040 executed) | 2026-10-21 |

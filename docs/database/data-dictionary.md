# Data dictionary

| | |
|--|--|
| **Step** | 052 — Produce the data dictionary |
| **Parent** | [erd.md](erd.md) (051) · [translation-strategy.md](translation-strategy.md) (050) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Field, type, purpose, validation |

This is the step 052 deliverable. Constraints: [constraints.md](constraints.md). Indexes: [indexes.md](indexes.md). **No migrations before gate 055.**

Shared on publishable **bases** unless noted: `publication_status` string (A-09 enum), `published_at` timestamp NULL, `scheduled_for` timestamp NULL, `created_at` / `updated_at`, `deleted_at` NULL (recovery only).

Shared on **translations** unless noted: `id`, `{parent}_id` FK, `locale` char(2) `fr`\|`en`, `meta_title` string NULL, `meta_description` string NULL, timestamps.

`slug` string 191 where the type uses slugs.

## Identity (already installed — 057 does not recreate)

`users`, Spatie `roles` / `permissions` / pivots, `activity_log`. Eight role names frozen. Do not add a ninth.

## Corporate (040)

**site_settings** (one row): `legal_name`, `brand`, `building`, `city`, `country`, `postal_box`, `telephone`, `email`, `canonical_url` string NOT NULL (frozen 004); `social_linkedin`…`social_instagram` string NULL; `geo_latitude` / `geo_longitude` decimal NULL.

**site_setting_translations:** `tagline` string NULL.

**pages:** `code` string unique.

**page_translations:** `slug`, `title` string NOT NULL; `blocks` JSON NOT NULL default `[]`.

**team_members:** `full_name` string NOT NULL; `sort_order` unsigned int NOT NULL default 0.

**team_member_translations:** `slug`, `title` string NOT NULL; `bio` text NULL.

**partners:** `name` string NOT NULL; `sort_order` unsigned int; `url` string NULL.

**partner_translations:** `slug`; `blurb` text NULL.

**careers:** `kind` string `job`\|`internship`; `closes_at` timestamp NULL; `application_url` string NULL.

**career_translations:** `slug`, `title` string NOT NULL; `body` JSON/text.

## Products (041)

**products:** `code` string unique (`CERTIDOCS_CT` \| `CERTIDOCS_CT_REPORTING` \| `CT_VERIF` \| `DOSER` \| `VIDEO_ENFORCEMENT`); `public_name` string NOT NULL; `sort_order` tinyint unique 1–5; `slug_fragment` string NOT NULL; `external_url` string NULL.

**product_translations:** `slug` (= fragment); `summary` text NULL; `body` JSON; `external_label` string NULL.

## Expertise (042)

**expertises:** `code` string unique (five codes); `sort_order` tinyint unique 1–5; `icon` string NULL.

**expertise_translations:** `slug`, `title` string NOT NULL; `summary` text NULL; `body` JSON.

**product_expertise:** `product_id`, `expertise_id` unique pair.

## Technical inspection (043)

**security_points:** `sort_order` tinyint unique 1–12.

**security_point_translations:** `title` string NOT NULL; `body` text NOT NULL. No slug.

**equipment:** `lifecycle` string `current`\|`legacy`; `sort_order` unsigned int.

**equipment_translations:** `slug`, `title` string NOT NULL; `body` JSON.

## Centres (044)

**regions:** `code` char(2) unique (`AD`…`SW`).

**region_translations:** `name` string NOT NULL; `slug` string NOT NULL.

**inspection_centres:** `region_id` FK NOT NULL; `operator_name` string NOT NULL; `city` string NOT NULL; `phone` / `email` string NULL; `latitude` / `longitude` decimal NULL; `verification_status` string NOT NULL default `unverified`; `last_verified_at` timestamp NULL; `verified_by` FK users NULL; `sort_order` unsigned int NULL.

**inspection_centre_translations:** `slug`, `name` string NOT NULL; `address_line` string NULL; `body` text NULL.

## Editorial (045)

**categories:** `sort_order` unsigned int.

**category_translations:** `slug`, `name` string NOT NULL.

**posts:** `kind` string `news`\|`article`; `author_id` FK users NULL.

**post_translations:** `slug`, `title` string NOT NULL; `summary` text NULL; `body` JSON.

**post_category**, **post_product**, **post_expertise:** unique FK pairs.

## Resources (046)

**resources:** `regulatory_status` string `current`\|`historical`\|`archive` NOT NULL; `authority` string NULL; `issued_on` date NULL; `relates_to_ct` boolean NOT NULL default false.

**resource_translations:** `slug`, `title` string NOT NULL; `summary` text NULL.

**resource_product:** unique FK pair.

## Communication (048)

**contact_requests:** `name` string NOT NULL; `email` string NOT NULL; `phone` string NULL; `subject` string NULL; `message` text NOT NULL; `locale` char(2) NOT NULL; `status` string NOT NULL default `new`; `ip` string NULL; `user_agent` string NULL; `notified_at` timestamp NULL; timestamps. No soft delete required.

## SEO (049)

**redirects:** `from_host` string NOT NULL; `from_path` string NOT NULL; `to_path` string NOT NULL; `status_code` smallint NOT NULL default 301; `is_active` boolean NOT NULL default true; unique `(from_host, from_path)`.

## Media (047)

Spatie `media` table. Required custom properties: `alt.fr`, `alt.en` (images); `rights` string. Disks: `public`, `documents`. MIME whitelist in application validation, not a SQL enum.

## Validation already frozen

- Contact values from the contact record, not copied per page.
- `(locale, slug)` unique per translation table that has slugs.
- Business `archive` / `legacy` / `historical` are status fields, not soft delete.
- No sixth product, no thirteenth security point, no eleventh region, no ninth staff role.
- No binaries in MySQL.

## Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Data dictionary produced (step 052 executed) | 2026-10-21 |

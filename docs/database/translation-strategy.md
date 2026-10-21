# Translation strategy

| | |
|--|--|
| **Step** | 050 — Define the translation strategy |
| **Parent** | [ADR-001](../architecture/adr-001-translation-tables.md) · [principal-entities.md](principal-entities.md) |
| **Next** | 074 — Implement solution actions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Parent records plus FR/EN translations |

This is the step 050 deliverable. It **confirms ADR-001**. Do not mix `spatie/laravel-translatable`. Locales are `fr` (default, unprefixed URLs) and `en` (`/en/…`). There is no locale table. **No migrations before gate 055.**

## 1. Pattern

Each translatable CMS type has:

1. A **base** row (identity, publication or verification, locale-independent facts).
2. Zero or more **`{entity}_translations`** rows keyed by `(parent_id, locale)` with `locale ∈ {fr, en}`.

Public URL for a locale exists only when:

- the base is in the public state for that type (A-09 Published, or centre `active`), and
- a translation row exists for that locale (and a slug where the type uses slugs).

UI chrome (buttons, nav labels) may fall back FR→EN or to `lang/` files. **Marketing body must not fall back.** Missing EN body = no EN URL.

## 2. Which types use the pattern

| Type | Base | Translation table | Slug on translation? |
|------|------|-------------------|----------------------|
| Settings | singleton | `site_setting_translations` | no |
| Page | `pages` | `page_translations` | yes (`code` on base) |
| TeamMember | `team_members` | `team_member_translations` | yes |
| Partner | `partners` | `partner_translations` | yes |
| Career | `careers` | `career_translations` | yes |
| Product | `products` | `product_translations` | yes, **must equal** frozen fragment |
| Expertise | `expertises` | `expertise_translations` | yes |
| SecurityPoint | `security_points` | `security_point_translations` | **no** (identity is `sort_order`) |
| Equipment | `equipment` | `equipment_translations` | yes |
| Region | `regions` | `region_translations` | yes (filter) |
| InspectionCentre | `inspection_centres` | `inspection_centre_translations` | yes |
| Category | `categories` | `category_translations` | yes |
| Post | `posts` | `post_translations` | yes |
| Resource | `resources` | `resource_translations` | yes |

## 3. Not translated this way

| Type | Why |
|------|-----|
| User, Role, Permission, Activity | Identity / audit. Role **display** names live in `lang/fr` and `lang/en`. |
| ContactRequest | Inbox row; `locale` is the submitter’s form language only. |
| Redirect | Host + path map. `to_path` is already localized. |
| Media | Alt text in Spatie `custom_properties` (`alt.fr` / `alt.en`) — documented exception (047). |

## 4. Shared translation columns

| Column | Rule |
|--------|------|
| `{parent}_id` | FK, cascade on delete of parent. |
| `locale` | `fr` or `en`. Unique with `{parent}_id`. |
| `slug` | When used: unique per `(locale, slug)` **among that table**. |
| `meta_title` / `meta_description` | Optional SEO (049). |

Product slugs are **not** independent across locales (041). Other types may use different FR/EN slugs.

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Translation strategy confirmed (step 050 executed; ADR-001 accepted) | 2026-10-21 |

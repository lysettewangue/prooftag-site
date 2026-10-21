# Editorial entities

| | |
|--|--|
| **Step** | 045 — Define editorial entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [functional-spec.md](../requirements/functional-spec.md) P-07 / A-09 |
| **Next** | 074 — Implement solution actions |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Posts, categories, and publication states specified |

This is the step 045 deliverable. Domain: `App\Domain\Editorial`. It also **names the shared publication enum** used by other publishable CMS types (040–043, 046). **No migrations before gate 055.**

Drafts are **never** public. Direct URL of a non-published post is 404 except authenticated preview.

## 1. Shared publication states (A-09)

Used on: Page, TeamMember, Partner, Career, Product, Expertise, SecurityPoint, Equipment, Post, Category, Resource.

| State | Public? | Who may enter |
|-------|---------|----------------|
| `draft` | No | update |
| `under_review` | No | update |
| `approved` | No | update / review |
| `scheduled` | No until `scheduled_for` | **publish** |
| `published` | Yes, if `published_at` ≤ now and translation exists | **publish** |
| `archived` | No (404) | **publish** |

One schedule per **base** record. Soft delete is recovery, not a seventh public state.

Settings, Region, InspectionCentre, ContactRequest, Redirect, User do **not** use this enum (centre uses verification_status; contact uses inbox statuses).

## 2. Category

| Side | Attribute | Rule |
|------|-----------|------|
| Base | Publication | Shared A-09. Unpublished categories hide from public filters. |
| Base | `sort_order` | |
| Translation | `locale`, `slug`, `name` | Slug unique per `(locale, slug)`. |

## 3. Post

Index `/actualites` · `/en/news`; detail `/{slug}`.

### Base

| Attribute | Rule |
|-----------|------|
| `kind` | `news` or `article`. |
| Publication | Shared A-09. |
| `published_at` / `scheduled_for` | Scheduler + LWS cron (ADR-006). |
| `hero_image` | Optional Media (047). |
| `author_id` | Optional staff User. Not a public byline until verified. |

### Translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | Unique per `(locale, slug)`. Independent FR/EN slugs allowed. |
| `title` / `summary` / `body` | Approved blocks. |
| SEO override | Optional (**049**). |

## 4. Relationships

| Association | Rule |
|-------------|------|
| Post ↔ Category | Optional many-to-many `post_category`. |
| Post ↔ Product | Optional tagging `post_product` (041). |
| Post ↔ Expertise | Optional tagging `post_expertise` (042). |

Career is **not** a Post (040).

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Editorial entities and shared publication states specified (step 045 executed) | 2026-10-21 |

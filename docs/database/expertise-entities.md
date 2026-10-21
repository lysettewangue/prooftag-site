# Expertise entities

| | |
|--|--|
| **Step** | 042 — Define expertise entities |
| **Parent** | [principal-entities.md](principal-entities.md) (039) · [solution-entities.md](solution-entities.md) (041) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Five expertise domains specified |

This is the step 042 deliverable. Domain: `App\Domain\Expertise`. **No migrations before gate 055.**

Exactly **five** expertise rows. Names must **not** copy product names (003). Not a sixth product. Not Contrôle technique.

## 1. Invariants

- Five seeded base rows. Staff cannot create a sixth Expertise or delete one of the five. Visibility is publication.
- `code` unique and not editable after create.
- `sort_order` 1–5 matching the sitemap order. Not freely reordered without a sitemap revision.
- Publication on the base (A-09, **045**), independent of Product publication.
- Technical Editor updates; Translator owns EN; Reviewer/Publisher publishes. Eight roles only.

## 2. Expertise (base)

| Attribute | Rule |
|-----------|------|
| `code` | One of: `RESEARCH_DEVELOPMENT`, `TECHNICAL_SUPPORT`, `DATA_ANALYSIS_REPORTING`, `DOCUMENT_SECURITY`, `ROAD_SAFETY_PREVENTION`. |
| `sort_order` | 1–5. |
| `icon` | Optional Media or Lucide key. Not a product mark. |
| Publication | Shared A-09 states on the base. |
| `published_at` / `scheduled_for` | One schedule, all locales. |

## 3. Expertise translation

| Attribute | Rule |
|-----------|------|
| `locale` | `fr` or `en` |
| `slug` | Unique per `(locale, slug)`. FR fragments frozen below. |
| `title` | Domain name. Must not equal a product `public_name`. |
| `summary` / `body` | Approved blocks. Unverified claims stay Draft. |
| SEO override | Optional; else Settings defaults (**049**). |

| `code` | `sort_order` | FR path | EN path (working slug) |
|--------|-------------:|---------|------------------------|
| `RESEARCH_DEVELOPMENT` | 1 | `/expertises/recherche-et-developpement` | `/en/expertise/research-and-development` |
| `TECHNICAL_SUPPORT` | 2 | `/expertises/support-technique` | `/en/expertise/technical-support` |
| `DATA_ANALYSIS_REPORTING` | 3 | `/expertises/analyse-donnees-reporting` | `/en/expertise/data-analysis-reporting` |
| `DOCUMENT_SECURITY` | 4 | `/expertises/securite-documentaire` | `/en/expertise/document-security` |
| `ROAD_SAFETY_PREVENTION` | 5 | `/expertises/securite-prevention-routiere` | `/en/expertise/road-safety-prevention` |

Index `/expertises` · `/en/expertise` is a listing query, not a row.

## 4. Relationships

| Association | Rule |
|-------------|------|
| Product ↔ Expertise | Optional many-to-many `product_expertise` (041). |
| Post ↔ Expertise | Optional tagging `post_expertise` (**045**). |
| Expertise → Product | Must not duplicate a product identity. |

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Expertise entities specified (step 042 executed) | 2026-10-21 |

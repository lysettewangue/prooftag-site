# Database indexes

| | |
|--|--|
| **Step** | 054 — Define database indexes |
| **Parent** | [constraints.md](constraints.md) (053) · [functional-spec.md](../requirements/functional-spec.md) |
| **Next** | 057 — Implement system and identity tables |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-21 |
| **Status** | Indexes for real queries specified |

This is the step 054 deliverable. Indexes exist only for **actual** public and admin queries on LWS Starter (MySQL 8). Unique constraints already provide indexes (053). **No migrations before gate 055.**

## 1. Public queries

| Query | Index |
|-------|--------|
| Product / expertise / page / post / resource / equipment / career / centre **by locale + slug** | Unique `(locale, slug)` on each translation table |
| Homepage / product index: published products in order | `products (publication_status, published_at, sort_order)` |
| News listing + pagination | `posts (publication_status, published_at)` |
| Centre directory: Active + region + pagination | `inspection_centres (verification_status, region_id)` |
| Centre / news / resource search (MySQL `LIKE` / FULLTEXT later if needed) | Start with slug/title indexes; no specialist engine (ADR-004) |
| Redirect resolve | Unique `(from_host, from_path)` |
| Sitemap: published translations | `(locale)` already in unique pairs; filter via parent publication_status |

## 2. Admin / inbox

| Query | Index |
|-------|--------|
| Contact inbox by status | `contact_requests (status, created_at)` |
| Activity log | Spatie defaults |
| Scheduled publish job | `(publication_status, scheduled_for)` on each publishable base that uses scheduling: `pages`, `products`, `posts`, `resources`, `careers`, `expertises`, `equipment`, `team_members`, `partners`, `security_points` |

Do not index every column. No index on long `body` / JSON `blocks`.

## 3. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Database indexes specified (step 054 executed) | 2026-10-21 |

# Public frontend gate

| | |
|--|--|
| **Step** | 150 — Review the public frontend |
| **Parent** | [149 — Sitemap & robots](public-sitemap-robots.md) · [122 — UI/UX approval](../design/ui-ux-approval.md) |
| **Next** | 151 — Migrate corporate content |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-28 |
| **Status** | **Gate passed.** Templates bind to CMS data; drafts stay private |

## 1. Scope

Steps **123–149** delivered the public site on Laravel Blade + Tailwind 4 against the frozen V1 model. This gate confirms those templates **work on real CMS rows** (and correct empty / 404 behaviour) before content migration **151+**.

It does **not** approve: launch copy, EN translation completeness, LWS deploy, WCAG automation (181), or the release candidate (186).

## 2. Template inventory (CMS-bound)

| Band | Deliverable | Template / surface |
|------|-------------|--------------------|
| 123–129 | Tokens, layout, nav, footer, components, Lucide, JS | Shared public shell |
| 130 | Homepage | `home` + published products / expertises |
| 131 | About family | `pages/corporate` (draft → 404) |
| 132–133 | Products | Index + detail |
| 134 | Expertise | Index + detail |
| 135–137 | Contrôle technique | Hub (page), 12 points, equipment |
| 138–139 | Centres | Directory + detail (Active only) |
| 140 | Resources | Library + detail (+ controlled download) |
| 141–142 | News | Listing + article |
| 143 | Careers | Page listing + career detail |
| 144–145 | Contact / legal | Form + legal pages |
| 146–147 | Search / language | LIKE search + FR↔EN switcher |
| 148–149 | SEO / sitemap | Head tags + `/sitemap.xml` + `/robots.txt` |

## 3. Local CMS snapshot (review day)

| Type | Publicly visible | Notes |
|------|------------------|-------|
| Pages (11) | 0 | All **draft** — About, contact, legal, CT hub → **404** (expected) |
| Products (5) | 0 | Indexes show empty state; details 404 |
| Expertises (5) | 0 | 4 draft + 1 archived; index empty |
| Security points (12) | 0 | Index 200 + empty; no draft leakage |
| Equipment / posts / resources / careers | 0 | Indexes/empty or 404 on missing slug |
| Inspection centres | **4 Active** | Directory + sample detail **200** FR/EN |

Home stays **200** with UI copy when the `home` page is draft (SEO falls back). Publication gates are enforced in catalogs / `publiclyVisible` / `publiclyListed`.

## 4. HTTP smoke (2026-10-28)

Against `php artisan serve` (`http://127.0.0.1:8000`):

| Expect | Routes checked |
|--------|----------------|
| **200** | `/`, `/en`, product/expertise/equipment/centres/resources/news indexes (FR+EN), security points, centre detail sample, search (±`q`), `/sitemap.xml`, `/robots.txt` |
| **404** | Draft About tree, contact, legal, privacy, CT hub (FR+EN); missing product/post/centre slugs |

Result: **all checks passed.** Sample pages expose public shell, one `h1`, canonical + hreflang, locale switcher, and Open Graph (148). Robots local = `Disallow: /`. Sitemap locs use `https://www.prooftagcatis.com`.

## 5. Contract confirmed

| Rule | Result |
|------|--------|
| Draft / unpublished never listed or detail-reachable | Pass |
| Active centres only in directory | Pass (4 listed) |
| Empty indexes are 200 + empty state, not fake cards | Pass |
| Locale switch uses hreflang; no invented slugs | Pass (147) |
| Canonical host, OG, sitemap/robots | Pass (148–149) |
| Admin out of public sitemap | Pass |

## 6. Deferred (not this gate)

| Item | Step |
|------|------|
| Migrate / publish corporate, products, CT, centres, resources, news | 151–160 |
| FR editorial + EN translation review | 161–163 |
| Legacy redirects + DITROS decision | 165–167 |
| Automated / browser / a11y suites | 168–181 |
| Staging / production | 187+ |

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Public templates work on CMS data; drafts stay private; migration band unlocked | 2026-10-28 |

**Gate 150 is passed. Steps 001–150 are complete. Step 151 is unlocked.**

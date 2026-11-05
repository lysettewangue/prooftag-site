# Automated test band (168–173)

| | |
|--|--|
| **Steps** | 168 unit · 169 feature · 170 authorization · 171 database · 172 email/filesystem · 173 browser |
| **Parent** | [implement-redirects.md](implement-redirects.md) · [acceptance-checklist.md](../qa/acceptance-checklist.md) |
| **Next** | 174 — [Static analysis](static-analysis.md) (COMPLETED) · 175 Pint |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | Suites in place; run commands below |

## 1. Tooling

| Piece | Notes |
|-------|-------|
| Pest 5 + Laravel plugin | Existing Feature/Unit |
| `pestphp/pest-plugin-browser` ^5.2 | Playwright Chromium |
| PHP `ext-sockets` | Required by the browser plugin |
| `tests/Browser/{Screenshots,Traces,Videos}` | gitignored |

```powershell
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
php artisan test --testsuite=Browser
# or
php artisan test
```

Do **not** run Feature and Browser in parallel against the same MySQL testing database.

## 2. Step map

### 168 — Unit (isolated business rules)

| File | Covers |
|------|--------|
| `tests/Unit/Domain/PublicationRulesTest.php` | Visibility, transitions, centres, product URL gate, contact pipeline |
| `tests/Unit/Domain/ContentBlocksTest.php` | Block whitelist / flatten |
| `tests/Unit/Domain/ExpertiseTitleRulesTest.php` | **New** — titles ≠ product names |
| `tests/Unit/Domain/MediaRulesTest.php` | **New** — MIME allow-list, size, images |

### 169 — Feature (routes, publishing, centres, contact)

| File | Covers |
|------|--------|
| Existing `Public*`, `BackendActionsTest`, `VisibilityRulesTest`, centre admin | Prior coverage |
| `PublicRouteMatrixTest.php` | **New** — FR/EN migrated routes + draft gates |
| `PublicContactFormTest.php` | **New** — HTTP contact + honeypot |
| `LegacyRedirectHttpTest.php` | **New** — Host-aware 301s (DITROS → CT hub) |

Migration seeds publish verified content; draft-only cases call `ResetsPublicationToDraft`.

### 170 — Authorization (allowed / denied)

| File | Covers |
|------|--------|
| `AdminPermissionsGateTest.php` | Eight-role module matrix |
| Module admin tests | Per-resource allow/deny |
| `PublishAuthorizationTest.php` | **New** — Content Manager / Translator denied publish; Reviewer allowed; Support denied redirects |

### 171 — Database integration

| File | Covers |
|------|--------|
| `DatabaseStructureTest.php` | Seeds, CHECK constraints, uniqueness |
| `ModelFreezeTest.php` | V1 catalogue freeze |
| `DatabaseRelationsTest.php` | **New** — translation CASCADE, region RESTRICT, redirect host CHECK, 23 active redirects |

### 172 — Email and filesystem

| File | Covers |
|------|--------|
| `BackendActionsTest` contact/media cases | Prior |
| `EmailAndFilesystemTest.php` | **New** — ContactReceivedMail reply-to; reject `.exe`; allowed image on `public` disk |

### 173 — Browser (critical journeys)

| File | Covers |
|------|--------|
| `tests/Browser/CriticalJourneysTest.php` | Home brand/CTA; contact submit; FR→EN switch; CERTIDOCS CT page |

```powershell
npx playwright install chromium   # once per machine
php artisan test --testsuite=Browser
```

## 3. Fixes bundled with this band

- Draft-assuming public tests demote published migration content before empty/404 assertions.
- `RedirectsAdminTest` expects the loaded legacy register (23 active).
- `DatabaseStructureTest` accepts migration-published CERTIDOCS CT.

## 4. Out of band

| Item | Step |
|------|------|
| Larastan / Pint gate | 174–175 |
| CI wiring | 176 |
| Manual QA / a11y | 177–181 |

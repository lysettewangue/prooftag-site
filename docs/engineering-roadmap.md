# Engineering roadmap

Sequential execution for the PROOFTAG-CATIS corporate platform. The working list is [STEP-BY-STEP.md](STEP-BY-STEP.md). This page states the rules and where each band is specified. It does not repeat the step list.

**Model:** Greenfield modular monolith. One active step. Local release candidate, then LWS staging, then production.

## Execution rule

A step starts only when the previous step is **COMPLETED**: implemented, tested where it applies, deliverable produced, and completion criteria met.

| Status | Meaning |
|--------|---------|
| NOT STARTED | Unlocked. Nothing done yet. |
| IN PROGRESS | Started. Criteria not met. |
| BLOCKED | Missing information, access, or approval. |
| COMPLETED | Deliverable and criteria satisfied. Unlocks the next step. |
| LOCKED | Prerequisite is not COMPLETED. |

Only one step is normally active. `LOCKED` is not a failure.

## Stack

| | |
|--|--|
| App | Laravel 13, PHP 8.4, MySQL, Eloquent, modular monolith |
| Public | Blade, Tailwind 4, Vite, vanilla JS, Alpine where useful, Lucide |
| Admin | Filament 5, Livewire, Heroicons |
| Quality | Pest, browser tests, Larastan, Pint, Git |
| Host | LWS Starter. No production Docker, Node server, Redis, Horizon, or Octane |
| Locales | French default, English secondary |

Products: CERTIDOCS CT, CERTIDOCS CT Reporting Platform, CT-VERIF, DOSER, Video Enforcement. DITROS-CT is not a product. Its old URL is decided at step 166.

## Sequence

| Steps | Band | Spec |
|------:|------|------|
| 001–006 | Charter, scope, products, contact, brand, initial sitemap | [charter/](charter/) |
| 007–015 | Legacy archive and content audit | [legacy/](legacy/), [content/](content/) |
| 016–022 | Audiences, requirements, final sitemap, content types | [requirements/functional-spec.md](requirements/functional-spec.md), [ia/sitemap-and-content-types.md](ia/sitemap-and-content-types.md) |
| 023–025 | Modular monolith, four layers, three environments | [architecture/system-architecture.md](architecture/system-architecture.md) |
| 026–038 | Local machine, Git, Laravel, Filament shell, roles, quality tools | [development/](development/) |
| 039–055 | Database design. No migrations before 055 | [database/erd.md](database/erd.md), [database/data-dictionary.md](database/data-dictionary.md) |
| 056–069 | Migrations, verified seeds, database tests | [development/local-schema.md](development/local-schema.md) |
| 070–087 | Models, actions, policies, media, mail, search, cache, scheduler, SEO | [backend/](backend/) |
| 088–106 | Filament modules and permission test | [admin/content-blocks.md](admin/content-blocks.md) |
| 107–113 | Representative content, then freeze the V1 data model | |
| 114–122 | Wireframes, then visual design. No frontend code before 122 | [design/design-brief.md](design/design-brief.md) |
| 123–150 | Public templates on CMS data | |
| 151–167 | Migration, FR/EN, redirects, DITROS decision | |
| 168–185 | Tests, CI, QA, security, performance | [qa/acceptance-checklist.md](qa/acceptance-checklist.md) |
| 186 | Local release candidate. No LWS work before this | |
| 187–205 | LWS account, staging, acceptance | [deploy/lws-hosting-notes.md](deploy/lws-hosting-notes.md) |
| 206–233 | Production freeze, deploy, cutover, smoke tests | [deploy/rollback-outline.md](deploy/rollback-outline.md) |
| 234–244 | Monitoring, manuals, restore test, handover | [governance/content-governance.md](governance/content-governance.md) |
| 245–250 | Maintenance and controlled change | |

## Gates

Do not pass these until the step is COMPLETED: **055** database design, **069** database tests, **106** admin permissions, **113** data-model freeze, **122** UI/UX approval, **150** public templates on real data, **186** release candidate, **205** staging acceptance.

> The previous step must be marked COMPLETED before the next step is unlocked.

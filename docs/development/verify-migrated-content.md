# Verify migrated content

| | |
|--|--|
| **Step** | 160 — Verify migrated content |
| **Parent** | [content-audit.csv](../content/content-audit.csv) · [verification-register.md](../content/verification-register.md) · [reconciliation.md](../content/reconciliation.md) · migrations 151–159 |
| **Next** | 161 — Complete the French editorial review → [french-editorial-review.md](french-editorial-review.md) (COMPLETED) · 162 EN |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | Compared with audit; VER holds respected; rejects absent from public |

## 1. Method

1. Read AUD-001–028 destinations and accuracy statuses.
2. Dump CMS publication flags (pages, products, expertises, equipment, centres, resources, posts, media, team/partners/careers).
3. HTTP smoke on FR destinations (+ sample EN).
4. Spot-check: no exclusive-operator homepage claim; CERTIDOCS CT disclaims DITROS-CT; team/partners empty publicly; only Active centres listed.

VER-01–13 stay **Open**. This step does not close verification rows and does not approve final FR/EN copy (161–163).

## 2. Audit matrix

| AUD | Accuracy | Expected destination | CMS / public result | OK |
|-----|----------|----------------------|---------------------|----|
| 001 | rewrite | `/controle-technique/points-de-securisation` | 12 security points **Published**; route **200** | yes |
| 002 | current | `/a-propos` | About **Published**; **200**; no VER-12 Prooftag SAS claim | yes |
| 003 | rewrite | `/produits/certidocs-ct` | CERTIDOCS CT **Published**; DITROS name disclaimed; **200** | yes |
| 004 | verification | `/produits/ct-verif` | Product **Draft** + mark attached; public **404** (VER-04/11) | yes |
| 005 | current† | `/produits/doser` | Product **Draft** + mark; **404** (VER-05/11) | yes |
| 006 | rewrite | `/controle-technique` | CT hub page **Published**; equipment + points live; **200** | yes |
| 007 | verification | `/centres-de-controle` | 4 **Active** publicly listed; 14 **Unverified** not listed; **200** | yes |
| 008 | verification | `/a-propos/equipe` | Page Published (empty list); sample team **Draft**; **200** | yes |
| 009 | historical | `/a-propos/historique` | History **Published** with 2020 timeline; **200** | yes |
| 010 | rewrite | `/expertises/securite-documentaire` | Expertise **Published**; **200** | yes |
| 011 | verification | `/produits/video-enforcement` | **Draft**; **404** (VER-07) | yes |
| 012 | verification | `/produits/certidocs-ct-reporting` | **Draft**; **404** (VER-06) | yes |
| 013 | reject | — | Homepage stats 0/0 not migrated | yes |
| 014 | reject | — | Legacy `/en` French body not scraped (REC-10) | yes |
| 015 | rewrite | `/expertises/recherche-et-developpement` | **Published**; **200** | yes |
| 016 | rewrite | `/expertises/support-technique` | **Published**; **200** | yes |
| 017 | rewrite | `/expertises/analyse-donnees-reporting` | **Published**; **200** | yes |
| 018 | rewrite | `/expertises/securite-prevention-routiere` | **Published**; **200** | yes |
| 019 | verification | `/a-propos/organisation` | Page **Draft**; public **404** (VER-13 / REC-01) | yes |
| 020 | rewrite | CERTIDOCS CT | Folded into product rewrite; empty www services not published as invent | yes |
| 021 | current | `/a-propos/carrieres` | Careers page **Published**; sample career **Draft**; empty public OK | yes |
| 022 | verification | `/a-propos/partenaires` | Partners page Published (empty); sample MINT **Draft** (VER-09) | yes |
| 023 | current | `/contact` | Contact **Published** from frozen settings; **200** | yes |
| 024 | reject | — | `net.` host not content-migrated | yes |
| 025 | rewrite | `/produits` | Index lists **CERTIDOCS CT** only; no DITROS card; **200** | yes |
| 026 | rewrite | `/expertises` | Index **200**; five expertises published | yes |
| 027 | archive | `/actualites` | Structure live; 2 published samples (not legacy scrape); draft `nouvelle-ct` **404** | yes |
| 028 | current | Brand | SiteSetting logo + `public/images/brand/*`; DITROS marks rejected | yes |

† AUD-005 marked `current` in the frozen audit but public release still blocked by VER-05 / VER-11 — correctly held as Draft.

## 3. HTTP smoke (local)

| Code | Paths |
|------|-------|
| **200** | `/`, `/a-propos`, `/historique`, `/equipe`, `/partenaires`, `/carrieres`, `/contact`, `/produits`, `/produits/certidocs-ct`, `/expertises` (+ detail), `/controle-technique` (+ points, équipements), `/centres-de-controle`, `/ressources`, `/actualites`, `/en/about`, `/en/products/certidocs-ct` |
| **404** | `/a-propos/organisation`, `/produits/ct-verif`, `/doser`, reporting, video-enforcement, `/actualites/nouvelle-ct` |

## 4. Residual local samples (not public)

| Row | Status | Note |
|-----|--------|------|
| TeamMember TCHANGA Jean | Draft | Representative seeder; VER-02 |
| Partner MINT | Draft | VER-09 |
| Career Ingénieur CT | Draft | Not listed publicly |
| Resource `guide-ct` | Draft | LibraryDocumentSeeder leftover; superseded by migrated `guide-processus-controle-technique` |
| Post `nouvelle-ct` | Draft | 404 |

## 5. Manual check

1. Walk the audit matrix above against admin + public.
2. Confirm VER-blocked destinations stay **404** or empty listings.
3. Confirm rejects (AUD-013/014/024) and DITROS chrome are absent.
4. FR/EN copy quality → **161–163** (not this step).

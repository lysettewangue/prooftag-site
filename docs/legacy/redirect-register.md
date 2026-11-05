# Legacy redirect register (final)

| | |
|--|--|
| **Step** | 165 — Finalise the legacy redirect register |
| **Parent** | [legacy-url-register.csv](legacy-url-register.csv) (008) · [seo-entities.md](../database/seo-entities.md) · [verify-migrated-content.md](../development/verify-migrated-content.md) |
| **Next** | 166 — [DITROS-CT URL](ditros-ct-url.md) (COMPLETED) · 167 — [Implement redirects](../development/implement-redirects.md) (COMPLETED) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | **Final** deliberate map for V1 load — [redirect-register.csv](redirect-register.csv) |

Canonical host remains `https://www.prooftagcatis.com`. French `to_path` values are unprefixed. English legacy body is not redirected as content (REC-10); only `/en` path preservation on `site`.

## 1. Host policy

| Host | Behaviour |
|------|-----------|
| `www.prooftagcatis.com` | Canonical — redirect only when the **legacy path** differs from the new tree |
| `site.prooftagcatis.com` | Every strategic path → matching www `to_path` |
| `net.prooftagcatis.com` | Retire — homepage → `/`; other paths via server catch-all at implement (167+) |
| `prooftagcatis.com` | Apex → `/` |

Paths are stored **without** trailing slash (`RedirectResolver` normalises).

## 2. Loadable rows

Source of truth for 167: [redirect-register.csv](redirect-register.csv).

| Count | Rule |
|------:|------|
| 23 | `is_active=1` — load and enable (includes DITROS → CT hub per **166**) |

## 3. Destination rules (post-migration)

| Legacy | `to_path` | Public today |
|--------|-----------|--------------|
| Corporate / expertise / contact / blog / CT / centres | Matching published FR paths | **200** |
| CT-VERIF / DOSER | Reserved product slugs | **404** until VER closes (intentional) |
| Organisation | `/a-propos/organisation` | **404** until VER-13 |
| DITROS-CT | `/controle-technique` | **200** — [ditros-ct-url.md](ditros-ct-url.md); **not** CERTIDOCS |
| site `/en` | `/en` | EN home — no legacy body |

Do **not** invent EN `to_path` variants in this register: one canonical FR (or `/en`) target per legacy URL. Locale switch on the destination page handles EN.

## 4. Out of register

| Item | Handling |
|------|----------|
| www homepage `/` | Already canonical — no self-redirect row |
| Uncaptured www menu URLs (galerie, lois, …) | HTTrack / host-panel before cutover; extend CSV then |
| Query strings / media binaries | Not redirect rows; media at 159 archive |

## 5. Manual check (register only)

1. Every strategic row from [legacy-url-register.csv](legacy-url-register.csv) appears once (normalised path).
2. DITROS row active → `/controle-technique` only ([ditros-ct-url.md](ditros-ct-url.md)).
3. No `to_path` uses a DITROS slug; DITROS does not target CERTIDOCS.
4. Implementation / DB seed → **167**.

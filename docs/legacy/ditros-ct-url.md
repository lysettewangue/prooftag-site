# DITROS-CT URL behaviour

| | |
|--|--|
| **Step** | 166 — Decide DITROS-CT URL behaviour |
| **Parent** | [redirect-register.md](redirect-register.md) · [products.md](../charter/products.md) · [reconciliation.md](../content/reconciliation.md) |
| **Next** | 167 — [Implement redirects](../development/implement-redirects.md) (COMPLETED) · 168 unit tests |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | **Decided** |

## 1. Decision

| Field | Value |
|-------|-------|
| Legacy | `site.prooftagcatis.com` + `/solutions/ditros-ct` |
| Behaviour | **301** to www |
| `to_path` | `/controle-technique` |
| `is_active` | **1** (load at 167) |
| New slug / nav / CMS product | **None** — DITROS-CT remains not a product |

Register row: [redirect-register.csv](redirect-register.csv).

## 2. Why not CERTIDOCS CT

| Option | Verdict |
|--------|---------|
| 301 → `/produits/certidocs-ct` | **Rejected** — asserts DITROS ≡ CERTIDOCS (fails step criterion *No implied Certidocs relationship*) |
| 301 → `/produits` | Weak topical match; still frames a “solutions rename” |
| Leave inactive (404) | Soft equity loss; strategic legacy URL deserves a landing |
| **301 → `/controle-technique`** | **Chosen** — topical CT process home (REC-04); no product synonym |

Content rewrite destinations (AUD-003 / REC-03–04) stay valid: factual CT text may live in CERTIDOCS CT **and** the CT hub. That is editorial absorption, **not** an HTTP product succession.

## 3. Rules locked by this step

1. No `ditros` path fragment on www.
2. No DITROS mark, card, or meta on CERTIDOCS CT or corporate chrome.
3. Do not retarget this row to `/produits/certidocs-ct` without a new charter decision.
4. www `/services` → CERTIDOCS CT (AUD-020) is unrelated and stays as registered.

## 4. Manual check

1. CSV row active, `to_path=/controle-technique`, notes cite 166.
2. No other row points a DITROS path at CERTIDOCS.
3. Public `/produits/certidocs-ct` still has no DITROS naming (161–163).
4. Implementation → **167**.

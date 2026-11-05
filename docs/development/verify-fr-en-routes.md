# Verify French and English routes

| | |
|--|--|
| **Step** | 164 — Verify French and English routes |
| **Parent** | [163 — Technical English](technical-english-review.md) · [public-language-switch.md](public-language-switch.md) · P-09 |
| **Next** | 165–167 COMPLETED → [implement-redirects.md](implement-redirects.md) · 168 unit tests |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | Route check passed — **46/46** pairs; hreflang paired |

## 1. Method

1. Build FR/EN `PublicPath` pairs for indexes + published CMS models (pages, products, expertises, equipment, resources, posts, Active centres).
2. HTTP-check expected **200** (published) or **404** (draft / unpublished).
3. On **200** pages, assert `<link rel="alternate" hreflang>` FR↔EN point at each other (plus `x-default` → FR).
4. Missing translation → no invented EN/FR slug (draft single-locale → 404 only).

## 2. Fix applied (164)

Locale-scoped eager loads (`with(['translations' => locale only])`) made `translationFor('en')` return null while the FR relation was loaded, so `hreflang` omitted the alternate. `HasTranslations::translationFor` now falls back to a query when the loaded collection lacks that locale.

Orphan Active centre **Centre Douala** (local leftover, not in AUD-007 capture) demoted to **Unverified**. Active centres must have EN twins for switch/hreflang.

## 3. Results (local)

| Check | Result |
|-------|--------|
| HTTP pairs | **46 OK / 0 FAIL** |
| hreflang pairs | **0 FAIL** |
| Draft products / organisation / draft post & resource | **404** both sides (or single-locale 404) |
| Indexes (`/produits`↔`/en/products`, CT, centres, news, …) | **200** + paired hreflang |

## 4. Manual check

1. From `/a-propos` switch to EN → `/en/about` (and back).
2. From `/produits/certidocs-ct` switch → `/en/products/certidocs-ct`.
3. View source: both pages expose `hreflang="fr"` and `hreflang="en"`.
4. Draft `/produits/ct-verif` and `/en/products/ct-verif` → **404**.

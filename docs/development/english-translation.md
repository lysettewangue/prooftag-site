# English translation

| | |
|--|--|
| **Step** | 162 — Complete the English translation |
| **Parent** | [161 — French editorial review](french-editorial-review.md) · [glossary-fr-en.md](../content/glossary-fr-en.md) · REC-10 |
| **Next** | 163 — Review technical English → [technical-english-review.md](technical-english-review.md) (COMPLETED) · 164 routes |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-29 |
| **Status** | **English content complete** for published surfaces (new EN, not legacy scrape) |

## 1. Scope

Every **Published** CMS row has a real English translation (`missing_count=0`):

- Corporate page blocks (About family, contact, legal, privacy, CT hub)
- CERTIDOCS CT, five expertises, 12 security points, four equipment rows
- Two resources, two editorial posts
- SiteSetting EN meta / tagline
- `lang/en/ui.php` chrome aligned with FR polish and glossary label **Technical inspection**

Draft-only products and sample team/partner/career rows keep EN drafts for later; they are not public. Legacy `/en` French body was **not** scraped (REC-10).

## 2. Criteria

| Check | Result |
|-------|--------|
| Published FR row → published EN twin | Pass |
| EN is real English (not leftover FR) | Pass |
| Public polish mirrors 161 (no `VER-*`, no DITROS meta-line, no internal step IDs) | Pass |
| Product names untranslated | Pass |
| First-use gloss for vignette / Code à Bulles on CERTIDOCS CT | Pass |
| Chrome uses “Technical inspection” (not mixed “Vehicle inspection”) | Pass |

Deep terminology consistency across every PV / vignette occurrence → **163**.

## 3. Polish applied (162)

| Area | Change |
|------|--------|
| Corporate EN | Softened history / team / partners / contact / privacy |
| CERTIDOCS CT + CT hub EN | Removed DITROS meta-line; glossary first-use on vignette / bubble seal |
| News / article EN | “Active centres” → “active centres”; dropped DITROS mention |
| Resources / equipment / expertise EN | Dropped `(VER-08)`; educational wording; “previous website” |
| `lang/en/ui.php` | Technical inspection chrome; Historical lifecycle label; equipment teaser |

## 4. Manual check

1. `/en`, `/en/about`, `/en/products/certidocs-ct`, `/en/technical-inspection`, `/en/news/…` → **200**, English body.
2. Language switch from FR published pages lands on EN equivalents.
3. No `VER-*` / DITROS / “step 004” on public EN HTML.
4. Terminology deep-pass → **163**.

# Information hierarchy review

| | |
|--|--|
| **Step** | 117 — Review information hierarchy |
| **Parent** | [wireframes.md](wireframes.md) (116) · [ui-ux-brief.md](ui-ux-brief.md) (114) · [model-review.md](../database/model-review.md) (112) |
| **Next** | 118 — Create high-fidelity designs → [hifi.md](hifi.md) (COMPLETED) · 119 responsive |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-27 |
| **Status** | Hierarchy supports real content. Adjustments recorded for 118 |

Compared desktop lo-fi artboards (W01–W11) with representative CMS rows (107–110), frozen catalogues (113), and nav/copy rules (005, 114). This does **not** redesign screens, pick type, or write Blade. Filament `/admin` is out of scope. VER-01–13 stay **Open**.

## 1. Method

| Source | Use |
|--------|-----|
| Wireframes W01–W11 | Proposed order: shell → hero / H1 → primary blocks → secondary → empty / system |
| Product / expertise / security-point / page / post / centre seeds | Real field shapes and lengths (drafts and stubs count as structure) |
| Brand + UI brief | Eight-section nav, no Accueil, audiences not extra items, one H1, WCAG structure |

Fixture copy is structure only. Launch bodies will grow after VER rows close; hierarchy must already tolerate longer text.

## 2. What already fits

| Template | Hierarchy | Why it fits real content |
|----------|-----------|--------------------------|
| W01 shell | Skip → logo home → eight sections → locale → search → footer contact | Matches frozen nav. Footer uses short contact-record lines. No social until VER-10. |
| W02 / W02b home | Hero (no VER-01 claim) → CTAs → published product cards → expertise grid | Product `public_name` is short brand text; cards hide drafts. Empty product grid is a first-class state, not ghost cards. No live counters. |
| W03 product ×5 | Crumb → H1 brand → summary → media → body → related expertise / resources | One pattern for all five codes. Optional `external_url` stays secondary. Unpublished stay off links. |
| W04 CT + 12 points | H1 → intro → numbered accordion → equipment teaser | CT is a Page, not a product. Points are components, not shouty all-caps. |
| W05 / W05b / W05c centres | Filters → **list primary** → map secondary; detail omits missing fields; empty search | Active-only listing. Missing phone / email / coords are normal. No-match ≠ 404. |
| W06 resources | Title → regulatory status **as text** → pagination | Status not colour-only. Missing PDF cannot look Published. |
| W07 / W07b / W07c news | Listing → article H1 + body; EN missing → `/en` | Drafts omitted. Independent FR/EN slugs. Empty listing valid. |
| W08 / W08b contact | Address block + labelled form; error beside field; success | Frozen contact length fits beside the form. |
| W09 legal | Single H1 + narrow reading column (~42rem) | Correct measure for long regulatory blocks when CMS fills `blocks`. |
| W10 system | Short H1 + recovery / silence | 404 / 500 / maintenance stay brand-first, low chrome. |
| W11 careers empty | Crumb → H1 → empty message | Same pattern later for team / partners while VER-02 / VER-09 open. |

Heading order on the artboards stays one `h1` then `h3` section labels — acceptable for lo-fi; hi-fi must use consecutive levels without skips (114 §5).

## 3. Length and density stress

| Content | Observed shape (representative) | Hierarchy consequence |
|---------|----------------------------------|------------------------|
| Nav labels FR | Up to “Actualités & Articles”, “Produits & Solutions” | Desktop header must allow wrap or a second row — do not truncate mid-word or drop a section. |
| Security-point titles | Long FR lines (e.g. carte grise / MINT; vignette + scellé + QR) | Accordion **summary** wraps to 2–3 lines; number stays visible; do not ellipsis the legal sense of the title. |
| Security-point bodies | Short rewrite stubs now; AUD-001 will enlarge | Open panel needs a readable prose measure; closed panels stay title-only. |
| Product summaries | ~1–2 sentences | Card deck + detail lede. Body HTML will grow — keep related blocks **below** the main column. |
| Expertise | Titles present; summaries / bodies often empty | Cards must work **title-only**; do not reserve a mandatory excerpt line that looks broken when empty. |
| Page `blocks` | Often `[]` today | Templates already reserve hero / body / FAQ slots; empty blocks omit the slot (no placeholder latin). |
| Centres | Short names; sparse contact fields | Detail hierarchy stays name → place → optional contacts → optional map. |
| Posts | Short draft title / summary / body | Listing = title + meta; article = long `{html}` in one column. |
| Resources | Sparse until PDF + VER-08 | Library rows stay title + status text; no fake preview hierarchy. |

## 4. Adjustments for high-fidelity (118) — no lo-fi redraw

| ID | Adjustment | Reason |
|----|------------|--------|
| H-01 | Nav: wrapping or two-line primary nav at desktop widths used in 118 | Eight long FR labels |
| H-02 | Accordion: multi-line summaries; keep `1.`–`12.` in view | Real point titles |
| H-03 | Product / expertise cards: title required; summary optional | Empty expertise summary in seeds |
| H-04 | Homepage product strip: 0–5 cards only; when 0, keep CT / Contact CTAs (W02b) | Draft portfolio |
| H-05 | Legal / long Page / article / product body: ~65–75 character measure, single column | W09 already; apply to other long reading surfaces |
| H-06 | Related expertise / resources / equipment: clearly secondary band, not competing H1 | Avoid twin heroes on product / CT |
| H-07 | Statistics / partners / team blocks: omit entirely when CMS empty or VER open | No decorative hierarchy for unverified claims |
| H-08 | Breadcrumbs on product, centre, article, careers | Wireframes show them; keep in hi-fi |
| H-09 | Directory: list remains the accessibility and layout primary when ~35 centres arrive (VER-03) | Pagination already implied |
| H-10 | Language switch: never invent EN paths; visual weight equal for FR/EN chrome | P-09 / W07b |

No change to the eight-section tree, template count, or sitemap. No new artboard required before 118.

## 5. Out of this step

| Item | Step |
|------|------|
| Colour, type, component polish | 118 / 120 |
| Tablet / mobile frames | 119 |
| Accessibility review of composed screens | 121 |
| Approval gate | **122** |
| Tailwind / Blade | 123+ |

## 6. Decision

The W01–W11 information hierarchy **supports real content** (including empty, sparse, and long-title cases). Recorded H-01–H-10 bind high-fidelity work. Do not redraw lo-fi.

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Information hierarchy reviewed against representative content; supports real content with H-01–H-10 for 118 (step 117 executed) | 2026-10-27 |

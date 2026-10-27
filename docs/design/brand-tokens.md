# Brand colour tokens

| | |
|--|--|
| **Step** | 115 — Extract official brand tokens |
| **Parent** | [brand-and-navigation.md](../charter/brand-and-navigation.md) (005) · [ui-ux-brief.md](ui-ux-brief.md) (114) |
| **Next** | 116 — Create low-fidelity wireframes |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.1 |
| **Date** | 2026-10-27 |
| **Status** | Logo hex preserved; supporting interface palette mapped to Tailwind at step 123 |

Source: colour wordmark `docs/legacy/archive/2026-10-20/media/Logo-Prooftag-CATIS-SA_Redesigne_Ok.png` (1122×324). The transparent `…-removebg.png` and favicon `Favicone.png` match the same two blues. Typefaces wait for step 120. Contrast of composed screens is reviewed at 121.

## 1. Sampled from the logo

Mode of opaque, non-white pixels (PHP GD). Nearby anti-alias hexes are discarded.

| Token | Hex | Role | Sample |
|-------|-----|------|--------|
| `royal` | `#0E6697` | Royal Blue. Wordmark (“Prooftag”, “CATIS”, “sa”). Primary brand colour. | 17759 px at exact hex on the colour PNG |
| `accent` | `#00AEFE` | Light royal / cyan. The **A** slash and the **i** tittle. Graphic mark only. | 1 447 px |
| `paper` | `#FFFFFF` | White. Primary pair with Royal Blue. | Logo background on light |

HSL of `royal`: **201.5° / 83% / 32%**. `accent` is the same family (**198.9° / 100% / 50%**).

## 2. Supporting interface shades (refined at step 120)

Not sampled from pixels. These shades use the logo hue while keeping the public interface brighter than the initial navy exploration. Forbidden as core brand: orange, gold, rainbow accents.

| Token | Hex | Use |
|-------|-----|-----|
| `azure-700` | `#007C9E` | Footer and focused surfaces with white text |
| `azure-600` | `#087EA4` | Secondary button and navigation emphasis |
| `azure-400` | `#00A6C7` | Decorative gradient and large dark text only |
| `azure-300` | `#48CAE4` | Decorative edge or dark text only |
| `cyan-ui` | `#28D7DF` | Primary action with dark text, focus accent |
| `cyan-soft` | `#D9FBFF` | Badges and soft emphasis |
| `azure-pale` | `#E8F8FB` | Section surfaces |
| `azure-mist` | `#F7FCFD` | Page canvas |
| `ink` | `#12222B` | Body text |
| `muted` | `#55646D` | Secondary text |
| `line` | `#D6DDE1` | Borders, rules |

## 3. Functional (status, not brand)

| Token | Hex | Use |
|-------|-----|-----|
| `success` | `#146C43` | Positive status |
| `warning` | `#B5473C` | Coral warning status; no yellow |
| `danger` | `#B42318` | Error status |

## 4. Contrast (WCAG 2.2, sampled)

Normal text needs **4.5:1**. Large text and UI components need **3:1**. Full design review is step 121.

| Foreground | Background | Ratio | AA normal |
|------------|------------|------:|-----------|
| `#FFFFFF` | `royal` `#0E6697` | 6.24 | Pass |
| `royal` `#0E6697` | `#FFFFFF` | 6.24 | Pass |
| `ink` `#12222B` | `#FFFFFF` | 16.29 | Pass |
| `ink` `#12222B` | `royal-pale` `#EDF4F8` | 16.85 | Pass |
| `muted` `#55646D` | `#FFFFFF` | 6.12 | Pass |
| `#FFFFFF` | `azure-700` `#007C9E` | 4.80 | Pass |
| `#FFFFFF` | `azure-600` `#087EA4` | 4.64 | Pass |
| `ink` `#12222B` | `azure-300` `#48CAE4` | 8.41 | Pass |
| `ink` `#12222B` | `cyan-ui` `#28D7DF` | 9.21 | Pass |
| `#FFFFFF` | `accent` `#00AEFE` | 2.47 | **Fail** |
| `accent` `#00AEFE` | `#FFFFFF` | 2.47 | **Fail** |
| `success` `#146C43` | `#FFFFFF` | 6.45 | Pass |
| `warning` `#B5473C` | `#FFFFFF` | 5.34 | Pass |
| `danger` `#B42318` | `#FFFFFF` | 6.57 | Pass |

**Do not** set body copy or small labels in the sampled logo `accent`, or as white-on-`accent`. Keep `#00AEFE` inside the official mark; use `cyan-ui` with dark text for interface actions.

## 5. Logo files

| Use | File |
|-----|------|
| Colour on light | `Logo_Prooftag_CATIS_SA_Redesigne_Ok-removebg.png` · `Logo-Prooftag-CATIS-SA_Redesigne_Ok.png` |
| White on `royal` / `royal-dark` | `Logo_Prooftag_CATIS_SA_Redesigne_Ok-white-e1719944621102.png` |
| Mono / black | `Logo_Prooftag_CATIS_SA_Redesigne_Ok-black.png` |
| Favicon (CATIS mark) | `Favicone.png` |

Do not use DITROS, CT-VERIF, or DOSER marks as the corporate logo.

## 6. Out of this step

| Item | Step |
|------|------|
| Lo-fi wireframes | 116 |
| Typefaces and component states | 120 |
| Accessibility review of composed screens | 121 |
| Tailwind / CSS variables in the app | [123](tailwind-tokens.md) |

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Official brand tokens extracted (step 115 executed) | 2026-10-22 |

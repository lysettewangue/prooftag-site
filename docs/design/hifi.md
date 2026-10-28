# High-fidelity designs

| | |
|--|--|
| **Step** | 118 — Create high-fidelity designs |
| **Parent** | [hierarchy-review.md](hierarchy-review.md) (117) · [wireframes.md](wireframes.md) (116) · [brand-tokens.md](brand-tokens.md) (115) |
| **Next** | 119 — Create responsive designs |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-27 |
| **Status** | Desktop visual designs. Not Blade. Not Tailwind in the app |

Open [hifi/index.html](hifi/index.html) in a browser. Colour and type applied. Tablet / mobile wait for **119**. Component state catalogue waits for **120**. Accessibility pass on composed screens waits for **121**. Filament `/admin` is out of scope.

## 1. Visual direction

| Token | Value | Role in hi-fi |
|-------|-------|----------------|
| `royal` | `#0E6697` | Primary actions, header accent, key rules |
| `accent` | `#00AEFE` | Decorative rules / focus ring only — **not** body text or white-on-accent buttons |
| `royal-deep` | `#04263A` | Footer band |
| `royal-pale` / `mist` | `#EDF4F8` / `#F5F8FA` | Section canvas |
| `ink` / `muted` | `#12222B` / `#55646D` | Body / secondary |
| UI type | Plus Jakarta Sans | Chrome, nav, UI |
| Reading type | Source Serif 4 | Legal, long article / product body (H-05) |

Personality: institutional, calm, Cameroon transport / road-safety. No orange, gold, purple gradients, or live counters.

## 2. Artboards (desktop ~1280)

| ID | Template | Hi-fi notes |
|----|----------|-------------|
| HF01 | Global shell | Two-line nav (H-01); logo colour on light; skip link; footer on `royal-deep` |
| HF02 | Homepage | Full-bleed hero; brand + one headline + one line + CTA group; published product strip; expertise title-only cards (H-03, H-07) |
| HF02b | Homepage empty products | No ghost cards; CT / Contact CTAs (H-04) |
| HF03 | Product detail | Crumb (H-08); brand H1; summary; body measure; secondary related band (H-06) |
| HF04 | Contrôle technique + 12 points | Multi-line accordion titles with numbers (H-02); equipment secondary |
| HF05 | Centre directory | List primary; map muted enhancement (H-09) |
| HF05b | Centre detail | Missing phone / email / coords as quiet text |
| HF05c | Directory no matches | Empty state, not 404 |
| HF06 | Resource library | Status as text label |
| HF07 | News listing | Published-looking card row |
| HF07b | Article · EN missing | Switch → `/en` (H-10); serif body measure |
| HF07c | News empty | Valid empty |
| HF08 | Contact | Address + form; visible labels |
| HF08b | Contact error / success | Error beside field; success message |
| HF09 | Legal | Serif, ~42rem column |
| HF10 | System | 404 · 500 · maintenance |
| HF11 | Careers empty | Crumb + empty (team / partners same later) |

Same-family templates (About, expertise detail, equipment, search, product index) reuse HF01 shell + page / card patterns — not a second visual system.

## 3. Hierarchy bindings (117)

H-01–H-10 are applied on these artboards. VER-01–13 stay Open: no exclusive-operator hero, no social icons, no unverified stats / team / partners blocks.

## 4. Out of this step

| Item | Step |
|------|------|
| Tablet / mobile frames | 119 |
| Full design-system tokens & states | 120 |
| Accessibility review | 121 |
| Stakeholder approval | **122** |
| Tailwind / Blade | 123+ |

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | High-fidelity desktop visual designs created for the key templates (step 118 executed) | 2026-10-27 |

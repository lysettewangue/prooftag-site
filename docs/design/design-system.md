# Public design system

| | |
|--|--|
| **Step** | 120 — Define the design system |
| **Parent** | [responsive-designs.md](responsive-designs.md) (119) · [brand-tokens.md](brand-tokens.md) (115) |
| **Next** | [121 — Review design accessibility](accessibility-review.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-27 |
| **Status** | Public foundations, components, and states defined; mapped to Tailwind at step 123 |

Open [design-system/index.html](design-system/index.html) for the visual catalogue. This system governs the **public site**. Filament keeps its own component system while sharing the approved brand direction.

## 1. Principles

1. **Clarity first:** complete labels, explicit hierarchy, visible form labels, one dominant action per group.
2. **Institutional, not heavy:** the official Royal Blue anchors identity; Azure/Cyan and white carry the interface without a navy-dominant appearance.
3. **Evidence over decoration:** no invented statistics, partners, capabilities, centre data, or testimonials.
4. **Accessible by construction:** 44 px targets, visible focus, text-backed states, reduced motion, and semantic native controls.
5. **Bilingual resilience:** components accept longer French or English text without ellipsis or fixed-height clipping.

## 2. Colour tokens

### Brand and interface

| Token | Hex | Approved use |
|-------|-----|--------------|
| `brand.royal` | `#0E6697` | Official logo-derived anchor, links, focus |
| `brand.azure.700` | `#007C9E` | Focus surface/footer; white text passes 4.80:1 |
| `brand.azure.600` | `#087EA4` | Secondary action/navigation; white text passes 4.64:1 |
| `brand.azure.400` | `#00A6C7` | Decorative gradient or large dark text only |
| `brand.azure.300` | `#48CAE4` | Decorative gradient edge or dark text only |
| `brand.cyan.500` | `#28D7DF` | Primary action with `ink`; contrast 9.21:1 |
| `brand.cyan.100` | `#D9FBFF` | Badge, icon tile, soft emphasis |
| `surface.100` | `#E8F8FB` | Alternating section |
| `surface.50` | `#F7FCFD` | Page canvas |
| `neutral.white` | `#FFFFFF` | Primary content surface |
| `text.strong` | `#12222B` | Body and headings |
| `text.muted` | `#55646D` | Secondary text; 6.12:1 on white |
| `border.default` | `#D6DDE1` | Dividers and component borders |

The sampled logo cyan `#00AEFE` remains part of the official image asset. Interface components use `#28D7DF` with dark text. Neither cyan is used behind white small text.

### Functional colours

| Token | Hex | Background | Rule |
|-------|-----|------------|------|
| `status.info` | `#007C9E` | `#D9FBFF` | General notice |
| `status.success` | `#146C43` | `#E7F6EE` | Confirmed positive result |
| `status.warning` | `#B5473C` | `#FFF0EE` | Coral warning; replaces the former yellow |
| `status.danger` | `#B42318` | `#FFF1F0` | Error or destructive action |
| `status.disabled` | `#89959B` | `#EEF2F4` | Native disabled control |

Every status includes text and, where useful, an icon. Colour alone never carries meaning.

## 3. Typography

| Role | Family | Weights | Use |
|------|--------|---------|-----|
| Interface | DM Sans | 400, 500, 600, 700 | Navigation, headings, body, controls |
| Long reading | Source Serif 4 | 400, 600 | Legal and regulatory prose only |
| Fallback | Arial / Georgia | — | Stable fallback by family |

| Token | Desktop | Compact | Line height | Weight |
|-------|---------|---------|------------:|-------:|
| `display-xl` | 64 px | 46 px | 1.02 | 700 |
| `heading-1` | 48 px | 38 px | 1.08 | 700 |
| `heading-2` | 38 px | 31 px | 1.15 | 700 |
| `heading-3` | 24 px | 22 px | 1.25 | 700 |
| `body-lg` | 18 px | 17 px | 1.70 | 400 |
| `body` | 16 px | 16 px | 1.60 | 400 |
| `body-sm` | 14 px | 14 px | 1.55 | 400/500 |
| `label` | 12 px | 12 px | 1.40 | 700 |
| `reading` | 18 px | 17 px | 1.85 | 400 |

Production must self-host or otherwise approve font delivery before step 123. Text remains usable with fallbacks and without font loading.

## 4. Spacing, sizing, shape

- Base unit: **4 px**.
- Spacing scale: `4, 8, 12, 16, 20, 24, 32, 40, 48, 64, 80, 96`.
- Compact page gutter: **16 px**; medium: **24 px**; wide container: maximum **1280 px**.
- Minimum interactive target: **44 × 44 px**; primary control height: **48 px**.
- Control radius: **10 px**; panel: **16 px**; card: **22 px**; hero: **28 px**; pill: **999 px**.
- Shadows: three Azure-tinted levels. Do not use shadow as the only component boundary.

## 5. Components

| Component | Variants | Required behavior |
|-----------|----------|-------------------|
| Button | Primary cyan, secondary Azure, outline, danger, disabled | Explicit label; one primary per group; loading prevents repeat submission |
| Link | Inline, navigation, standalone arrow, external | Underline in prose; external destination announced |
| Text input | Default, hover, focus, error, success, disabled | Visible label, optional help, associated error text |
| Select/textarea | Same state contract as text input | Native semantics retained |
| Badge | Info, success, warning coral, danger, neutral | Text label mandatory |
| Alert | Info, success, warning coral, danger | Icon + heading + message; live-region behavior selected by urgency |
| Card | Product, expertise, resource, article, directory result | Full title; no competing nested actions |
| Navigation | Wide eight-item bar, compact disclosure | No Home item; logo returns to locale home |
| Breadcrumb | Text trail | Current page not linked; unpublished ancestors absent |
| Accordion | Numbered CT item, FAQ item | Complete trigger label; native button/details behavior |
| Pagination | Previous, numbered current, next | Current page announced; 44 px targets |
| Empty state | Collection empty, no filter match, unavailable translation | Explanation + nearest useful recovery action |
| Skeleton/loading | Text/card geometry only | Motion reduced; never resembles real content indefinitely |

## 6. Interactive states

All interactive components define `default`, `hover`, `focus-visible`, `active`, `disabled`, and when applicable `loading`, `success`, and `error`.

- Focus: two-colour ring with a 3 px white outline and a Royal outer ring, visible on light, cyan, and Azure surfaces. Never suppress without an equivalent.
- Hover: border or surface change; no layout movement.
- Active: at most 1 px translation.
- Disabled: native `disabled` or `aria-disabled`, neutral surface, no opacity-only treatment.
- Error: red/coral border, icon or marker, and an associated text explanation.
- Loading: retain the action label, add progress wording or icon, and block duplicate submission.

## 7. Icons and imagery

- Public icon family: **Lucide** only; 20 or 24 px, stroke 1.75 by default.
- Icon-only buttons require an accessible name and tooltip where helpful.
- Status, destructive, download, external-link, and language actions keep a text label in primary contexts.
- Hero/network geometry can be CSS/SVG-native and abstract.
- Real photos require rights metadata and bilingual alt text from the CMS.
- Product logos never replace the corporate mark in the global shell.

## 8. Motion

| Token | Duration | Use |
|-------|---------:|-----|
| `motion.fast` | 120 ms | Hover and focus feedback |
| `motion.base` | 180 ms | Disclosure and menu |
| `motion.slow` | 240 ms maximum | Panel transition |

Default easing is `ease-out`; deliberate panel movement may use `cubic-bezier(.2,.8,.2,1)`. Under `prefers-reduced-motion: reduce`, smooth scrolling and decorative movement become effectively instant.

## 9. Responsive contract

The components inherit the behavior frozen in [responsive-designs.md](responsive-designs.md): wide `≥1025`, medium `641–1024`, compact `≤640`. Breakpoints may become named Tailwind tokens at step 123, but DOM order and content guarantees cannot change.

## 10. Governance

- Step 121 audits the composed designs against WCAG 2.2 AA and may refine a token or state.
- Step 122 is the approval gate.
- Step 123 maps the approved tokens into [Tailwind/CSS](tailwind-tokens.md); it does not redesign them.
- Editors cannot change palette, typefaces, spacing, navigation, or component variants in the CMS.
- Any new component must document anatomy, variants, states, responsive behavior, accessibility name, and content limits.

## 11. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Public type, colour, spacing, component, and state system defined (step 120 executed) | 2026-10-27 |


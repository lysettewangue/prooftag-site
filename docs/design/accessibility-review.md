# Design accessibility review

| | |
|--|--|
| **Step** | 121 — Review design accessibility |
| **Parent** | [design-system.md](design-system.md) (120) |
| **Next** | [122 — Approve UI/UX](ui-ux-approval.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Standard** | WCAG 2.2, target level AA |
| **Date** | 2026-10-27 |
| **Status** | Design review complete; ready for the step 122 approval gate |

## 1. Scope and method

The review covers the composed desktop designs, responsive prototype, and public design system from steps 118–120. It checks colour contrast, keyboard focus, landmark and heading intent, form labels and errors, target sizes, responsive reading order, and reduced motion.

This is a **design-artifact audit**, not the final audit of the Laravel application. Browser zoom, keyboard traversal, screen-reader output, Livewire announcements, dynamic menus, CMS alternative text, and automated axe/Lighthouse scans must be tested again after implementation at step 181.

## 2. Corrections applied

| Finding | Correction | WCAG 2.2 reference |
|---------|------------|--------------------|
| Azure text on the primary cyan button was too weak for normal text | Primary cyan actions now use `#12222B`, giving **9.01:1** | 1.4.3 Contrast (Minimum) |
| Small cyan labels and arrows on white were too weak | Card numbers, result arrows, and state codes now use Royal `#0E6697`, giving **6.24:1** on white | 1.4.3 |
| Focus indication could disappear against blue or cyan surfaces | A two-colour focus treatment now combines a white inner outline and Royal outer ring | 2.4.7 Focus Visible; 2.4.11 Focus Not Obscured |
| The skip link stayed off-screen when focused | The link is now positioned visibly at the upper-left on focus | 2.4.1 Bypass Blocks |
| Form focus used a translucent ring with insufficient certainty | Inputs, selects, and textareas now use the same high-contrast focus treatment | 1.4.11 Non-text Contrast; 2.4.7 |
| Light control borders did not reach 3:1 against white | Essential input and outline-button borders now use colours above 4:1 against white | 1.4.11 Non-text Contrast |
| The design-system side navigation had targets shorter than 44 px | Each link now has a minimum height of 44 px | 2.5.8 Target Size (Minimum) |
| The compact header hid search without an equivalent menu entry | Search is now present in the mobile navigation | 2.4.5 Multiple Ways; consistent access |
| Contact requirements were communicated only by an asterisk | Prototype controls now also use the native `required` state | 3.3.2 Labels or Instructions |
| Error help in the component catalogue was not programmatically associated | The invalid field now references its error through `aria-describedby` | 3.3.1 Error Identification; 4.1.2 Name, Role, Value |
| Reduced-motion handling existed only in later responsive CSS | The high-fidelity base now also disables smooth scrolling and decorative transitions on request | 2.3.3 Animation from Interactions |
| The abstract network had a label without an image role | The labelled visual now uses `role="img"` | 1.1.1 Non-text Content; 4.1.2 |

## 3. Contrast evidence

| Foreground / background | Ratio | Decision |
|-------------------------|------:|----------|
| Ink `#12222B` / Cyan `#28D7DF` | 9.21:1 | Pass AA and AAA for normal text |
| White / Azure 700 `#007C9E` | 4.80:1 | Pass AA for normal text |
| White / Azure 600 `#087EA4` | 4.64:1 | Pass AA for normal text |
| Ink / Sky `#48CAE4` | 8.41:1 | Pass AA and AAA for normal text |
| Muted `#55646D` / White | 6.12:1 | Pass AA for normal text |
| White / Royal `#0E6697` | 6.24:1 | Pass AA for normal text |
| White / Warning coral `#B5473C` | 5.34:1 | Pass AA for normal text |
| White / Success `#146C43` | 6.45:1 | Pass AA for normal text |
| White / Danger `#B42318` | 6.57:1 | Pass AA for normal text |

The official logo may retain its sampled cyan because it is an image mark. That cyan is not approved as a background for small white interface text. No yellow remains in the public palette.

## 4. Criterion review

| Area | Design decision | Result |
|------|-----------------|--------|
| Text alternatives | Logo has an alternative; decorative geometry is CSS-only; meaningful abstract visual is labelled | Pass at design level |
| Structure | Native headings, navigation, forms, labels, lists, `details`, and buttons are preferred | Pass; verify generated templates |
| Reading order | Directory results precede the optional map in DOM and on compact layouts | Pass |
| Colour use | Statuses combine colour with text, shape, or icon; no meaning depends on colour alone | Pass |
| Reflow | Layout contract covers 320 px and rearranges without truncating official titles | Pass at design level; test 400% zoom later |
| Keyboard | Native controls and disclosures are specified; no hover-only action | Pass at design level; test full traversal later |
| Focus | Persistent, high-contrast `:focus-visible` ring; skip link becomes visible | Pass |
| Target size | Main actions, navigation, pagination, disclosure, language controls, and inputs meet at least 44 px | Pass |
| Page language | French prototypes declare `lang="fr"`; English templates must declare `lang="en"` | Pass as a requirement |
| Labels and errors | Visible labels, native required state, `aria-invalid`, associated error text, and alert summary | Pass |
| Motion | No autoplay; reduced-motion mode removes smooth scrolling and transitions | Pass |

The high-fidelity gallery intentionally contains several separate page artboards in one review document, so it contains several `h1` elements. In production, each route must render one page-level `h1` and one main landmark.

## 5. Implementation conditions

Step 122 may approve the UI/UX direction, subject to preserving these conditions in frontend code:

- keep the semantic DOM order when visual grids change;
- return the logo to the current locale homepage and name every compact control;
- preserve visible labels and associate server-side validation errors with their fields;
- announce asynchronous success and failure messages with the correct live-region urgency;
- require useful bilingual alternative text for CMS images and treat decorative images as empty alternatives;
- keep map use optional and expose centre information in an accessible list first;
- test keyboard-only use, 200% and 400% zoom, forced-colour/high-contrast mode, screen readers, and reduced motion before release;
- run automated checks only as support for, not a replacement for, manual accessibility QA.

## 6. Decision

The design artifacts meet the intended WCAG 2.2 AA direction after the corrections above. **Step 121 is complete. The product-owner decision is recorded in the step 122 approval document.**

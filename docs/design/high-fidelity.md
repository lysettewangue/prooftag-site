# High-fidelity desktop designs

| | |
|--|--|
| **Step** | 118 — Create high-fidelity designs |
| **Parent** | [information-hierarchy-review.md](information-hierarchy-review.md) (117) · [brand-tokens.md](brand-tokens.md) (115) |
| **Next** | [119 — Create responsive designs](responsive-designs.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.1 |
| **Date** | 2026-10-27 |
| **Status** | Desktop high-fidelity design set complete; not public Blade |

> The production shell and homepage were subsequently softened after stakeholder review. See [visual-refresh.md](visual-refresh.md) for the current colour balance and layout direction.

Open [high-fidelity/index.html](high-fidelity/index.html) in a browser. These are fixed desktop design artboards at 1440 px. They are a visual source for steps 119–123, not application templates.

## 1. Visual direction

**Institutional mobility network** combines the official Royal Blue with an electric Azure/Cyan range, generous white space, precise information layers, softly elevated surfaces, and abstract road/network geometry. The palette was intensified after visual review so the navigation, hero, cards, and footer feel energetic and immediately recognizable without becoming navy.

- Royal Blue `#0E6697` remains the official logo-derived anchor.
- Azure `#0369A1` to `#0277B5` carries text-bearing navigation, hero, and footer surfaces with readable white text.
- Sky `#0284C7` and `#0EA5E9` energize gradients and decorative edges with dark text where needed.
- Electric Cyan `#22D3EE` identifies primary action, focus, connection, and technology.
- Pale cyan cards replace dense dark-blue card blocks to make the page brighter and more memorable.
- White, mist, and pale blue keep dense information readable.
- No yellow, gold, orange brand accent, rainbow palette, unverified photography, invented statistic, testimonial, partner logo, or social account.
- Warning/error examples use coral/red as functional colour and always include text.

The network artwork is CSS-native and deliberately abstract. It does not claim to show a real centre, vehicle, system screen, map coordinate, or government integration.

## 2. Artboards

| ID | Template coverage | Key proof |
|----|-------------------|-----------|
| HF01 | Global shell + homepage | Eight-section navigation, verified hero, three actions, published-only solution grid, five expertises, frozen footer |
| HF02 | Product detail | Official name, summary, body priority, abstract media, related expertise/resources |
| HF03 | Technical-inspection hub | Twelve complete accordion titles with one panel open; no DITROS-CT framing |
| HF04 | Centre directory | Search/region controls, three Active fixtures, list first, optional map, missing contact details |
| HF05 | Resource library | Explicit historical/current text, absent-PDF state, optional download, pagination |
| HF06 | News listing/article language | Published listing hierarchy, complete titles, category/kind/date metadata, pagination |
| HF07 | Contact | Full frozen identity, visible labels, error summary, inline field error |
| HF08 | Legal/privacy family | Narrow regulatory reading measure, optional contents, ordered sections |
| HF09 | 404/500/maintenance + careers empty | Plain recovery actions and valid empty collection treatment |

About, organisation, history, expertise detail, equipment, partners, team, search, product index, and centre detail reuse these approved page-hero, prose, card, directory, and empty-state patterns. They do not require a separate visual language.

## 3. Content and verification safeguards

- The homepage does not use the unresolved exclusive-operator claim (VER-01).
- Product capability copy stays within the frozen baseline; unresolved product pages remain unpublished.
- The directory shows the three Active representative centres only. SATELLITE NGONO remains absent.
- The map is explicitly illustrative because the representative centres have no verified coordinates.
- The resource stress case is historical and has no active download without a file.
- Team, partners, social links, production URLs, and unverified organisation facts remain absent.
- Product and article examples are labelled by the surrounding design-viewer context; seed fixtures are not launch approval.

## 4. Typography and layout boundary

DM Sans and Source Serif 4 are used as **provisional design fonts**, following the earlier recommendation. Their final selection, complete scale, weights, loading strategy, and component tokens belong to step 120.

The artboards intentionally keep a fixed 1440 px canvas and a 1200 px content container. Tablet/mobile behavior, collapsed navigation, reflow, touch layout, and breakpoint decisions belong to step 119. The CSS in this design package must not be copied into production as the final responsive system.

## 5. Interaction boundary

- Accordions use native `<details>` to demonstrate hierarchy.
- Links, search, filters, downloads, pagination, and form submission are visual states only.
- No database request, public route, map provider, analytics, or external product URL is connected.
- Focus, keyboard sequence, final contrast across states, and reduced-motion behavior receive the composed-screen audit at step 121.

## 6. Handoff to responsive design

Step 119 must preserve:

1. Full labels without ellipsis for navigation, products, expertises, and the twelve points.
2. List-before-map order for the centre directory.
3. Contact details before or alongside the form, never hidden behind interaction.
4. Explicit resource status text and absent-file state.
5. One `<h1>` and the heading order frozen at step 117.
6. Visible locale state and safe locale-home fallback when a translation is absent.
7. Cyan/Azure as the only bright brand accent; no yellow.

## 7. Verification

- All nine artboards and their anchors are present in `high-fidelity/index.html`.
- Official local colour and white logos are used from `public/images/brand/`.
- The complete twelve-point list is rendered.
- The French eight-section navigation is rendered without an `Accueil` item.
- Desktop-only boundary is explicit; no step-119 breakpoint is preselected.

## 8. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | High-fidelity desktop direction and key template set created (step 118 executed) | 2026-10-26 |


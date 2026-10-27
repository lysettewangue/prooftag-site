# Responsive designs

| | |
|--|--|
| **Step** | 119 — Create responsive designs |
| **Parent** | [high-fidelity.md](high-fidelity.md) (118) · [information-hierarchy-review.md](information-hierarchy-review.md) (117) |
| **Next** | [120 — Define the design system](design-system.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-27 |
| **Status** | Desktop, tablet, and mobile responsive behavior defined; not public Blade |

Open [responsive/index.html](responsive/index.html) to compare the same design prototype at **1440 px**, **834 px**, and **390 px**. Open [responsive/prototype.html](responsive/prototype.html) directly and resize the browser for a continuous test.

The prototype demonstrates the shell, hero, solution and expertise grids, centre directory, resource row, contact form, and complete footer. It uses the brighter Azure/Cyan direction approved after step 118 and contains no yellow.

## 1. Responsive ranges

The reference widths are test points, not device detection. The layout responds continuously between them.

| Range | Reference | Navigation | Content behavior |
|-------|-----------|------------|------------------|
| Wide | `≥ 1025 px` | All eight primary items visible | 1200 px maximum content measure; hero, directory, and contact use paired columns |
| Medium | `641–1024 px` | Condensed native disclosure menu | 24 px page gutters; two-column cards where readable; directory, contact, legal, and detail splits stack |
| Compact | `≤ 640 px` | Condensed single-column disclosure menu | 16 px gutters; one-column content; primary actions expand to available width |

The implementation step may translate these values to Tailwind tokens after step 120. It must preserve the behavior rather than blindly copy this design CSS.

## 2. Global shell

### Desktop

- Contact utility strip, logo row, search, FR/EN, then the eight-item navigation.
- Logo remains the locale home link; there is no `Accueil` / `Home` menu item.
- Footer uses three columns: identity, exploration, legal information.

### Tablet

- Utility strip is removed from the visual header; the same contact details remain in the footer and contact page.
- Search and locale controls remain in the logo row.
- The eight-item navigation moves into a labelled `Menu principal` disclosure with two columns when open.
- Footer becomes two columns, with corporate identity occupying the first full row.

### Mobile

- Logo, FR/EN, and menu control remain visible without horizontal scrolling.
- Search leaves the header and is reached through the opened menu/search route in implementation.
- The open navigation list is one column with 48 px rows.
- Footer becomes one column and retains every frozen contact line and legal link.

## 3. Component reflow

| Component | Desktop | Tablet | Mobile |
|-----------|---------|--------|--------|
| Hero | Text + network illustration | Reduced two-column composition | Text first, illustration below; actions full width |
| Product cards | Three columns | Two columns; last item spans | One column; official names never truncate |
| Expertise cards | Five columns | Two columns; last item spans | One column |
| CT accordion | Full title row | Full title row | Number, complete wrapping title, disclosure icon; body indented |
| Directory filters | Search + region + action row | Same row while space permits | Three stacked controls |
| Directory results/map | Side by side | List then map | List then map, never reversed |
| Resource/article row | Metadata + content + action | Same or stacked by available measure | Metadata, content, then action |
| Contact | Identity beside form | Identity above form | Identity above one-column form |
| Legal | Sticky contents beside prose | Contents above prose | Contents above prose; narrow reading measure |
| Empty/system states | Three-up where applicable | Two/one based on measure | One column with one primary recovery action |

## 4. Content guarantees

- No essential title, navigation label, product name, expertise name, CT point, or status is ellipsized.
- The centre list remains earlier than the map in both DOM and visual order.
- Missing phone, email, coordinates, translation, file, or published item never produces an empty placeholder.
- Contact information remains available before form interaction.
- FR/EN stays visible at every width; a missing entity translation still falls back to the locale homepage.
- Status meaning always has text. The Cyan/Azure palette is not the sole carrier of meaning.
- All interactive targets are designed for at least 44 × 44 px; the composed accessibility audit remains step 121.

## 5. Overflow and long-content policy

- Page gutters never fall below 16 px.
- Images and abstract artwork may shrink, crop decoratively, or move below text; meaningful text cannot be cropped.
- Official product names and the eight navigation labels wrap rather than abbreviate.
- Long French and English words may break only as a last resort (`overflow-wrap` in implementation); normal wrapping is preferred.
- Tables in future legal/resources content receive a horizontally scrollable wrapper with a visible cue, not a shrunken unreadable font.
- CMS rich text, embeds, and media must declare intrinsic dimensions to avoid layout shift.

## 6. Interaction notes

- The prototype uses native `<details>` for the compact navigation, so the information remains available without custom JavaScript.
- The final menu must restore focus to its trigger after closing and trap focus only if implemented as a modal drawer.
- Opening the navigation must not change the locale or lose the current page.
- Accordions, filters, pagination, and form feedback retain the reading order frozen at step 117.
- `prefers-reduced-motion: reduce` removes smooth scrolling and reduces transitions in the prototype.

## 7. Verification matrix

| Check | 1440 | 834 | 390 |
|-------|:----:|:---:|:---:|
| Logo and locale controls visible | ✓ | ✓ | ✓ |
| Navigation reachable | Full | Condensed | Condensed |
| Hero title complete | ✓ | ✓ | ✓ |
| Long product/expertise names complete | ✓ | ✓ | ✓ |
| Centre list before map | ✓ | ✓ | ✓ |
| Resource status and absent-file state visible | ✓ | ✓ | ✓ |
| Corporate details before contact form | Beside | Above | Above |
| Complete footer | 3 columns | 2 columns | 1 column |
| Horizontal page overflow intended | No | No | No |

## 8. Scope boundary

The files under `docs/design/responsive/` are design artifacts. They do not create Laravel routes, query CMS data, submit forms, open production maps, or implement the public site. Final typography, tokens, focus states, component specifications, and accessibility decisions continue in steps 120–121; frontend implementation remains locked until gate 122.

## 9. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Responsive desktop, tablet, and mobile behavior created for the key patterns (step 119 executed) | 2026-10-27 |


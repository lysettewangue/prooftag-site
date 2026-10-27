# UI/UX approval

| | |
|--|--|
| **Step** | 122 — Approve UI/UX |
| **Parent** | [accessibility-review.md](accessibility-review.md) (121) |
| **Next** | [123 — Configure Tailwind tokens](tailwind-tokens.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Date** | 2026-10-27 |
| **Status** | Approved; public frontend implementation unlocked |

## 1. Approval scope

The product owner approves the public UI/UX direction represented by the following controlled deliverables:

- [UI/UX brief](ui-ux-brief.md) — audiences, templates, content constraints, and accessibility target;
- [brand tokens](brand-tokens.md) — official Royal Blue anchor and the approved Azure/Cyan interface palette;
- [low-fidelity wireframes](wireframes.md) — key page structures;
- [information hierarchy review](information-hierarchy-review.md) — content order and real-data resilience;
- [high-fidelity designs](high-fidelity.md) — desktop visual direction and template families;
- [responsive designs](responsive-designs.md) — wide, medium, and compact behavior;
- [public design system](design-system.md) — typography, colour, spacing, components, and states;
- [accessibility review](accessibility-review.md) — WCAG 2.2 AA design corrections and implementation conditions.

This approval authorizes implementation beginning at step 123. It does not approve unverified launch content, production deployment, or the final accessibility of code that has not yet been built.

## 2. Frozen decisions

| Area | Approved decision |
|------|-------------------|
| Brand | Official Royal `#0E6697` remains the identity anchor |
| Interface palette | Azure 700/600 for readable strong surfaces; electric cyan for emphasis and primary action with dark ink |
| Excluded colour | No yellow, gold, or navy-dominant public interface |
| Typography | DM Sans for interface; Source Serif 4 for long legal/regulatory reading, with safe fallbacks |
| Navigation | Eight frozen sections; no Home item; logo returns to the current locale homepage |
| Responsive behavior | Wide `≥1025`, medium `641–1024`, compact `≤640`; content must reflow continuously |
| Content | Only published and verified CMS content appears publicly; missing content produces explicit safe states |
| Directory | Accessible list before optional map; Active centres only |
| Components | Approved buttons, cards, forms, alerts, badges, accordion, pagination, navigation, and empty states |
| Accessibility | WCAG 2.2 AA target, visible two-colour focus, 44 px targets, labelled controls, reduced motion |
| Footer | Complete corporate identity, contact, exploration, and legal information at every width |

## 3. Implementation contract

Steps 123–150 must translate the approved system into production components without silently redesigning it. Implementation may refine technical details where necessary, but must preserve:

1. the approved colour roles and measured contrast;
2. semantic reading order and one page-level `h1` per route;
3. complete French and English labels without truncation;
4. visible labels, error associations, keyboard focus, and minimum target sizes;
5. list-first centre discovery and optional map enhancement;
6. the publication, verification, and missing-translation rules of the frozen data model;
7. all responsive content guarantees and the complete footer.

A material change to navigation, palette, template hierarchy, component behavior, or accessibility rules requires a documented design decision before implementation continues.

## 4. Deferred verification

The following remain mandatory after implementation and are not waived by this gate:

- test templates with real CMS data and all five product records;
- verify keyboard navigation, menu focus restoration, validation announcements, and language switching;
- test 320 px reflow, 200% and 400% zoom, reduced motion, and forced-colour/high-contrast mode;
- perform screen-reader and automated accessibility checks at step 181;
- run the public frontend review gate at step 150 before content migration and release work.

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Approved the UI/UX direction and authorized step 123 frontend token implementation | 2026-10-27 |

**Gate 122 is passed. Steps 001–122 are complete. Step 123 is unlocked.**

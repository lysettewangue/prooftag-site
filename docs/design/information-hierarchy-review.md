# Information hierarchy review

| | |
|--|--|
| **Step** | 117 — Review information hierarchy |
| **Parent** | [wireframes.md](wireframes.md) (116) · [ui-ux-brief.md](ui-ux-brief.md) (114) |
| **Next** | [118 — Create high-fidelity designs](high-fidelity.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-26 |
| **Status** | Reviewed against frozen and representative CMS content |

This review checks whether the low-fidelity layouts can carry the real V1 content without hiding, inventing, or visually flattening information. It freezes hierarchy decisions for high-fidelity work; it does not choose typography, responsive breakpoints, or implement public Blade templates.

## 1. Sources reviewed

- Frozen navigation, naming, homepage policy, and contact display lines.
- Five product names and their representative bilingual summaries and bodies.
- Five expertise titles, including the longest labels.
- The twelve technical-inspection point titles in French and English.
- Representative centres, resources, categories, and editorial drafts.
- Empty, missing-translation, validation, and system states from W01–W11.
- VER-01–13 publication restrictions. Representative seed data remains structural and is not approved launch copy.

## 2. Global hierarchy

The shell and page content have separate responsibilities:

1. Skip link.
2. Header: logo/home, primary navigation, search, current locale and language switch.
3. Breadcrumbs, except on the homepage.
4. Exactly one page `<h1>`.
5. Introductory summary or lead action.
6. Page-specific primary content.
7. Related or secondary content.
8. Footer: frozen identity, legal links, and verified social links only.

Decisions:

- The logo is the home link; `Accueil` / `Home` is not added to navigation or breadcrumbs.
- The eight-section navigation remains the primary information architecture. Audience names never become top-level entries.
- Search and locale controls are utilities, not peers of the eight sections.
- Breadcrumbs express the frozen section tree and do not include unpublished ancestors.
- Page headings use structural levels, not visual size. Cards and related panels start at `<h2>` below the page `<h1>`.
- A missing translation sends the alternate locale control to that locale's homepage and explains the absence; it never creates a synthetic entity URL.

## 3. Real-content fit

| Content | Representative stress case | Hierarchy decision |
|---------|----------------------------|--------------------|
| Primary navigation | `Actualités & Articles`, `Centres de contrôle` | Labels may wrap in design work; never abbreviate or reduce the eight sections. |
| Homepage hero | “Technologie pour le transport et la sécurité routière au Cameroun” | One concise `<h1>`, followed by a short verified lead and at most three primary actions. No counters or VER-01 claim. |
| Product name | `CERTIDOCS CT Reporting Platform` | Preserve the official name and allow two lines. Never truncate a product heading or translate a product name. |
| Product summary | CT-VERIF and DOSER drafts include verification and outbound-link caveats | Summary remains directly under `<h1>`; publication rules remove unresolved internal caveats from public copy rather than hiding them through truncation. |
| Expertise title | `Sécurité et prévention routière` / `Road safety and prevention` | Cards reserve two title lines and remain equal peers; titles are not replaced by product names. |
| CT point | Longest titles are roughly a full sentence in both locales | Number and title form the accordion heading. Titles wrap freely; no ellipsis, all-caps, or equal-height constraint. Body follows only inside the expanded panel. |
| Centre result | Operator, city, region; phone, email, and coordinates may all be absent | Name is primary, place secondary, verified contact fields tertiary. Absence does not create empty labels or placeholder pins. |
| Resource | Long title plus `current`, `historical`, or `archive` | Title precedes metadata; regulatory status is explicit text near the title. Download is shown only when a public file exists. |
| Article | Independent FR/EN titles, summaries, slugs, and bodies | Listing order is title, category/kind/date metadata, then summary. Detail keeps metadata before the body and related content after it. |
| Contact identity | Five frozen display lines, including the long building line | Identity stays a grouped address block before or alongside the form. It must not be compressed into the footer-only treatment. |
| Legal copy | Long-form regulatory content | A narrow reading column with section `<h2>` headings and optional in-page contents for sufficiently long documents. |

No essential label receives a character limit. Listing summaries may use a visual line clamp only if the complete title remains visible and the destination exposes the full summary; server-rendered text must remain available without JavaScript.

## 4. Template hierarchy decisions

| Wireframe | Required reading order | Review result |
|-----------|------------------------|---------------|
| W01 Shell | Utilities → primary navigation → page slot → corporate footer | Pass. The page slot owns its `<h1>`; the shell itself does not add one. |
| W02 Home | Hero `<h1>` → actions → published solutions → expertises | Pass after changing the two section headings from `<h3>` to `<h2>`. Product absence uses W02b and does not leave an empty grid. |
| W03 Product | Breadcrumb → product `<h1>` → summary → media/body → optional outbound action → related expertises/resources | Pass after promoting related-panel headings to `<h2>`. Long official names wrap without truncation. |
| W04 CT | `<h1>` and intro → twelve numbered points → equipment | Pass after giving equipment an `<h2>`. Accordion headings keep the complete real titles. |
| W05 Directory | `<h1>` → filters → result count/list → pagination; map secondary | Pass. The list remains primary and usable when coordinates or JavaScript are absent. |
| W05b Centre | Breadcrumb → centre `<h1>` → location → available contact details → optional map | Pass. Missing values are omitted or explained once, not repeated as disabled controls. |
| W05c No matches | `<h1>` → retained filters → no-result explanation → reset action | Pass with a required reset-search action in high fidelity. It is not a 404. |
| W06 Resources | `<h1>` → filters/status explanation → document list → pagination | Pass. Status text belongs with each result and precedes the optional download action. |
| W07 News | `<h1>` → filters/categories when available → published entries → pagination | Pass. Draft fixtures never appear publicly. |
| W07b Article | Breadcrumb → article `<h1>` → metadata/summary → body → related content | Pass. Missing EN correctly falls back to `/en`. |
| W07c Empty news | `<h1>` → neutral empty message | Pass. Do not render empty filters or pagination. |
| W08 Contact | Contact `<h1>` and frozen identity → form → submission feedback | Pass. Error summary, when present, precedes the form; field errors remain next to fields. |
| W08b Form states | Status heading/message → affected fields → submit action | Pass. Success replaces the form or receives focus; it is not conveyed by colour alone. |
| W09 Legal | `<h1>` → optional contents → ordered `<h2>` sections | Pass. Reading measure remains narrow; legal sections are not cardified. |
| W10 System | Error `<h1>` → plain explanation → one recovery action | Pass. Technical details and application navigation are secondary or absent. |
| W11 Careers | Breadcrumb → `<h1>` → empty message → contact link if recruitment enquiries are accepted | Pass. Team and partners may reuse the state while VER-02 / VER-09 remain open. |

## 5. Content priority and disclosure

- Homepage: verified institutional proposition first; published solutions second; stable expertise taxonomy third.
- Product: identity and outcome before implementation detail. Related content never interrupts the main explanation.
- Technical inspection: overview before the twelve-point process. Only one accordion panel needs to be open; all headings remain scannable when collapsed.
- Directory: filters and list before map. Search results announce their count; no-result recovery is adjacent to the message.
- Resources and news: browse metadata before secondary summaries; pagination follows the collection it controls.
- Contact: direct corporate details remain available even if the form fails.
- Empty states: explain valid absence and offer the nearest useful route. They never imply an error or fabricate content.

## 6. High-fidelity handoff constraints

Step 118 must preserve the hierarchy above and demonstrate at least these stress cases:

1. The complete eight-item French navigation.
2. The longest product and expertise names on cards and detail headings.
3. A technical-inspection accordion item with a full long title.
4. A directory entry with no phone, email, or coordinates.
5. A resource with a visible `historical` label and no downloadable file.
6. A missing-translation language-switch state.
7. One empty collection and one contact-form error state.
8. The full frozen corporate address in the contact page and footer.

Colour, font, spacing, mobile reflow, and measured accessibility remain steps 118–121. No public frontend implementation starts before the approval gate at step 122.

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Information hierarchy reviewed against real V1 content and edge states (step 117 executed) | 2026-10-26 |


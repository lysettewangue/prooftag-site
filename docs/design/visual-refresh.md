# Public visual refresh

| Item | Value |
| --- | --- |
| **Reason** | Stakeholder review found the implemented shell too heavy and the homepage too skeletal |
| **Reference reviewed** | [G3 Control](https://g3control.com/) |
| **Date** | 2026-10-27 |
| **Status** | Implemented and visually checked at desktop and compact widths |

## Reference analysis

The reference was reviewed for composition rather than copied. Its strongest reusable principles are:

- a very slim utility strip followed by a predominantly white navigation area;
- one clear action in the header instead of a visually dominant navigation band;
- a large visual hero that combines a concise message, direct actions, and proof-oriented supporting points;
- generous section spacing, strong heading contrast, and restrained card borders;
- alternating white and softly tinted surfaces that create rhythm without making every section dark;
- a saturated brand colour reserved for focal surfaces and calls to action.

The reference's orange accent, photography, claims, navigation labels, and page-specific content are not reused.

## PROOFTAG-CATIS direction

The revised implementation keeps the official logo Royal Blue but moves the interface toward a fresher teal Azure and electric Cyan range. Large areas are white, mist, or pale cyan. Dark or saturated colour is confined to the abstract mobility panel, the final call to action, and the footer.

The homepage now follows a complete visitor journey:

1. positioning and two immediate actions;
2. an abstract road-and-network visual that makes the technology/mobility theme concrete without inventing photography;
3. three direct domain entry points;
4. a concise explanation of the site's clarity, trust, and accessibility principles;
5. one final contact/expertise action band;
6. the corporate footer fed by global settings.

## Responsive decisions

- The eight-item navigation appears only from 1280 px, where all labels fit without truncation.
- Tablet and compact widths keep a white native disclosure menu.
- Hero columns stack in document order on smaller screens.
- Actions become full-width on compact screens.
- Cards and approach items reflow without fixed text heights.

## Safeguards

- No yellow or orange was introduced.
- No G3 asset, source code, wording, statistic, or distinctive branded element was copied.
- No unverified PROOFTAG-CATIS statistic, testimonial, partner, centre count, or exclusive claim was added.
- The homepage copy remains translatable; CMS binding for published products / expertises / SEO is in [public-homepage.md](../development/public-homepage.md) (step 130).

## Verification

- Production Tailwind/Vite build succeeds.
- Desktop capture checked at 1440 px.
- Compact capture checked at 390 px.
- Public localisation, navigation, layout, footer, component, and token tests cover the revised shell.

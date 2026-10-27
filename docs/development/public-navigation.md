# Public navigation

| | |
|--|--|
| **Step** | 125 — Implement navigation |
| **Parent** | [Public layout shell](public-layout.md) (124) · [brand constraints](../charter/brand-and-navigation.md) |
| **Next** | [126 — Implement the footer](public-footer.md) |
| **Registry** | [`app/Support/PublicNavigation.php`](../../app/Support/PublicNavigation.php) |
| **View** | [`resources/views/components/public/site-header.blade.php`](../../resources/views/components/public/site-header.blade.php) |
| **Test** | [`tests/Feature/PublicNavigationTest.php`](../../tests/Feature/PublicNavigationTest.php) |
| **Date** | 2026-10-27 |
| **Status** | Eight-section bilingual navigation implemented |

## 1. Frozen navigation map

The registry owns path order and locale pairing. Labels remain in Laravel language files.

| Key | French | English |
|-----|--------|---------|
| `about` | À propos · `/a-propos` | About us · `/en/about` |
| `products` | Produits & Solutions · `/produits` | Products & Solutions · `/en/products` |
| `expertise` | Expertises · `/expertises` | Expertise · `/en/expertise` |
| `technical_inspection` | Contrôle technique · `/controle-technique` | Technical inspection · `/en/technical-inspection` |
| `centres` | Centres de contrôle · `/centres-de-controle` | Inspection centres · `/en/inspection-centres` |
| `resources` | Ressources · `/ressources` | Resources · `/en/resources` |
| `news` | Actualités & Articles · `/actualites` | News & Articles · `/en/news` |
| `contact` | Contact · `/contact` | Contact · `/en/contact` |

There is deliberately no Home item. The official logo is the active-locale home link. Search is a separate utility destination: `/recherche` or `/en/search`.

## 2. Responsive behavior

### Full navigation (`≥1280 px`)

- A slim pale-cyan utility strip carries the identity line and FR/EN controls.
- The logo, all eight items, search, and the contact action appear on a white navigation surface.
- The active section receives a cyan underline and `aria-current="page"`.

### Compact, medium, and narrow desktop (`<1280 px`)

- The logo and FR/EN controls remain visible.
- Search stays directly available and a native `<details>` disclosure exposes the complete menu without custom JavaScript.
- Links form one column on compact widths and two columns from 641 px.
- The contact action remains available inside the opened menu.

Native disclosure behavior preserves keyboard operation, expanded/collapsed semantics, and focus return without a script or focus trap.

## 3. Active-section rule

An item is active when the current request path equals its index path or begins with that path plus `/`. A product detail therefore activates Products, and an inspection-centre detail activates Centres. The locale prefix is part of the English comparison.

The homepage has no active primary item because it is represented by the logo.

## 4. Route boundary

This step freezes and renders navigation destinations; it does not invent placeholder content or return false-success pages. Controllers and public routes are added with their scheduled templates from step 130 onward. Until each destination is implemented, Laravel may correctly return 404 if a link is followed in the incomplete development build.

## 5. Verification

- Registry tests confirm exactly eight ordered items in French and English.
- Tests confirm the absence of a Home item and the correct search paths.
- Parent and descendant active-state matching is covered.
- Rendered-shell tests confirm both desktop and compact copies of every item.
- Existing layout, localisation, and Tailwind-token tests remain green.
- Vite/Tailwind production build succeeds and `git diff --check` is clean.

## 6. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen bilingual navigation implemented without a Home item; footer step 126 unlocked | 2026-10-27 |

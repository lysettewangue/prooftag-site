# Tailwind public tokens

| | |
|--|--|
| **Step** | 123 — Configure Tailwind tokens |
| **Parent** | [UI/UX approval](ui-ux-approval.md) (122) · [design system](design-system.md) (120) |
| **Next** | [124 — Implement the public layout](../development/public-layout.md) |
| **Implementation** | [`resources/css/app.css`](../../resources/css/app.css) |
| **Test** | [`tests/Feature/PublicDesignTokensTest.php`](../../tests/Feature/PublicDesignTokensTest.php) |
| **Date** | 2026-10-27 |
| **Status** | Tailwind CSS 4 tokens configured and production build verified |

## 1. Implementation strategy

The public theme is implemented with Tailwind CSS 4 `@theme static`. Static emission keeps the approved CSS custom properties available before every future utility is used. The existing generic Tailwind palette remains available temporarily for legacy Laravel views, but public templates from step 124 onward must use the semantic tokens below rather than arbitrary or generic colours.

No extra package or JavaScript framework was added. The Filament admin stylesheet remains separate.

## 2. Utility contract

| Design role | Tailwind utility examples |
|-------------|---------------------------|
| Official anchor | `bg-brand-royal`, `text-brand-royal`, `border-brand-royal` |
| Strong Azure surface | `bg-brand-azure-700`, `bg-brand-azure-600` with white text |
| Cyan action | `bg-brand-cyan-500 text-ink` |
| Soft emphasis | `bg-brand-cyan-100`, `bg-surface-subtle` |
| Page canvas | `bg-surface-canvas` |
| Text | `text-ink`, `text-muted` |
| Control boundary | `border-control-border` |
| Status | `text-status-success`, `bg-status-warning-soft`, `text-status-danger` |
| Interface type | `font-sans` |
| Long reading | `font-reading` |
| Type scale | `text-display-xl`, `text-heading-1`, `text-heading-2`, `text-heading-3`, `text-body-lg`, `text-body-sm`, `text-label`, `text-reading` |
| Responsive type | compact token by default, e.g. `text-heading-1-compact wide:text-heading-1` |
| Content measure | `max-w-site`, `max-w-reading` |
| Gutters and targets | `px-gutter-compact medium:px-gutter-medium`, `min-h-target`, `h-control` |
| Shape | `rounded-control`, `rounded-panel`, `rounded-card`, `rounded-hero`, `rounded-pill` |
| Elevation | `shadow-soft`, `shadow-card`, `shadow-hero` |
| Easing | `ease-standard`, `ease-deliberate` |

Tailwind generates these utilities when their class names occur in a scanned Blade or JavaScript source. The underlying variables are always emitted because the theme is static.

## 3. Responsive tokens

Compact is the unprefixed default. The content breakpoints remain unchanged; a dedicated navigation threshold was added after the [visual refresh](visual-refresh.md) so all eight long labels remain readable:

| Variant | Minimum | Range represented |
|---------|--------:|-------------------|
| unprefixed | 0 | compact, through 640 px |
| `medium:` | 641 px (`40.0625rem`) | medium/tablet |
| `wide:` | 1025 px (`64.0625rem`) | wide/desktop |
| `nav:` | 1280 px (`80rem`) | full eight-item header navigation |

This deliberately avoids device detection. Layouts must remain fluid between the reference widths.

## 4. Typography and font delivery

The approved stacks are configured as tokens:

- `font-sans`: DM Sans, then the native UI/system sans-serif stack;
- `font-reading`: Source Serif 4, then Georgia/system serif fallbacks.

No remote font request is introduced at this step. Until approved self-hosted font files are added, the fallback stacks keep content usable and avoid blocking public rendering.

## 5. Accessibility and governance

- Cyan primary actions pair with `text-ink`, never small white text.
- The sampled logo cyan `#00AEFE` is not exposed as an interface token.
- Warning is coral `#B5473C`; no yellow brand or warning token exists.
- Essential control borders use `control-border` (`#6B818C`) for more than 3:1 contrast on white.
- Focus components in step 127 must use `--focus-ring-inner` and `--focus-ring-outer` to preserve the approved two-colour treatment.
- Motion components must use `--motion-fast`, `--motion-base`, or `--motion-slow` and honour `motion-reduce`.
- Arbitrary colour values in public Blade require an explicit design-system amendment.

## 6. Verification

- `npm run build` completes with Vite and Tailwind CSS 4.
- The built public stylesheet contains the brand, breakpoint, typography, spacing, and motion variables.
- `PublicDesignTokensTest` protects the approved palette, responsive contract, shape, typography, and absence of a yellow brand token.
- `git diff --check` must remain clean.

## 7. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Approved design-system values mapped into Tailwind/CSS tokens; step 124 unlocked | 2026-10-27 |

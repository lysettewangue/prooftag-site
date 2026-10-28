# Shared public components

| Item | Value |
| --- | --- |
| **Step** | 127 — Implement shared components |
| **Parent** | [126 — Implement the footer](public-footer.md) |
| **Next** | 128 — Integrate Lucide → [public-lucide.md](public-lucide.md) (COMPLETED) · 129 JS foundation |
| **Status** | COMPLETED |

## Component catalogue

The shared anonymous Blade components live in `resources/views/components/public`. They use only the semantic Tailwind tokens approved at steps 120–123.

| Component | Blade tag | Contract |
| --- | --- | --- |
| Button | `x-public.button` | Link or native button; primary, secondary, outline, danger, disabled, and loading states |
| Link | `x-public.link` | Inline or standalone link with optional external-destination announcement |
| Card | `x-public.card` | Full title, optional eyebrow and single stretched destination; optional metadata footer |
| Badge | `x-public.badge` | Info, success, coral warning, danger, and neutral variants with mandatory text |
| Alert | `x-public.alert` | Titled status message; danger uses `role="alert"`, other variants use `role="status"` by default |
| Input | `x-public.form.input` | Label, help, validation message, required/disabled state, and native input types |
| Select | `x-public.form.select` | Native select with the same form-state contract |
| Textarea | `x-public.form.textarea` | Native textarea with the same form-state contract |
| Breadcrumb | `x-public.breadcrumb` | Linked ancestors and an unlinked `aria-current` page |
| Pagination | `x-public.pagination` | Previous/next links, a five-page window, disabled edges, and announced current page |
| Accordion | `x-public.accordion` | Native `details`/`summary`, optional number, and complete visible title |
| Empty state | `x-public.empty-state` | Explanation with one optional recovery action using the shared button |

## Usage rules

- Use at most one primary button in an action group.
- Use `disabled` for unavailable native buttons and `loading` while preventing repeated submission.
- A linked card has one destination. Its footer is metadata, not another interactive control.
- Form labels are always visible. Help and validation messages are connected through `aria-describedby`; errors also set `aria-invalid`.
- Status meaning always includes text. No component uses yellow.
- All controls meet the 44 px minimum target; principal controls use the 48 px control token.
- Components wrap naturally and do not truncate long French or English labels.
- Icons use the Lucide public set via `<x-public.icon>` — see [public-lucide.md](public-lucide.md).

## Examples

```blade
<x-public.button href="/contact" variant="primary">
    Nous contacter
</x-public.button>

<x-public.form.input
    name="email"
    label="E-mail"
    type="email"
    :error="$errors->first('email')"
    required
/>

<x-public.card title="CERTIDOCS CT" href="/produits/certidocs" eyebrow="Solution">
    Description complète de la solution.
</x-public.card>
```

## Verification

- `PublicComponentsTest` renders every component family and verifies link/button states, semantic elements, ARIA relationships, form errors, native disclosure, and pagination.
- The public footer, layout, navigation, localisation, and design-token regression tests remain green.
- The production Vite build includes all component classes successfully.

## Acceptance

| Role | Decision | Date |
| --- | --- | --- |
| Product owner | Shared Blade component foundation implemented; Lucide integration step 128 unlocked | 2026-10-27 |

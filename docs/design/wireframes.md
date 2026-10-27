# Low-fidelity wireframes

| | |
|--|--|
| **Step** | 116 — Create low-fidelity wireframes |
| **Parent** | [ui-ux-brief.md](ui-ux-brief.md) (114) · [brand-tokens.md](brand-tokens.md) (115) |
| **Next** | [117 — Review information hierarchy](information-hierarchy-review.md) |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-22 |
| **Status** | Desktop lo-fi key templates. Not public Blade |

Open [wireframes/index.html](wireframes/index.html) in a browser. Grayscale boxes only. Royal Blue and type wait for 118–120. Responsive frames wait for 119. Filament `/admin` is out of scope.

## 1. Artboards

| ID | Template | States shown |
|----|----------|--------------|
| W01 | Global shell | Skip link, logo home, eight sections, **no** Accueil, FR current, EN switch, search, footer from the contact record, no social (VER-10) |
| W02 | Homepage | Hero without VER-01 claim; CTAs Produits / Contrôle technique / Contact; published-only product cards; five expertise cards; no counters |
| W02b | Homepage | Empty published product grid |
| W03 | Product detail | CERTIDOCS CT as the ×5 pattern; related expertise/resources; no DITROS-CT |
| W04 | Contrôle technique + 12 points | Numbered accordion (not all-caps); equipment teaser |
| W05 | Centre directory | Search, region, list primary; map enhancement; Active only (CADCIA, HYDRAC, AUTO VISION). SATELLITE NGONO omitted |
| W05b | Centre detail | Missing phone / email / coordinates |
| W05c | Directory | No matches |
| W06 | Resource library | Current / historical / archive as **text**; pagination |
| W07 | News listing | When published |
| W07b | Article | Independent FR/EN slugs; EN missing → switch to `/en` |
| W07c | News listing | Empty (valid) |
| W08 | Contact | Frozen address; labelled fields; honeypot not shown |
| W08b | Contact | Field error + success |
| W09 | Legal | Mentions (privacy reuses the same measure) |
| W10 | System | 404 · 500 · maintenance |
| W11 | Careers | Empty listing (P-11) |

Same-family templates (About, expertise detail, equipment, search, product index) reuse W01 + page/card patterns. They are not extra visual systems.

## 2. Fixtures

Representative CMS rows are **structure only**. Do not treat sample news, draft products, or four centres as launch content. VER-01–13 stay Open.

## 3. Verification (2026-10-22)

- Browser: `docs/design/wireframes/index.html` — W01–W11 present.
- TOC jump to W05 (directory: CADCIA, HYDRAC, AUTO VISION; SATELLITE NGONO omitted).
- Contact honeypot not exposed. Eight-section nav without Accueil.
- Heading levels corrected by the step 117 hierarchy review; visual treatment remains low fidelity.

## 4. Out of this step

| Item | Step |
|------|------|
| Hierarchy vs real copy length | 117 |
| High-fidelity colour and type | 118 |
| Tablet / mobile | 119 |
| Design system | 120 |
| Accessibility review of composed screens | 121 |
| Approval gate | **122** |
| Tailwind / Blade | 123+ |

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Low-fidelity wireframes created for the key templates (step 116 executed) | 2026-10-22 |

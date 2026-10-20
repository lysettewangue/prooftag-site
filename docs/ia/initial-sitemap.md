# Initial sitemap

| | |
|--|--|
| **Step** | 006 — Freeze the initial information architecture |
| **Parent** | [brand-and-navigation.md](../charter/brand-and-navigation.md) v1.0 · [products.md](../charter/products.md) v1.0 |
| **Next** | 007 — Legacy source register |
| **Tracker** | [STEP-BY-STEP.md](../STEP-BY-STEP.md) |
| **Version** | 1.0 |
| **Date** | 2026-10-20 |
| **Status** | Frozen (initial). Final sitemap is step 020. |

Canonical host: `https://www.prooftagcatis.com`. French has **no** `/fr` prefix. English lives under `/en/…`. Slugs are per locale.

## 1. Host policy

| Host | V1 behaviour |
|------|----------------|
| www.prooftagcatis.com | Canonical |
| site.prooftagcatis.com | 301 to matching www path |
| net.prooftagcatis.com | 301 to www (retire host) |
| Apex prooftagcatis.com | 301 to www |

Exact path map: [legacy-url-register.csv](../legacy/legacy-url-register.csv) (step 008); applied at steps 165–167.

## 2. French tree

| Path | Type | Notes |
|------|------|-------|
| `/` | Home | Logo target |
| `/a-propos` | Page | Company narrative |
| `/a-propos/organisation` | Page | |
| `/a-propos/historique` | Page | 2020 positioning belongs here if retained |
| `/a-propos/equipe` | Team | Publish after VER-02 |
| `/a-propos/partenaires` | Partners | Publish after VER-09 |
| `/a-propos/carrieres` | Careers index | |
| `/produits` | Product index | Published products only |
| `/produits/certidocs-ct` | Product | |
| `/produits/certidocs-ct-reporting` | Product | Hidden until VER-06 |
| `/produits/ct-verif` | Product | Hidden until VER-04 |
| `/produits/doser` | Product | Hidden until VER-05 |
| `/produits/video-enforcement` | Product | Hidden until VER-07 |
| `/expertises` | Expertise index | |
| `/expertises/recherche-et-developpement` | Expertise | |
| `/expertises/support-technique` | Expertise | |
| `/expertises/analyse-donnees-reporting` | Expertise | |
| `/expertises/securite-documentaire` | Expertise | |
| `/expertises/securite-prevention-routiere` | Expertise | |
| `/controle-technique` | Knowledge hub | Not a product |
| `/controle-technique/points-de-securisation` | 12 points | |
| `/controle-technique/equipements` | Equipment | |
| `/centres-de-controle` | Directory | Active centres only |
| `/centres-de-controle/{slug}` | Centre | |
| `/ressources` | Resource index | |
| `/actualites` | Post index | |
| `/actualites/{slug}` | Post | |
| `/contact` | Page + form | |
| `/mentions-legales` | Page | |
| `/politique-de-confidentialite` | Page | |

No DITROS-CT path. Search may be `/recherche` (confirmed at step 020).

## 3. English mirrors

| FR | EN |
|----|----|
| `/` | `/en` |
| `/a-propos` | `/en/about` |
| `/produits` | `/en/products` |
| `/expertises` | `/en/expertise` |
| `/controle-technique` | `/en/technical-inspection` |
| `/centres-de-controle` | `/en/inspection-centres` |
| `/ressources` | `/en/resources` |
| `/actualites` | `/en/news` |
| `/contact` | `/en/contact` |
| `/mentions-legales` | `/en/legal-notice` |
| `/politique-de-confidentialite` | `/en/privacy-policy` |

Product slug fragments stay branded (`certidocs-ct`, `ct-verif`, `doser`, `video-enforcement`).

## 4. What this sitemap does not freeze

Content types and relationships: steps 021–022. Wireframes: 114–122. Hidden products still **have** paths reserved; they are omitted from nav, sitemap.xml, and search until published.

## 5. Acceptance

| Role | Decision | Date |
|------|----------|------|
| Product owner | Frozen as initial sitemap (step 006 executed) | 2026-10-20 |

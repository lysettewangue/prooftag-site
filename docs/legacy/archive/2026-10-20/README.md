# Legacy archive — 2026-10-20

Steps 009 (text) and 010 (media). Not a restorable WordPress backup (step 211).

## Text (009)

| File | Source |
|------|--------|
| `site-pages.json` | WP REST pages (16) |
| `site-posts.json` | WP REST posts (`[]`) |
| `extracts.md` | Public HTML captures (www, site, net) |

www REST returned **401**. Remaining www menu URLs not in [legacy-url-register.csv](../../legacy-url-register.csv) need a host-panel or HTTrack pass before cutover.

## Media (010)

| File | Source |
|------|--------|
| `site-media-p1.json` | WP REST media (50 items — full set; page 2 empty) |
| `media/` | Official logos and product marks (AUD-028 seed set) |
| `media/binaries/` | Full 50 binaries pulled at step 159 from `source_url` |

Classification: [media-classification.csv](../../content/media-classification.csv). CMS attach: [migrate-media.md](../../development/migrate-media.md). Do not use DITROS marks on the new corporate chrome.

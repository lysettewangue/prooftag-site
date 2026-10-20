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
| `media/` | Downloaded logos and product marks (not the full 50 binaries) |

Binaries still on origin at the `source_url` values in `site-media-p1.json`. Prefer those originals at migration (step 159). Do not use DITROS marks on the new corporate chrome.

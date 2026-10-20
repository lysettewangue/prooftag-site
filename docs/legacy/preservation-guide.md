# Legacy preservation guide

| | |
|--|--|
| **Steps** | 007–010 |
| **Archive** | [archive/2026-10-20/](archive/2026-10-20/) |
| **Sources** | [source-register.md](source-register.md) |
| **URLs** | [legacy-url-register.csv](legacy-url-register.csv) |

## What is done (2026-10-20)

- Source register for www, site, net, and external apps
- Strategic URL register with proposed FR/EN targets
- Text extracts + WP REST JSON for site (16 pages, 0 posts, 50 media records)
- Logo binaries downloaded under `archive/2026-10-20/media/`

## What remains before cutover (not this band)

- HTTrack or host-panel dump of remaining www URLs (galerie, lois, industries, actualité) if they still serve content
- WordPress file + database backup, DNS/MX/SSL export — **step 211**
- Full media binary pull from `site-media-p1.json` at step 159

## Methods (repeatable)

**site.prooftagcatis.com**

```text
GET /wp-json/wp/v2/pages?per_page=100&_fields=id,slug,link,title,modified,parent,status
GET /wp-json/wp/v2/media?per_page=100&_fields=id,source_url,title,mime_type,alt_text
GET /wp-json/wp/v2/posts?per_page=100
```

**www.prooftagcatis.com** — REST 401; public HTML GET or crawler.

**net.prooftagcatis.com** — treat as www mirror; do not keep its email.

Do not scrape CT-VERIF / DOSER operational UIs.

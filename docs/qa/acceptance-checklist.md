# Acceptance checklist

Used at steps 177–185. Step 186 (release candidate) stays locked until this list is signed.

## Functional — public

- [ ] Homepage FR and EN  
- [ ] All five product pages (or gated products hidden per VER-07)  
- [ ] Expertise index and five details  
- [ ] Contrôle technique hub and 12 points  
- [ ] Centre directory filter/pagination  
- [ ] Resources list and download  
- [ ] News list and article  
- [ ] Contact form delivery to inbox  
- [ ] Language switch preserves equivalent content  
- [ ] Legal pages present  

## Editorial

- [ ] Draft not visible publicly  
- [ ] Scheduled post publishes after cron/job  
- [ ] Unpublish removes from index  

## Admin / RBAC

- [ ] Each role sees only permitted modules  
- [ ] Publish permission enforced  
- [ ] Centre verify action works  

## Security

- [ ] Admin requires login; no public register  
- [ ] CSRF on forms  
- [ ] Upload rejects executable  
- [ ] `APP_DEBUG=false` on staging/production checklist  

## SEO

- [ ] Canonical and hreflang on sample pages  
- [ ] Sitemap generates valid URLs  
- [ ] Sample legacy redirect works  

## Accessibility

- [ ] Keyboard navigation header and contact form  
- [ ] Focus visible  

## Performance (smoke)

- [ ] Homepage loads acceptably on throttled mobile (manual or Lighthouse)  

## Sign-off

| Role | Name | Date |
|------|------|------|
| Product owner | | |
| Technical lead | | |

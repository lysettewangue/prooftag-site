# LWS Starter hosting notes

## Document root

Point domain to Laravel **`public/`** directory only.

## PHP

- Select **PHP 8.4** in panel  
- Enable required Laravel extensions  

## Deploy flow (summary)

1. Upload or Git pull release to server  
2. `composer install --no-dev --optimize-autoloader`  
3. `php artisan migrate --force`  
4. Upload or build frontend → `public/build`  
5. `php artisan config:cache route:cache view:cache`  
6. Verify `storage/` and `bootstrap/cache/` writable  

## Cron

Use **PHP** cron entry (not HTTP) when Cloudflare may block HTTP cron:

```text
php8.4 /home/.../artisan schedule:run >> /dev/null 2>&1
```

Keep scheduled jobs lightweight (< 4 minutes).

## Mail

Configure SMTP; SPF/DKIM for `info@prooftagcatis.com`.

## Staging

- `staging.prooftagcatis.com`  
- Basic authentication + `X-Robots-Tag: noindex` or middleware  

## Quotas to monitor

- Bandwidth ~45 GB/month optimal  
- 60 concurrent connections  
- Storage 200 GB  

## References

- [LWS Laravel hosting](https://www.lws.fr/hebergement-laravel.php)  
- [Cron on LWS](https://aide.lws.fr/base/Hebergement-web-mutualise/Outils-web/Comment-creer-une-tache-cron-LWS-Panel)  
- [Quotas](https://aide.lws.fr/base/Hebergement-web-mutualise/formules-hebergement-web/Quotas-formules-dhebergement-web)

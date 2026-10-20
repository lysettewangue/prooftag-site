# Rollback outline

## Triggers

- Critical production error after cutover  
- Data loss or widespread 500 errors  
- Failed migration without quick fix  

## Steps

1. Enable maintenance mode on new app if partially up.  
2. Restore DNS or the vhost to the legacy backup taken at step 211.  
3. Restore legacy files and database from dated backup.  
4. Communicate internally; post-mortem before second cutover attempt.  

## New application rollback

- Redeploy previous **git tag** artifact.  
- `php artisan migrate:rollback` only if safe and tested—prefer forward fix.  
- Restore `storage/app` media backup if corrupted.  

## Prevention

- Staging acceptance (step 205) is mandatory  
- Local release candidate (step 186) is signed  
- Keep the legacy backup for at least 30 days after launch  

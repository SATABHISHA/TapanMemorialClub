# Hostinger Shared Hosting Deployment Guide

## 1. Build Locally
1. composer install --optimize-autoloader --no-dev
2. npm install
3. npm run build
4. php artisan config:cache
5. php artisan route:cache
6. php artisan view:cache

## 2. Upload Files
1. Upload all project files to Hostinger `public_html` parent folder (for example: `~/tmc-app`).
2. Move the contents of project `public/` into `public_html/`.
3. Edit `public_html/index.php` paths:
   - `require __DIR__.'/../tmc-app/vendor/autoload.php';`
   - `$app = require_once __DIR__.'/../tmc-app/bootstrap/app.php';`

## 3. Environment and Permissions
1. Copy `.env.hostinger.sample` to `.env`.
2. Set DB and mail credentials.
3. Run `php artisan key:generate` once.
4. Ensure writable folders:
   - `storage/`
   - `bootstrap/cache/`

## 4. Database Setup
1. Create MySQL database in Hostinger panel.
2. Import schema with:
   - `php artisan migrate --force`
3. Seed data:
   - `php artisan db:seed --force`

## 5. Queue Setup (Shared Hosting Friendly)
Use cron every minute:
`* * * * * /usr/bin/php /home/USERNAME/tmc-app/artisan queue:work --stop-when-empty >> /dev/null 2>&1`

## 6. Scheduler Setup
Add cron:
`* * * * * /usr/bin/php /home/USERNAME/tmc-app/artisan schedule:run >> /dev/null 2>&1`

## 7. Performance Optimization
1. `php artisan optimize`
2. `php artisan event:cache`
3. Use `QUEUE_CONNECTION=database`.
4. Keep image uploads compressed and leverage blob fallback over 2MB.
5. Enable Cloudflare CDN if available.

## 8. Default Admin Login
- Email: admin@tapanmemorialclub.com
- Password: password
(Immediately change password in production)

## 9. GitHub Actions CI/CD (Auto Deploy)
This repository includes `.github/workflows/deploy-hostinger.yml`.

It runs on every push to `main` and executes `./deploy.sh` on Hostinger over SSH.

Set these GitHub repository secrets before enabling auto deploy:
- `HOSTINGER_HOST` (example: `82.25.106.143`)
- `HOSTINGER_PORT` (example: `65002`)
- `HOSTINGER_USERNAME` (example: `u473577775`)
- `HOSTINGER_PASSWORD` (your SSH password)

After secrets are added, every push to `main` will deploy automatically.

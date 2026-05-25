#!/bin/bash
set -e

cd /home/u473577775/domains/tapanmemorialclub.sroy.es/public_html

git fetch origin main
git reset --hard origin/main

composer install --no-dev --optimize-autoloader

php artisan optimize:clear
php artisan migrate --force
php artisan optimize

rm -rf public/storage
ln -s ../storage/app/public public/storage

chmod -R u+rwX storage bootstrap/cache

# Ensure uploaded media files are readable by the web server
if [ -d "storage/app/public/media" ]; then
    find storage/app/public/media -type d -exec chmod 755 {} \;
    find storage/app/public/media -type f -exec chmod 644 {} \;
fi

echo "Deployment completed successfully."

# Shared Hosting Optimization Notes

## PHP and Laravel
- Use PHP 8.2+.
- Set `APP_DEBUG=false` in production.
- Run `php artisan optimize` after each deployment.
- Use database drivers for `cache`, `session`, and `queue` for compatibility.

## Database
- Existing migrations include indexes for query-heavy columns.
- Run periodic cleanup for soft-deleted media and contacts.

## Image Strategy
- Images are compressed before save.
- Files <=2MB are stored on `public` disk.
- Files >2MB are stored as LONGBLOB bytes in `media_libraries.image_bytes`.
- Thumbnails and WebP bytes are generated for fast delivery.

## Frontend
- Use lazy loading (`loading="lazy"`) for gallery images.
- Vite build output is static and CDN-ready.

## Queue and Scheduler
- Use cron-triggered short workers instead of daemon workers.
- Keep queue jobs lightweight to stay inside Hostinger memory limits.

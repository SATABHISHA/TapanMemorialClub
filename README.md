# Tapan Memorial Club - Laravel Premium Sports Website

Production-ready Laravel + MySQL club website with premium animated frontend and dynamic admin panel, designed for Hostinger shared hosting.

## Stack
- Laravel 12 (Blade)
- MySQL (primary), SQLite supported if driver available
- Bootstrap 5 + Tailwind utility pipeline
- GSAP, AOS, SwiperJS, AnimeJS, Chart.js
- Spatie Roles/Permissions
- Intervention Image

## Architecture
- MVC with Repository + Service pattern
- Image optimization pipeline in:
  - `app/Services/ImageOptimizationService.php`
  - `app/Support/ImageHelper.php`
  - `app/Repositories/Eloquent/MediaRepository.php`
- Blob fallback over 2MB (`media_libraries.image_bytes`)
- Dynamic media stream endpoints:
  - `/media/{id}`
  - `/media/{id}/thumb`

## Modules
- Dynamic Menus/Submenus
- Slider management
- Gallery management
- Performance management
- Achievement management
- Blog/News management
- Sponsors
- Settings
- Contact submissions
- Media library with compression and bytes handling
- Admin dashboard analytics widgets

## Key Paths
- Frontend home: `resources/views/frontend/home.blade.php`
- Frontend layout: `resources/views/layouts/frontend.blade.php`
- Admin layout: `resources/views/layouts/admin.blade.php`
- Routes: `routes/web.php`
- Migrations: `database/migrations`
- Seed data: `database/seeders/DatabaseSeeder.php`

## Local Setup
1. `composer install`
2. `npm install`
3. Copy `.env.example` to `.env` (or use `.env.hostinger.sample`)
4. Set DB credentials (`mysql` recommended)
5. `php artisan key:generate`
6. `php artisan migrate --seed`
7. `php artisan storage:link`
8. `npm run build`
9. `php artisan serve`

## Default Admin Credentials
- Email: `admin@tapanmemorialclub.com`
- Password: `password`

## Hostinger Deployment
See:
- `DEPLOY_HOSTINGER.md`
- `SHARED_HOSTING_OPTIMIZATION.md`

## Asset Notes
Place logo and hero static files in:
- `public/assets/images/logo.jpeg`
- `public/assets/images/stadium-bg.jpg`

A placeholder note exists in `public/assets/images/README.txt`.

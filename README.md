# Muni University News & Media Portal

Institutional-grade news portal for **Muni University** (Arua City, Uganda) — `news.muni.ac.ug` — built with Laravel 11.

## Branding
- **Primary Red:** `#8B0000` `--muni-red`
- **Dark Red:** `#5C0000` `--muni-red-dark`
- **Gold:** `#ffde00` `--muni-gold`
- **Blue:** `#24AAE1` `--muni-blue`
- **Fonts:** Merriweather (headings) + Source Sans Pro (body)
- **Logo:** `/public/assets/images/muni-logo.png`

## Tech Stack
- **Backend:** Laravel 11 (PHP 8.2+), MySQL 8 (SQLite for dev), `spatie/laravel-permission` 6.25, `intervention/image` 3.11, `spatie/laravel-sitemap` 8.0, Laravel Breeze 2.4 (Blade)
- **Frontend:** Blade components, Bootstrap 5.3, Tailwind 3.x, Alpine.js 3, Font Awesome 6.4, Vite 5, TinyMCE 6 CDN
- **Storage:** `storage/app/public` with symlink `public/storage`

## Quick Start

```bash
git clone https://github.com/ALICAIPAULJURUA/muni-news.git
cd muni-news
composer install
cp .env.example .env
php artisan key:generate

# Configure DB in .env (SQLite default: touch database/database.sqlite)
# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=muni_news
# DB_USERNAME=root
# DB_PASSWORD=

# Storage link
php artisan storage:link

# Migrate & seed (creates super_admin, roles, categories, settings)
php artisan migrate --seed
# or fresh
php artisan migrate:fresh --seed

# Frontend assets
npm install
npm run build   # or npm run dev for Vite dev server

# Sitemap (public/sitemap.xml)
php artisan sitemap:generate

php artisan serve
# http://localhost:8000
# Admin: http://localhost:8000/admin/dashboard
# Login: admin@muni.ac.ug / password
```

## Default Seeded Data

**Super Admin**
- `admin@muni.ac.ug` / `password`
- `username: admin, full_name: Super Admin, is_active: true`

**Roles & Permissions** (spatie)
- `super_admin` — all 8 permissions
- `comm_admin` — all except `manage users`
- `editor` — `publish articles, create drafts, moderate comments, view analytics, upload media`
- `viewer` — no permissions

Matrix: `manage users | manage settings | manage categories | publish articles | create drafts | moderate comments | view analytics | upload media`

**Categories** (6)
`News`, `Announcements`, `Events`, `Research`, `Student Stories`, `Staff Stories`

**Settings** (`settings` table key-value)
`site_name: Muni University News & Media Portal`, `site_tagline: Transforming Lives`, `site_domain: news.muni.ac.ug`, `primary_color: #8B0000` etc

## Environment

```env
APP_NAME="Muni University News & Media Portal"
APP_URL=https://news.muni.ac.ug
APP_ENV=production
DB_CONNECTION=mysql

FILESYSTEM_DISK=public
MAIL_MAILER=smtp
MAIL_HOST=smtp.muni.ac.ug
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_FROM_ADDRESS=info@muni.ac.ug
```

## Key Features

**Public Frontend** (`app/Http/Controllers/Frontend/`)
- `/` — Hero Grid (2fr large + 2 stacked secondary), Latest 3-col, Breaking ticker (`is_breaking`), Category horizontal scroll, Newsletter CTA
- `/news` + `/category/{slug}` — 3-col card grid, filter bar (search `q`, category dropdown), pagination `bootstrap-5`, 16:9 images, 2-line title clamp
- `/article/{slug}` — 65/35 layout (65% content / 35% sticky sidebar), breadcrumb, immersive header, `max-width:75ch` `line-height:1.8`, lead gold border, blockquote red border italic, related 3, tags, share (FB,X,LinkedIn,WhatsApp,Email)
- `/newsletters` — 2-col (articles left, PDF sidebar right with year filter 2-col button grid)
- `/events`, `/event/{slug}`, `/media-gallery` (Alpine lightbox), `/downloads`, `/page/{slug}`

**Device-Based View Counting** (`app/Http/Middleware/TrackViewMiddleware.php`)
- `fingerprint = sha256(IP + User-Agent)`
- If `article_id + fingerprint` exists in last 24h → skip else `increment(views)` + insert `article_views`

**Admin Panel** (`/admin` — `role:super_admin|comm_admin|editor` + granular)
- `admin/dashboard` — stat cards (articles/views/comments/subscribers), recent 5
- `admin/articles` — filterable table, TinyMCE (`/admin/upload-image` CSRF, validates MIME `jpeg,png,gif,webp,svg` 5MB, stores `storage/app/public/articles/`, returns `{location}`), CRUD with tags, featured/breaking/published, SEO
- `admin/categories` — tree view nested, sort_order
- `admin/events`, `admin/newsletters` (PDF 10MB), `admin/downloads` (10MB)
- `admin/users` — super_admin only, role assignment, toggle `is_active`
- `admin/media` — grid, bulk delete, `files.*` 5MB whitelist `Str::slug`
- `admin/comments` — pending/approved tabs, approve/delete
- `admin/subscribers` — export CSV, delete
- `admin/settings` — General/SEO/Email SMTP + test button
- `admin/reports` — analytics (top 5, views by day, category stats)

**Security**
- `@csrf` on all forms, `throttle:3,1` subscribe, `throttle:5,1` login (LoginRequest 5/min), Form Requests, whitelist MIME + real `getMimeType()`, `Str::slug` sanitize, Blade `{{ }}` escape, `hasAnyRole`, `is_active` check in `RoleMiddleware`

**SEO**
- Clean URLs `/article/{slug}`, canonical `url()->current()` in `layouts/app.blade.php`, per-page `meta_title`/`meta_description`/`og:image` absolute `asset('storage/...')` (WhatsApp/FB), Twitter card, Organization + NewsArticle JSON-LD (`resources/views/frontend/articles/show.blade.php` + `layouts/app`), `public/sitemap.xml` via `php artisan sitemap:generate` (28 URLs), `loading="lazy"` on card images, Vite minified

## Testing

```bash
php artisan test
# 34 tests (Auth, Profile, AdminPanel)
php artisan test --filter=AdminPanel
# Covers: dashboard auth 403, super_admin/editor/comm_admin, TinyMCE, image upload, article create
```

Manual checks:
- Login with `admin@muni.ac.ug` → `/admin/dashboard` 200, viewer → 403
- Subscribe `POST /subscribe` duplicate → validation error, throttle 3/min
- `GET /article/{slug}` increments `views` once per 24h fingerprint

## Deployment (Ubuntu + Nginx example)

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan storage:link
php artisan sitemap:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public/build

# Cron for sitemap daily
# 0 2 * * * cd /var/www/muni-news && php artisan sitemap:generate >> /dev/null 2>&1

# Nginx: point to /public, php-fpm 8.2, force https news.muni.ac.ug
```

## Folder Structure
```
app/Http/Controllers/Admin (Article, Category, Event, NewsletterAdmin, DownloadAdmin, User, MediaLibrary, Comment, Subscriber, Setting, Report, Dashboard)
app/Http/Controllers/Frontend (Home, Article, Newsletter, Event, Media, Download, Page, Subscribe)
app/Http/Middleware (RoleMiddleware, TrackViewMiddleware)
app/Services/ImageService
resources/views/layouts/{app,admin,auth} + frontend/* + admin/*
public/assets/images/muni-logo.png
public/sitemap.xml
```

## License
MIT — Muni University
 muni-news

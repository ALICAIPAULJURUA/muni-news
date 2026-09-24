# 📘 MUNI UNIVERSITY NEWS PORTAL — LARAVEL REBUILD
## Product Requirements Document (PRD)

---

## 1. PROJECT OVERVIEW

### 1.1 Project Name
**Muni University News & Media Portal**

### 1.2 Purpose
A modern, institutional-grade news portal for Muni University (Arua City, Uganda) to publish news, announcements, events, newsletters, and media content. Built for scalability, SEO, and editorial efficiency using Laravel.

### 1.3 Branding (NON-NEGOTIABLE)
- **Primary Red:** `#8B0000` (--muni-red)
- **Dark Red:** `#5C0000` (--muni-red-dark)
- **Gold Accent:** `#ffde00` (--muni-gold)
- **Blue Accent:** `#24AAE1` (--muni-blue)
- **Fonts:** `Merriweather` (headings, serif) + `Source Sans Pro` (body, sans-serif)
- **Logo Path:** `/assets/images/muni-logo.png`
- **Tagline:** "Transforming Lives"
- **Domain:** `news.muni.ac.ug`

---

## 2. TECHNICAL STACK

### 2.1 Backend
- **Framework:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8.0 (InnoDB, utf8mb4)
- **Authentication:** Laravel Breeze (Blade stack)
- **Authorization:** `spatie/laravel-permission` (role-based access)
- **Image Processing:** `intervention/image` v3
- **Rich Text Editor:** TinyMCE 6 (CDN)
- **File Storage:** Laravel Filesystem (`storage/app/public` with symlink)

### 2.2 Frontend
- **Templating:** Laravel Blade (component-based)
- **CSS Framework:** Bootstrap 5.3 + Custom CSS Variables
- **JavaScript:** Vanilla JS + Alpine.js (for interactive components like dropdowns/modals)
- **Icons:** Font Awesome 6.4
- **Build Tool:** Vite

---

## 3. DATABASE SCHEMA (Migrations)

OpenCode must create these migrations in order:

### 3.1 Core Tables

**`users`** (extends Laravel default)
- `id` (bigint, PK)
- `username` (varchar 50, unique)
- `full_name` (varchar 150)
- `email` (varchar 255, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (varchar 255)
- `avatar` (varchar 255, nullable)
- `is_active` (boolean, default true)
- `remember_token`
- `timestamps`

**`roles` & `permissions`** (via Spatie)
- Roles: `super_admin`, `comm_admin`, `editor`, `viewer`

**`categories`** (self-referencing hierarchy)
- `id` (bigint, PK)
- `parent_id` (bigint, nullable, FK → categories.id)
- `name` (varchar 100)
- `slug` (varchar 120, unique)
- `description` (text, nullable)
- `sort_order` (int, default 0)
- `timestamps`

**`articles`**
- `id` (bigint, PK)
- `author_id` (bigint, FK → users.id)
- `category_id` (bigint, FK → categories.id)
- `title` (varchar 255)
- `slug` (varchar 255, unique)
- `summary` (text)
- `content` (longText)
- `featured_image` (varchar 255, nullable)
- `is_featured` (boolean, default false)
- `is_breaking` (boolean, default false)
- `is_published` (boolean, default false)
- `published_at` (timestamp, nullable)
- `meta_title` (varchar 100, nullable)
- `meta_description` (varchar 200, nullable)
- `views` (int, default 0)
- `timestamps`
- `softDeletes`

**`article_views`** (device-based tracking)
- `id` (bigint, PK)
- `article_id` (bigint, FK)
- `device_fingerprint` (varchar 64, indexed)
- `ip_address` (varchar 45)
- `viewed_at` (timestamp)

**`tags` & `article_tag` (pivot)**
- `tags`: `id`, `name`, `slug` (unique), `timestamps`
- `article_tag`: `article_id`, `tag_id` (composite PK)

**`comments`** (moderated)
- `id` (bigint, PK)
- `article_id` (bigint, FK)
- `user_id` (bigint, nullable, FK)
- `author_name` (varchar 100)
- `author_email` (varchar 255)
- `content` (text)
- `is_approved` (boolean, default false)
- `parent_id` (bigint, nullable, for replies)
- `timestamps`

**`events`**
- `id` (bigint, PK)
- `title`, `slug`, `description`, `location` (varchars)
- `event_date` (datetime)
- `end_date` (datetime, nullable)
- `featured_image` (varchar 255, nullable)
- `is_online` (boolean, default false)
- `registration_link` (varchar 255, nullable)
- `timestamps`

**`newsletters`** (PDF archives)
- `id` (bigint, PK)
- `title`, `slug`, `description` (varchars/text)
- `publication_year` (int)
- `cover_image` (varchar 255, nullable)
- `file_path` (varchar 255)
- `download_count` (int, default 0)
- `timestamps`

**`downloads`**
- `id` (bigint, PK)
- `title`, `slug`, `description` (varchars/text)
- `category` (varchar 100)
- `file_path` (varchar 255)
- `download_count` (int, default 0)
- `timestamps`

**`media`** (media library)
- `id` (bigint, PK)
- `filename`, `original_name`, `path` (varchars)
- `mime_type`, `size`, `extension` (varchars/int)
- `uploader_id` (bigint, FK)
- `timestamps`

**`newsletter_subscriptions`**
- `id` (bigint, PK)
- `email` (varchar 255, unique)
- `is_active` (boolean, default true)
- `subscribed_at` (timestamp)

**`settings`** (key-value store)
- `id` (bigint, PK)
- `key` (varchar 100, unique)
- `value` (text)
- `timestamps`

**`pages`** (static pages: about, contact, etc.)
- `id` (bigint, PK)
- `slug` (varchar 100, unique)
- `title`, `content` (varchars/text)
- `meta_title`, `meta_description` (varchars, nullable)
- `timestamps`

---

## 4. USER ROLES & PERMISSIONS

| Permission | super_admin | comm_admin | editor | viewer |
|-----------|:-----------:|:----------:|:------:|:------:|
| Manage Users | ✅ | ❌ | ❌ | ❌ |
| Manage Settings | ✅ | ✅ | ❌ | ❌ |
| Manage Categories | ✅ | ✅ | ❌ | ❌ |
| Publish Articles | ✅ | ✅ | ✅ | ❌ |
| Create Drafts | ✅ | ✅ | ✅ | ❌ |
| Moderate Comments | ✅ | ✅ | ✅ | ❌ |
| View Analytics | ✅ | ✅ | ✅ | ❌ |
| Upload Media | ✅ | ✅ | ✅ | ❌ |

---

## 5. FEATURES — DETAILED SPECIFICATIONS

### 5.1 PUBLIC FRONTEND

#### 5.1.1 Homepage (`/`)
- **Hero Grid**: Asymmetric layout — 1 large featured article (2fr) + 2 stacked secondary (1fr)
- **Latest News**: 3-column grid, uniform 16:9 images, 2-line clamped titles
- **Breaking News Ticker** (if any `is_breaking` articles exist)
- **Category Highlights**: Horizontal scrolling sections per category
- **Newsletter CTA Band**: Muni-red background, subscribe form

#### 5.1.2 News Listing (`/news`, `/category/{slug}`)
- Uniform card grid (3 cols desktop, 2 tablet, 1 mobile)
- Filter bar: Search, Category dropdown, Status
- Pagination (SEO-friendly, numbered)
- Article cards: image, category badge, title (2-line clamp), summary (3-line), meta

#### 5.1.3 Article Detail (`/article/{slug}`)
- **Layout**: 2-column (65% content / 35% sticky sidebar on desktop)
- **Breadcrumb**: Home > Category > Title
- **Immersive Header**: Category badge, H1 title, meta (author, date, views), featured image
- **Typography**: Max 75 characters per line, 1.8 line-height
- **Lead paragraph**: Gold left border, larger font
- **Blockquotes**: Red left border, serif italic
- **Inline images**: `max-width: 100%`, centered, shadowed
- **Sidebar** (sticky): Related articles (3), Newsletter signup
- **Footer**: Tags, Social sharing (FB, X, LinkedIn, WhatsApp, Email)

#### 5.1.4 Newsletters (`/newsletters`)
- **List view**: 2-column (articles left, PDF sidebar right)
- **PDF Sidebar**: Download cards with year, download count
- **Year filter**: 2-column button grid (no overflow)

#### 5.1.5 Other Pages
- **Events** (`/events`, `/event/{slug}`): Listing + detail
- **Gallery** (`/media-gallery`): Grid with lightbox
- **Downloads** (`/downloads`): Categorized file list
- **Static Pages**: About, Contact, Research, Stories, VC Diary

### 5.2 ADMIN PANEL (`/admin`)

#### 5.2.1 Layout
- **Sidebar navigation** (collapsible on mobile)
- **Top bar**: User avatar, logout, quick actions

#### 5.2.2 Dashboard (`/admin/dashboard`)
- Stat cards: Articles, Views, Comments, Subscribers
- Recent activity feed

#### 5.2.3 Article Manager
- **List**: Filterable table (search, category, status), pagination
- **Create/Edit**: 
  - Title, Category, Summary, Content (TinyMCE)
  - Featured image upload
  - Publish settings: date, featured/breaking/published flags
  - SEO: meta_title, meta_description
  - Tags (multi-select)
- **TinyMCE Config**:js
plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount', 'codesample', 'paste'],
toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image media table | removeformat | code',
images_upload_url: '/admin/upload-image',
extended_valid_elements: 'style[type],script[src|type|defer],div[],span[],article[*]',
verify_html: false- **Image upload handler**: CSRF-protected, validates MIME + size (5MB), stores to `/storage/app/public/articles/`

#### 5.2.4 Other Managers
- **Categories**: Tree view with nesting
- **Events, Newsletters, Downloads**: CRUD operations
- **Users**: List with role assignment, activate/deactivate
- **Media Library**: Grid view with bulk delete
- **Comments**: Pending queue + approved list, approve/delete
- **Subscribers**: Email list with export CSV, delete
- **Settings**: General, SEO, Email (SMTP with test button)
- **Reports**: Analytics dashboard

---

## 6. UI/UX DESIGN SYSTEM

### 6.1 Design Tokens (`resources/css/variables.css`)
```css
:root {
  --muni-red: #8B0000;
  --muni-red-light: #C41E24;
  --muni-red-dark: #5C0000;
  --muni-blue: #24AAE1;
  --muni-emerald: #00b9f1;
  --muni-gold: #ffde00;
  --color-text-primary: #1a1a1a;
  --color-text-secondary: #4a5568;
  --color-text-muted: #718096;
  --color-bg-primary: #ffffff;
  --color-bg-secondary: #f7f9fc;
  --color-bg-tertiary: #edf2f7;
  --color-border: #e2e8f0;
  --font-heading: 'Merriweather', Georgia, serif;
  --font-body: 'Source Sans Pro', system-ui, sans-serif;
  --space-1: 0.25rem; --space-2: 0.5rem; --space-3: 0.75rem;
  --space-4: 1rem; --space-5: 1.5rem; --space-6: 2rem;
  --space-8: 3rem; --space-10: 4rem;
  --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
  --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
  --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
  --transition-fast: 150ms ease-in-out;
  --transition-base: 250ms ease-in-out;
}

6.2 Component Rules
Cards: border-radius: 2px, box-shadow: var(--shadow-sm), lift on hover (translateY(-4px))
Buttons: Sharp edges (2px radius), uppercase labels, 44px min-height for mobile
Badges: 2px radius, uppercase, 0.75rem font
Forms: 2px borders, focus ring with muni-blue
Tables: Dark header with gold bottom border
Article cards: aspect-ratio: 16/9 on images, 2-line title clamp, 3-line excerpt clamp
6.3 Accessibility (WCAG 2.1 AA)
All interactive elements: 44px min touch target
Focus states: outline: 2px solid var(--muni-blue)
Color contrast: ≥ 4.5:1 for text
ARIA labels on all icon-only buttons
Semantic HTML5: <header>, <nav>, <main>, <article>, <aside>, <footer>
7. SECURITY REQUIREMENTS
CSRF Protection: Laravel's @csrf on all forms
Rate Limiting: Login (5/min), Subscribe form (3/min per IP)
Input Validation: Laravel Form Requests for every endpoint
File Uploads:
Whitelist MIME types (validate real MIME, not extension)
Max size: 5MB images, 10MB documents
Store outside public root (storage/app/public/)
Sanitize filenames (Str::slug)
XSS Prevention: Blade auto-escapes {{ }}, use {!! !!} only for trusted TinyMCE content
SQL Injection: Always use Eloquent/query builder (no raw SQL)
Role-based Middleware: RoleMiddleware protecting /admin/* routes
8. SEO REQUIREMENTS
Clean URLs: /article/{slug}, /category/{slug}, /newsletters
Sitemap: Auto-generated via spatie/laravel-sitemap
Meta Tags: Per-page title, description, OG tags, Twitter cards
Schema.org: NewsArticle, Organization JSON-LD
Canonical URLs: On every page
Open Graph: Absolute URLs for og:image (critical for WhatsApp/FB)
Performance: Image lazy loading, minified CSS/JS via Vite
9. SPECIAL FEATURES
9.1 Device-Based View Counting
On article visit:
Generate fingerprint = sha256(IP + User-Agent)
Check article_views: if (article_id + fingerprint) exists in last 24h → skip
Else: increment articles.views, insert record in article_views
9.2 Newsletter Subscription
Form submits to /subscribe
Validate email format
Prevent duplicates (UNIQUE constraint + app-level check)
Flash success message via session
9.3 TinyMCE Image Upload
Custom images_upload_handler in JS
Sends to /admin/upload-image with CSRF
Returns JSON { "location": "/storage/articles/filename.jpg" }
10. FOLDER STRUCTURE
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        (ArticleController, UserController, etc.)
│   │   └── Frontend/     (HomeController, ArticleController, etc.)
│   ├── Middleware/       (RoleMiddleware, TrackViewMiddleware)
│   └── Requests/         (Form requests for validation)
├── Models/               (Article, Category, User, etc.)
└── Services/             (NewsletterService, ImageService, etc.)

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php        (public layout)
│   │   └── admin.blade.php      (admin layout)
│   ├── components/              (reusable: card, badge, alert)
│   ├── frontend/                (home, articles, newsletters, etc.)
│   └── admin/                   (dashboard, articles, users, etc.)
└── css/
    └── app.css                  (imports variables + components)

11. IMPLEMENTATION PLAN FOR OPENCODE
OpenCode must execute these phases in order. After each phase, commit to Git.
🎯 PHASE 1: Foundation
Install packages: spatie/laravel-permission, intervention/image, bootstrap@5.3, alpinejs
Create ALL migrations listed in Section 3
Create Seeder: super_admin user, default roles, default categories, default settings
Configure public storage disk symlink (php artisan storage:link)
Run migrations and seed.
🎯 PHASE 2: Authentication & Roles
Install Laravel Breeze (Blade stack)
Add Spatie roles, seed role matrix
Create RoleMiddleware, apply to /admin/* routes
Customize login view to match Muni branding
Build admin layout (admin.blade.php) with sidebar
🎯 PHASE 3: Public Frontend
Create public layout with header (top bar, logo, nav), footer
Implement HomeController (homepage hero + latest)
Implement ArticleController (index, show, category, search)
Build article detail page with 65/35 layout
Implement newsletters, events, gallery pages
Add TrackViewMiddleware for device-based views
🎯 PHASE 4: Admin Panel
Build admin dashboard with stat cards
Article CRUD with TinyMCE + image upload endpoint
Category CRUD with tree view
Events, Newsletters, Downloads CRUD
User management with role assignment
Media library, Comments moderation, Subscribers management
Settings pages (General, SEO, Email)
🎯 PHASE 5: Polish & Launch
Install spatie/laravel-sitemap, generate sitemap
Add JSON-LD schema to article pages
Test all forms, all user roles
Write README with deployment instructions
12. OPENCODE EXECUTION INSTRUCTIONS
When OpenCode starts work, it must:
Read this document fully before writing any code
Follow the phases in order — do not skip
Use Laravel best practices: Eloquent, Form Requests, Policies, Services
Never hardcode branding values — always use CSS variables
Commit after each phase with clear messages
Ask for clarification if any requirement is ambiguous
🚨 Critical Rules
DO NOT remove any feature listed in this PRD
DO NOT change the branding colors/fonts
DO NOT skip validation or security measures
ALWAYS use CSRF tokens on forms
ALWAYS use htmlspecialchars() or Blade's {{ }} for user content


GitHub Repository
echo "# muni-news" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/ALICAIPAULJURUA/muni-news.git
git push -u origin main
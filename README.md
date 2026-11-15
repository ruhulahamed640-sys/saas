# AHAMED DRAFTED — Personal Digital Journal, Editorial Portfolio & Life Archive

## 1. Architecture Overview
- **Frontend Stack:** Semantic HTML5, Centralized CSS3 Token System (`assets/css/tokens.css`, `base.css`, `components.css`), Modular Vanilla JavaScript (`assets/js/api.js`, `theme.js`), Responsive CSS Grid & Flexbox, SVG Iconography. Zero frameworks (No React, Next.js, Vue, Tailwind, or Bootstrap).
- **Backend Stack:** Native PHP 8.2+, MySQL 8.0+, PDO (`config/database.php`), Session & CSRF Guards (`config/auth.php`), REST-style JSON Endpoints (`api/v1/index.php`).

## 2. Complete Multi-Page Public Structure (13 Dedicated Pages)
1. `index.php` — 10-Section Editorial Landing Page (Hero, About Preview, Featured Journal, Asymmetrical Photo Grid, Life Timeline, Friends, Interests, Portfolio, Places, Footer)
2. `about.php` — Full Biography, Education Journey, Personal Philosophy, Favourite Things & Rituals
3. `story.php` — Chapter-Based Long-Form Editorial Storytelling & Pull Quotes
4. `journal.php` — Magazine Article Grid, Category Filter, Search, Sorting & Empty State
5. `article.php` — Long-Form Article Reader with Pull Quotes, Inline Captions & Related Dispatches
6. `gallery.php` — Editorial Photo Album Grid & Category Filters
7. `album.php` — Individual Album Contact Sheet, EXIF Metadata & Lightbox Controls
8. `memories.php` — Chronological Memory Feed with Categories & Dates
9. `friends.php` — Consent-Aware Friendship Cards & Shared Memories
10. `events.php` — November 23 Birthday Countdown & Celebration Archive
11. `places.php` — Visited Places (West Bengal, Haryana, Indian Railway Corridors) with Approximate Coordinates
12. `portfolio.php` — Projects (`slipperspoint.shop`, HostyCare/cPanel, Shutterstock Archive), Skills & Achievements
13. `guestbook.php` — Moderated Reader Correspondence & Submission Form

## 3. Complete 24-Module Admin CMS (`admin/index.php`)
Includes Overview, Profile, Homepage, Journal, Categories, Photo Gallery, Albums, Friends, Memories, Timeline, Birthday & Events, Places, Education, Interests, Achievements, Projects, Guestbook, Comments, Media Library, Navigation, Privacy, Users & Permissions, SEO Settings, Website Settings, and Backup.

## 4. Deployment Instructions (XAMPP / cPanel / HostyCare)
1. Import `sql/schema_and_seed.sql` into MySQL 8.0+.
2. Set environment variables (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) or configure `config/database.php`.
3. Upload project files to `public_html/` on cPanel/HostyCare or run locally via `php -S localhost:8080`.

# Ahamed Drafted — production foundation

## Mobile-first
- The public site and admin UI must work at 360px and wider.
- Use responsive grids, fluid type, touch-sized controls, and no horizontal page overflow.
- Respect reduced-motion preferences and lazy-load below-the-fold media.

## Content policy
- No seed/demo records are included.
- Public pages read only records marked `visibility=public` and `status=published`.
- Empty sections must show an empty state rather than fabricated content.

## Database setup
1. Create a private `.env` file in the project root on the hosting account.
2. Fill DB_HOST, DB_NAME, DB_USER, DB_PASS with the exact DirectAdmin database credentials.
3. Import `database/schema.sql` in phpMyAdmin.
4. Never upload or commit the real `.env`.

## Content storage
- `site_settings`: one `profile` setting containing the profile JSON document.
- `content_records`: content sections (journal, category, album, photograph, memory, friend, event, place, project, timeline, guestbook, education, achievement, interest) stored as JSON documents.
- `page_views`: privacy-minimized page view records for the analytics dashboard.
- `users`: admin accounts. Create the first owner through a secure one-time setup flow before exposing the admin login.

This foundation does not yet implement the complete CRUD editor or owner setup flow. Do not expose the admin area publicly until authentication is completed.

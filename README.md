# Tilawa - Dar Tahfeez MVP (Laravel 12 + MySQL + Tailwind CSS)

Minimal SaaS MVP for a Quran memorization center with tenants, teachers, classes, sessions, and parent read-only access.

## Features
- Multi-tenant scoping by `tenant_id`
- SaaS admin area to create/manage tenants
- Admin module: teachers, classes (halaqat), students, foundation skills, settings, onboarding wizard, reports
- Teacher module: dashboard, my students, session create/edit
- Parent portal: `/p/{token}` read-only progress view
- Score slider UI (0..10) with Arabic labels from tenant settings
- Surah list seeded (114 surahs)
- Reports: top students, inactive students, teacher performance
- PDF generation with DomPDF
- Excel export with Maatwebsite/Excel
- Onboarding wizard for quick setup
- Tailwind CSS v4 for responsive UI
- Vite for fast asset compilation

## Requirements
- PHP 8.2+
- MySQL 8+
- Composer 2.x
- Node.js 18+ (for frontend assets)
- Docker + Docker Compose (optional, for local testing)

## Docker Testing
```bash
# build and start
 docker compose up -d --build

# install dependencies (if not already installed)
 docker compose exec app composer install

# copy env
 docker compose exec app cp .env.example .env
 docker compose exec app php artisan key:generate

# set docker db config
 docker compose exec app bash -lc "grep -q DB_HOST .env || true"
 docker compose exec app bash -lc "printf '\nDB_HOST=db\nDB_PORT=3306\nDB_DATABASE=tilawa\nDB_USERNAME=tilawa\nDB_PASSWORD=tilawa\n' >> .env"

# migrate + seed
 docker compose exec app php artisan migrate --seed
```
App: http://localhost:8080

Default SaaS admin login:
- Login: `root@tilawa.com` (or username `saasadmin`)
- Password: `password`

Default tenant admin login:
- Login: `admin@tilawa.com` (or username `admin`)
- Password: `password`

Multi-tenant teacher:
- A teacher can be assigned to multiple tenants.
- Use the tenant switcher in the header if the teacher belongs to multiple centers.

## SaaS Admin (Tenant Management)
- Login as SaaS admin (`root@tilawa.com` / `saasadmin`)
- Go to **المنصّة** → **مركز جديد**
- Create a tenant + its tenant admin in one step

## Local Setup (without Docker)
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed data
php artisan migrate --seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

Or use the convenience script:
```bash
composer run setup
```

## Reports
- **Top Students**: List students by memorized ayahs (date range filter)
- **Inactive Students**: Students with no recent sessions
- **Teacher Performance**: Metrics per teacher
- **PDF Export**: Generate PDF reports
- **Excel Export**: Export data to Excel spreadsheets

## Business Rules Summary
- Session validation:
  - If attendance is `present`, require `surah_id`, `ayah_from`, `ayah_to`
  - `ayah_from >= 1`, `ayah_to <= surah.ayah_count`, `ayah_to >= ayah_from`
  - `ayah_count = ayah_to - ayah_from + 1`
  - If attendance is `absent`/`excused`, surah + ayahs optional, `ayah_count = 0`
- Overlap protection:
  - For `session_type = new` and `attendance_status = present`, prevent overlapping ayah ranges in the same surah for the same student
- Progress:
  - `total_memorized_ayahs` = sum of `ayah_count` where `session_type = new` and `attendance_status = present`
  - `memorized_percent` = `(total_memorized_ayahs / 6236) * 100`
- Teacher ↔ Groups:
  - Teachers have `allowed_groups_json`
  - Class assignment requires the class group to be in teacher allowed groups
  - Teachers can only create sessions for students in classes assigned to them

## Tech Stack
- **Backend**: Laravel 12, PHP 8.2+
- **Database**: MySQL 8+
- **Frontend**: Blade templates, Tailwind CSS v4, Vite 7
- **Libraries**: DomPDF (PDF), Maatwebsite/Excel (Excel export)

## Development Commands
```bash
# Full development stack (server, queue, logs, Vite)
composer run dev

# Run tests
composer test

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Production optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Seed Data
- 114 Surahs with `id`, `name_ar`, `ayah_count`, `start_page`
- Default foundation skills
- Default score thresholds

## Backup (Daily mysqldump)
Use `backup.sh` and set a cron job:
```
0 2 * * * /path/to/tilawa/backup.sh
```
- Keeps 14 days of compressed backups in `storage/backups`

## Deployment

For detailed deployment instructions, see [DEPLOYMENT.md](DEPLOYMENT.md).

Quick summary:
1. Create MySQL database and user on hosting server
2. Upload project files (excluding `node_modules`, `.git`)
3. Configure `.env` for production
4. Set file permissions: `chmod -R 775 storage bootstrap/cache`
5. Run migrations: `php artisan migrate --seed --force`
6. Set up cron job for daily backups: `0 2 * * * /path/to/backup.sh`
7. Enable HTTPS and SSL certificate

## Project Structure

- `app/Http/Controllers/` - Controllers organized by module (Admin, Teacher, Public, Saas)
- `app/Http/Models/` - Eloquent models (Tenant, User, Student, Session, etc.)
- `app/Http/Services/` - Business logic services (ReportService)
- `database/` - Migrations and seeders
- `resources/views/` - Blade templates organized by module
- `routes/` - Route definitions
- `docker/` - Docker configuration for local development
- `storage/backups/` - Automated daily backups (14-day retention)

## Repository
- **Private Repository**: https://github.com/abdallahemad94/Tilawa
- **Framework**: Laravel 12
- **License**: MIT

## Notes
- Parent portal is read-only: `/p/{token}`
- Tokens are regenerated from the admin student edit page
- Docker setup available for isolated development environment
- Backup script keeps 14 days of compressed MySQL backups

## Documentation
- [DEPLOYMENT.md](DEPLOYMENT.md) - Detailed deployment guide for Hostinger
- [FILE_TREE.md](FILE_TREE.md) - Complete project structure
- [MISSING_FEATURES_ANALYSIS.md](MISSING_FEATURES_ANALYSIS.md) - Feature roadmap and recommendations

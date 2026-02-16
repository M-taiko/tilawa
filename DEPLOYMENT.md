# Deployment Guide - Hostinger Shared Hosting

## Prerequisites

- Hostinger shared hosting account with cPanel
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer access (or local development environment)
- SSH access (recommended)

## Step 1: Local Preparation

1. Clone or prepare your Laravel project locally
2. Update `.env` file with production database credentials:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   ```

3. Install dependencies:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

4. Run migrations locally to verify:
   ```bash
   php artisan migrate --seed
   ```

## Step 2: Upload to Hostinger

### Option A: Using cPanel File Manager

1. Log in to cPanel
2. Go to File Manager → public_html
3. Upload all files from your Laravel project EXCEPT:
   - `.env` file (create manually on server)
   - `node_modules/` folder
   - `.git/` folder
   - Any test files

### Option B: Using Git (Recommended)

1. Push your code to GitHub/GitLab
2. SSH into your Hostinger account
3. Clone the repository:
   ```bash
   cd public_html
   git clone https://github.com/username/tilawa.git .
   ```

## Step 3: Configure Environment

1. Create `.env` file in your hosting root directory
2. Copy content from `.env.example`
3. Update with your production values
4. Generate app key:
   ```bash
   php artisan key:generate
   ```

## Step 4: Set File Permissions

SSH into your server and run:

```bash
# Set proper ownership (replace username with your cPanel username)
chown -R username:username .

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Make storage and cache writable
chmod -R 775 storage bootstrap/cache

# Generate application key
php artisan key:generate
```

## Step 5: Create Symbolic Link

```bash
php artisan storage:link
```

## Step 6: Run Migrations

```bash
php artisan migrate --seed
```

## Step 7: Configure Cron Jobs

Set up the following cron jobs in cPanel → Cron Jobs:

```bash
# Daily backup at 2 AM
0 2 * * * php /home/username/public_html/artisan schedule:run >> /dev/null 2>&1

# Or use backup script directly
0 2 * * * /home/username/public_html/backup.sh >> /home/username/backup.log 2>&1
```

## Step 8: Configure .htaccess

Ensure your `.htaccess` file in the public folder contains:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]
</IfModule>

<IfModule mod_rewrite.c>
    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Step 9: Update Backup Script

Edit `backup.sh` with your actual database credentials:

```bash
DB_NAME="your_actual_db_name"
DB_USER="your_actual_db_user"
DB_PASS="your_actual_db_password"
```

Make it executable:
```bash
chmod +x backup.sh
```

## Step 10: Security

1. Change default admin password immediately
2. Set `APP_DEBUG=false` in `.env`
3. Restrict access to sensitive files via `.htaccess`
4. Use HTTPS (SSL certificate - free on Hostinger)
5. Regular backups via cron job

## Troubleshooting

### 500 Internal Server Error

- Check `.env` file is present and configured
- Verify file permissions (755 for directories, 644 for files)
- Check PHP error logs in cPanel

### Database Connection Issues

- Verify database credentials in `.env`
- Ensure database user has proper privileges
- Check database host (usually `localhost` on Hostinger)

### Storage Not Writing

- Verify storage folder permissions: `chmod -R 775 storage`
- Check SELinux/AppArmor restrictions
- Ensure `php artisan storage:link` was run

## Performance Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize composer autoloader
composer dump-autoload --optimize
```

## Maintenance Mode

To put the site in maintenance mode:
```bash
php artisan down
```

To bring it back online:
```bash
php artisan up
```

## Support

For Hostinger-specific issues:
- Hostinger Support Portal: https://support.hostinger.com
- Laravel Documentation: https://laravel.com/docs

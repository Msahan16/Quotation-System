# AKM Aluminium Quotation System - Deployment Guide

This guide will help you deploy the quotation system to any hosting provider.

## Pre-Deployment Checklist

### 1. Server Requirements
- PHP 8.1 or higher
- Composer
- SQLite extension (php-sqlite3) OR MySQL/PostgreSQL
- PHP Extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, DOM

### 2. Configure Environment Variables

Copy `.env.example` to `.env` and configure:

```bash
cp .env.example .env
php artisan key:generate
```

**Required Settings:**
```env
APP_NAME="AKM Aluminium"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### 3. Database Configuration

**Option A: SQLite (Simple, recommended for small deployments)**
```env
DB_CONNECTION=sqlite
```
Create the database file:
```bash
touch database/database.sqlite
php artisan migrate
```

**Option B: MySQL**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quotation_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Email Configuration (IMPORTANT)

The system sends email notifications when quotations are created.

**Option A: Gmail SMTP (Recommended for small businesses)**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-16-character-app-password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="AKM Aluminium"
```

**To get Gmail App Password:**
1. Go to https://myaccount.google.com
2. Enable 2-Factor Authentication
3. Go to Security → 2-Step Verification → App passwords
4. Generate a new app password for "Mail"
5. Use the 16-character password in MAIL_PASSWORD

**Option B: SendGrid**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-verified-email@domain.com"
MAIL_FROM_NAME="AKM Aluminium"
```

**Option C: Mailgun**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.mailgun.org
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="AKM Aluminium"
```

**Option D: Hosting Provider SMTP**
Contact your hosting provider for SMTP settings.

### 5. Performance Configuration

```env
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

## Deployment Steps

### Step 1: Upload Files
Upload all project files to your hosting server.

### Step 2: Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Step 3: Configure Environment
```bash
cp .env.example .env
# Edit .env with your settings
php artisan key:generate
```

### Step 4: Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 5: Run Migrations
```bash
php artisan migrate --force
```

### Step 6: Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Point Web Root
Configure your web server to point to the `public` folder.

**Apache (.htaccess already included)**
Point document root to `/public`

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Hosting Provider Specific Instructions

### Hostinger
1. Upload files to `public_html`
2. Move contents of `public` folder to `public_html`
3. Edit `index.php` paths accordingly
4. Use File Manager to set permissions

### cPanel/Shared Hosting
1. Upload files outside `public_html`
2. Create symlink or move public contents
3. Configure using cPanel's PHP Selector

### DigitalOcean/VPS
1. SSH into server
2. Clone repository
3. Install dependencies
4. Configure Nginx/Apache
5. Set up SSL with Let's Encrypt

### Vercel/Netlify
Not recommended - this is a PHP application. Use traditional PHP hosting.

## Troubleshooting

### Email Not Sending
1. Check MAIL_* settings in .env
2. For Gmail, ensure App Password is correct
3. Check if firewall blocks port 587
4. Check `storage/logs/laravel.log` for errors

### 500 Server Error
1. Check file permissions on storage/
2. Run `php artisan config:clear`
3. Check storage/logs/laravel.log

### Slow Performance
1. Run optimization commands (Step 6)
2. Enable OPcache in PHP
3. Use file-based cache instead of database

### PDF Generation Issues
1. Ensure all fonts are installed
2. Check DomPDF cache folder permissions
3. Increase PHP memory_limit if needed

## Security Checklist

- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] Strong APP_KEY generated
- [ ] HTTPS enabled
- [ ] Database credentials secure
- [ ] storage/ not publicly accessible
- [ ] .env file not accessible via web

## Maintenance Commands

```bash
# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan optimize

# Check for issues
php artisan about
```

## Support

For issues with this deployment, check:
1. Laravel documentation: https://laravel.com/docs
2. Storage logs: `storage/logs/laravel.log`

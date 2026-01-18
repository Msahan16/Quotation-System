# PDF Download Fix - Production Deployment Checklist

## Issue Summary
PDF downloads work locally but fail on live server (akmquotation.app) with "Site wasn't available" error.

## Changes Made

### 1. Backend Route Updates (`routes/web.php`)
- ✅ Added CORS headers for cross-origin requests
- ✅ Increased PHP execution timeout to 120 seconds
- ✅ Enhanced error logging with stack traces
- ✅ Optimized DomPDF settings
- ✅ Proper response headers for PDF downloads

### 2. Frontend JavaScript Updates
- ✅ Enhanced `safeDownload()` function in `quotation-view.blade.php`
- ✅ Enhanced `safeDownload()` function in `quotation-list.blade.php`
- ✅ Added loading indicators during PDF generation
- ✅ Added 30-second timeout handling
- ✅ Improved error messages for users
- ✅ Multiple fallback mechanisms

## Production Server Checklist

### ⚠️ CRITICAL: Check These on Your Live Server

#### 1. PHP Configuration
Check your server's `php.ini` file and ensure:
```ini
max_execution_time = 120
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
```

**How to check:**
- SSH into your server
- Run: `php -i | grep max_execution_time`
- Run: `php -i | grep memory_limit`

#### 2. DomPDF Dependencies
Ensure all required packages are installed on production:
```bash
cd /path/to/your/project
composer install --no-dev --optimize-autoloader
```

#### 3. File Permissions
Check that the logo file is accessible:
```bash
ls -la public/AKM.png
# Should show readable permissions (644 or similar)
```

#### 4. Storage Permissions
Ensure Laravel can write to storage:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```
(Replace `www-data` with your web server user if different)

#### 5. Environment Configuration
Check your production `.env` file:
```env
APP_ENV=production
APP_DEBUG=false  # Set to true temporarily to see detailed errors
APP_URL=https://akmquotation.app  # Must match your domain with https://

# Ensure these are set correctly
FILESYSTEM_DISK=local
```

#### 6. HTTPS/SSL Configuration
Since your site uses HTTPS, ensure:
- All assets load over HTTPS
- No mixed content warnings
- SSL certificate is valid

#### 7. Web Server Configuration

**For Apache (.htaccess):**
Add these to your `.htaccess` in the `public` folder:
```apache
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Accept"
</IfModule>

<IfModule mod_fcgid.c>
    FcgidIOTimeout 120
    FcgidIdleTimeout 120
</IfModule>
```

**For Nginx:**
Add to your server block:
```nginx
location ~ \.php$ {
    fastcgi_read_timeout 120;
    fastcgi_send_timeout 120;
}

add_header Access-Control-Allow-Origin *;
add_header Access-Control-Allow-Methods 'GET, POST, OPTIONS';
add_header Access-Control-Allow-Headers 'Content-Type, Accept';
```

#### 8. Clear All Caches
After deploying, run these commands on production:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

#### 9. Check Laravel Logs
Monitor the logs while testing:
```bash
tail -f storage/logs/laravel.log
```

Try downloading a PDF and check for any errors in the log.

#### 10. Browser Console Check
Open browser DevTools (F12) on akmquotation.app and:
1. Go to Console tab
2. Try downloading a PDF
3. Look for any error messages
4. Check the Network tab for the download request
5. See what status code is returned (should be 200)

## Testing Steps

### On Production Server:

1. **Test Direct Route Access:**
   - Visit: `https://akmquotation.app/quotation/1/download` (replace 1 with actual ID)
   - Should download PDF or show error message

2. **Test via Button Click:**
   - Go to quotation list
   - Click download button
   - Watch for loading spinner
   - PDF should download

3. **Check Error Logs:**
   ```bash
   # On server
   tail -100 storage/logs/laravel.log
   ```

## Common Issues & Solutions

### Issue 1: "Site wasn't available"
**Cause:** PHP timeout or memory limit
**Solution:** Increase `max_execution_time` and `memory_limit` in php.ini

### Issue 2: CORS Error
**Cause:** Missing CORS headers
**Solution:** Already fixed in routes/web.php, but ensure web server config allows it

### Issue 3: 500 Internal Server Error
**Cause:** Missing dependencies or permissions
**Solution:** 
- Run `composer install`
- Check file permissions
- Check Laravel logs

### Issue 4: Blank PDF or Corrupted File
**Cause:** Missing fonts or logo file
**Solution:**
- Ensure `public/AKM.png` exists and is readable
- Check DomPDF font cache: `storage/fonts/`

### Issue 5: Mixed Content Warning
**Cause:** Loading resources over HTTP on HTTPS site
**Solution:** Ensure `APP_URL` in `.env` is `https://akmquotation.app`

## Quick Deployment Commands

Run these on your production server after uploading the updated code:

```bash
# Navigate to project directory
cd /path/to/quotation

# Pull latest changes (if using Git)
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM (if applicable)
sudo systemctl restart php8.1-fpm  # Adjust version as needed
```

## Verification

After deployment, verify:
- [ ] PDF downloads work from quotation list
- [ ] PDF downloads work from quotation view page
- [ ] Loading spinner appears during generation
- [ ] Success checkmark appears after download
- [ ] Error messages are user-friendly if something fails
- [ ] No errors in browser console
- [ ] No errors in Laravel logs

## Support

If issues persist after following this checklist:
1. Enable debug mode temporarily: `APP_DEBUG=true` in `.env`
2. Try downloading and check the exact error message
3. Check both Laravel logs and web server error logs
4. Verify all the above checklist items are completed

## Files Modified
- `routes/web.php` - Enhanced PDF download route
- `resources/views/livewire/quotation-view.blade.php` - Improved download function
- `resources/views/livewire/quotation-list.blade.php` - Improved download function

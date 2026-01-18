#!/bin/bash

# PDF Download Fix - Production Deployment Script
# Run this on your production server after uploading the updated files

echo "=========================================="
echo "PDF Download Fix - Deployment Script"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run this script from your Laravel project root.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Found Laravel project${NC}"
echo ""

# Step 1: Clear all caches
echo "Step 1: Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"
echo ""

# Step 2: Install/Update dependencies
echo "Step 2: Installing dependencies..."
composer install --no-dev --optimize-autoloader
echo -e "${GREEN}✓ Dependencies installed${NC}"
echo ""

# Step 3: Set proper permissions
echo "Step 3: Setting file permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"
echo ""

# Step 4: Optimize for production
echo "Step 4: Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo -e "${GREEN}✓ Optimizations complete${NC}"
echo ""

# Step 5: Check critical files
echo "Step 5: Checking critical files..."
if [ -f "public/AKM.png" ]; then
    echo -e "${GREEN}✓ Logo file exists${NC}"
else
    echo -e "${RED}✗ Logo file (public/AKM.png) not found${NC}"
fi

if [ -f ".env" ]; then
    echo -e "${GREEN}✓ .env file exists${NC}"
else
    echo -e "${RED}✗ .env file not found${NC}"
fi
echo ""

# Step 6: Test PDF generation
echo "Step 6: Testing PDF generation..."
echo "Please visit: https://akmquotation.app/test-pdf.php"
echo "to run diagnostic tests"
echo ""

# Step 7: Restart services (if needed)
echo "Step 7: Restarting services..."
echo -e "${YELLOW}Note: You may need to restart PHP-FPM or your web server${NC}"
echo "Common commands:"
echo "  - sudo systemctl restart php8.1-fpm"
echo "  - sudo systemctl restart nginx"
echo "  - sudo systemctl restart apache2"
echo ""

echo "=========================================="
echo -e "${GREEN}Deployment Complete!${NC}"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Visit https://akmquotation.app/test-pdf.php to run diagnostics"
echo "2. Test PDF download from a quotation"
echo "3. Check Laravel logs: tail -f storage/logs/laravel.log"
echo "4. If issues persist, review PDF_DOWNLOAD_FIX.md"
echo ""
echo -e "${YELLOW}Remember to delete test-pdf.php after testing!${NC}"

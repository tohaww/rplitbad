@echo off
REM Deploy script untuk Windows
REM Jalankan: deploy.bat

echo 🚀 Starting deployment...

REM 1. Pull latest changes dari Git
echo 📥 Pulling latest changes...
git pull origin main

REM 2. Install dependencies
echo 📦 Installing dependencies...
composer install --no-dev --optimize-autoloader
call npm install
call npm run build

REM 3. Clear cache
echo 🧹 Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

REM 4. Optimize
echo ⚡ Optimizing...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ✅ Deployment completed!
pause


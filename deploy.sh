#!/bin/bash

# Deploy script untuk Laravel
# Jalankan: bash deploy.sh

echo "🚀 Starting deployment..."

# 1. Pull latest changes dari Git
echo "📥 Pulling latest changes..."
git pull origin main

# 2. Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm install
npm run build

# 3. Run migrations (optional, uncomment jika perlu)
# php artisan migrate --force

# 4. Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 5. Optimize
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment completed!"


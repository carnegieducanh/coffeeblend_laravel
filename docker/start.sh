#!/bin/bash
set -e

cd /var/www/html

echo "=== CoffeeBlend Laravel Startup ==="

# Generate APP_KEY nếu chưa có
if [ -z "$APP_KEY" ]; then
    echo "[INFO] Generating APP_KEY..."
    php artisan key:generate --force
fi

# Xóa cache cũ
echo "[INFO] Clearing old caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Cache lại cho production
echo "[INFO] Caching config/routes/views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Chạy migrations
echo "[INFO] Running database migrations..."
php artisan migrate --force

# Tạo storage symlink (nếu chưa có)
if [ ! -L /var/www/html/public/storage ]; then
    echo "[INFO] Creating storage symlink..."
    php artisan storage:link
fi

echo "[INFO] Starting Apache..."
exec apache2-foreground

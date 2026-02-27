#!/bin/bash

cd /var/www/html

echo "=== CoffeeBlend Laravel Startup ==="
echo "PHP: $(php -r 'echo PHP_VERSION;')"

# Generate APP_KEY neu chua co
if [ -z "$APP_KEY" ]; then
    echo "[INFO] Generating APP_KEY..."
    php artisan key:generate --force
fi

# Xoa cache cu (khong fail neu loi)
echo "[INFO] Clearing caches..."
php artisan config:clear 2>&1 || true
php artisan cache:clear 2>&1 || true
php artisan view:clear 2>&1 || true

# Cache lai cho production
echo "[INFO] Caching config/routes/views..."
php artisan config:cache 2>&1 && echo "  config cached" || echo "  [WARN] config cache failed"
php artisan route:cache 2>&1 && echo "  routes cached" || echo "  [WARN] route cache failed"
php artisan view:cache 2>&1 && echo "  views cached" || echo "  [WARN] view cache failed"

# Chay migrations
echo "[INFO] Running database migrations..."
php artisan migrate --force 2>&1
if [ $? -ne 0 ]; then
    echo "[WARN] Migration failed! Check DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD env vars."
    echo "[WARN] App will start anyway - fix env vars and redeploy."
fi

# Chay seeders neu SEED_DB=true
if [ "${SEED_DB}" = "true" ]; then
    echo "[INFO] Running database seeders (SEED_DB=true)..."
    php artisan db:seed --force 2>&1
    if [ $? -eq 0 ]; then
        echo "[INFO] Seeding completed successfully."
    else
        echo "[WARN] Seeding failed! App will start anyway."
    fi
else
    echo "[INFO] Skipping seeders (set SEED_DB=true to enable)."
fi

# Tao storage symlink
if [ ! -L /var/www/html/public/storage ]; then
    echo "[INFO] Creating storage symlink..."
    php artisan storage:link 2>&1 || true
fi

echo "[INFO] Starting Apache..."
exec apache2-foreground

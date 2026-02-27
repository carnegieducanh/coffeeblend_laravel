FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite headers

# Install system dependencies + Node.js 20 in one step
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    libonig-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (cache layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy package files and install Node dependencies (cache layer)
COPY package.json package-lock.json ./
COPY vite.config.js ./
COPY resources/ resources/
RUN npm ci

# Copy all application files
COPY . .

# Build frontend assets
RUN npm run build

# Complete composer autoloader
RUN composer dump-autoload --optimize --no-dev

# Configure Apache document root to /public
RUN sed -ri \
    -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN sed -i '/<\/VirtualHost>/i \\t<Directory /var/www/html/public>\n\t\tOptions Indexes FollowSymLinks\n\t\tAllowOverride All\n\t\tRequire all granted\n\t</Directory>' \
    /etc/apache2/sites-available/000-default.conf

# PHP production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    printf "upload_max_filesize=64M\npost_max_size=64M\nmemory_limit=256M\n" \
    >> "$PHP_INI_DIR/php.ini"

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy startup script & strip Windows CRLF line endings
COPY docker/start.sh /usr/local/bin/start.sh
RUN sed -i 's/\r$//' /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]

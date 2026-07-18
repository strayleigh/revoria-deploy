FROM php:8.4-apache

# Install system dependencies dan PHP extensions yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql bcmath zip gd opcache \
    && rm -rf /var/lib/apt/lists/*

# Pastikan hanya mpm_prefork yang aktif (sesuai mod_php)
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork rewrite headers

# Set document root ke public/
RUN sed -ri \
    -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri \
    -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .


# Install Composer dependencies (tanpa dev)
RUN composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist


# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Jalankan dengan env Railway yang sudah ter-inject
CMD ["/bin/sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && exec apache2-foreground"]
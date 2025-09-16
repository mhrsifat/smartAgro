# Stage 1: PHP CLI for Composer (dev dependencies)
FROM php:8.2-cli AS deps

WORKDIR /app

# System dependencies
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev nano \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy full Laravel source (needed for dev packages)
COPY . /app

# Install all dependencies including dev packages
RUN composer install --no-interaction --ignore-platform-reqs

# Stage 2: PHP Apache
FROM php:8.2-apache

WORKDIR /var/www/html

# System dependencies + nano
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev nano \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache rewrite
RUN a2enmod rewrite
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Copy vendor from deps stage
COPY --from=deps /app/vendor /var/www/html/vendor

# Copy full Laravel source
COPY . /var/www/html

# Set permissions for storage and cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ENTRYPOINT: artisan commands run at container startup
ENTRYPOINT ["sh", "-c", "php artisan config:clear || true && php artisan storage:link || true && php artisan key:generate --show || true && apache2-foreground"]

# Use www-data user
USER www-data

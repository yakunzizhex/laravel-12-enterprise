FROM php:8.3-fpm-alpine

WORKDIR /app

# Install PHP extensions
RUN apk add --no-cache \
    postgresql-dev \
    redis \
    libzip-dev \
    zip \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

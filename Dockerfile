# ----------------------------
# STEP 1 — Base PHP + Composer + Extensions
# ----------------------------
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip curl && \
    docker-php-ext-install pdo pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ----------------------------
# STEP 2 — Copy project files
# ----------------------------
WORKDIR /app
COPY . .

# ----------------------------
# STEP 3 — Install dependencies
# ----------------------------
RUN composer install 
RUN composer require mongodb/laravel-mongodb

# ----------------------------
# STEP 4 — Expose and start Laravel
# ----------------------------
EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000

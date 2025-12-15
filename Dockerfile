# --------------------------------------
# 1️⃣ Base image: PHP + Nginx + Composer
# --------------------------------------
FROM richarvey/nginx-php-fpm:latest

# --------------------------------------
# 2️⃣ Set working directory
# --------------------------------------
WORKDIR /var/www/html

# --------------------------------------
# 3️⃣ Copy project files into the container
# --------------------------------------
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader


COPY . .

# --------------------------------------
# 4️⃣ Install system dependencies + MongoDB driver
# --------------------------------------
RUN apk add --no-cache autoconf g++ make openssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    composer install --no-dev --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache && \
    chmod -R 775 storage bootstrap/cache

# --------------------------------------
# 5️⃣ Environment variables (for Render)
# --------------------------------------
ENV PORT=10000
EXPOSE 10000

# --------------------------------------
# 6️⃣ Start Laravel app (Render auto-maps to port 10000)
# --------------------------------------
CMD php artisan serve --host=0.0.0.0 --port=${PORT}

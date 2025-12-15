# --------------------------------------
# 1️⃣ Base image: PHP 8.3 + Nginx + Composer
# --------------------------------------
FROM richarvey/nginx-php-fpm:latest

# --------------------------------------
# 2️⃣ Set working directory
# --------------------------------------
WORKDIR /var/www/html

# --------------------------------------
# 3️⃣ Install system dependencies & latest MongoDB driver
# --------------------------------------
RUN apk add --no-cache autoconf g++ make openssl-dev && \
    pecl install mongodb-2.2.0 && \
    echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini && \
    php -m | grep mongodb || true

# --------------------------------------
# 4️⃣ Copy project files into container
# --------------------------------------
COPY . .

# --------------------------------------
# 5️⃣ Install PHP dependencies
# --------------------------------------
RUN composer install --no-dev --optimize-autoloader --prefer-dist && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    chmod -R 775 storage bootstrap/cache

# --------------------------------------
# 6️⃣ Render expects your app to listen on port 10000
# --------------------------------------
ENV PORT=10000
EXPOSE 10000

# --------------------------------------
# 7️⃣ Start Laravel server
# --------------------------------------
CMD php artisan serve --host=0.0.0.0 --port=${PORT}

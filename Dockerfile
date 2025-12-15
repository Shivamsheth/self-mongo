# ----------------------------
# 1. Use a PHP image with FPM
# ----------------------------
FROM richarvey/nginx-php-fpm:latest

# ----------------------------
# 2. Set working directory
# ----------------------------
WORKDIR /var/www/html

# ----------------------------
# 3. Copy project files
# ----------------------------
COPY . .

# ----------------------------
# 4. Install MongoDB driver and dependencies
# ----------------------------
RUN apk add --no-cache autoconf g++ make openssl-dev && \
    pecl install mongodb-2.1.4 && \
    echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini && \
    php -m | grep mongodb && \
    composer install --no-dev --optimize-autoloader && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache



# ----------------------------
# 5. Expose Render port
# ----------------------------
EXPOSE 10000

# ----------------------------
# 6. Start Laravel server
# ----------------------------
CMD php artisan serve --host=0.0.0.0 --port=10000

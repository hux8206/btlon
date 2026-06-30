FROM php:8.2-fpm-alpine

# Cài đặt các extension PHP cần thiết cho Laravel
RUN apk add --no-cache nginx supervisor curl libpng-dev libxml2-dev zip unzip git
RUN docker-php-ext-install pdo_mysql bcmath gd SimpleXML

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf

# Cổng Render yêu cầu chạy (Render mặc định map port 80)
EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
FROM node:22-bookworm-slim AS node

FROM php:8.3-cli-bookworm

COPY --from=node /usr/local/ /usr/local/

RUN apt-get update && apt-get install -y \
    --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_sqlite \
        zip \
        gd \
        intl \
        bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress \
    --no-scripts

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN composer dump-autoload --no-dev --optimize \
    && npm run build \
    && mkdir -p storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache database \
    && npm cache clean --force

USER www-data

EXPOSE 10000

CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]

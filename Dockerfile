FROM php:8.3-fpm-bookworm

ARG APP_DIR=/var/www/html
WORKDIR ${APP_DIR}

RUN apt-get update && apt-get install -y --no-install-recommends \
    chromium \
    fonts-noto-core \
    git \
    poppler-utils \
    unzip \
    libsqlite3-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_sqlite mbstring bcmath opcache sockets \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/entrypoint.sh /usr/local/bin/laravel-entrypoint
RUN chmod +x /usr/local/bin/laravel-entrypoint

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public/design \
    bootstrap/cache \
    database \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 9000

ENTRYPOINT ["laravel-entrypoint"]
CMD ["php-fpm", "-F"]

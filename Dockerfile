FROM php:8.3.11-fpm-alpine3.20

LABEL org.opencontainers.image.source=https://github.com/typesetterio/typesetter-cli
LABEL org.opencontainers.image.description="TypesetterIO creates PDFs from Markdown in a snap!"
LABEL org.opencontainers.image.licenses=MIT

WORKDIR /var/www/html

RUN apk add --no-cache $PHPIZE_DEPS \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader --no-dev

ENTRYPOINT ["php", "typesetter"]

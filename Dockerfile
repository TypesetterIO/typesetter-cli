FROM php:8.5.11-cli-alpine3.24

LABEL org.opencontainers.image.source=https://github.com/TypesetterIO/typesetter-cli
LABEL org.opencontainers.image.description="TypesetterIO creates PDFs from Markdown in a snap!"
LABEL org.opencontainers.image.licenses=MIT

WORKDIR /app

# hadolint ignore=DL3018,SC2086
RUN apk add --no-cache \
        freetype \
        libpng \
        libjpeg-turbo \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && apk del .build-deps

COPY --from=composer:2.10.3 /usr/bin/composer /usr/bin/composer

COPY . .

ARG VERSION=dev-main

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_ROOT_VERSION=$VERSION
RUN composer install --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader --no-dev

WORKDIR /runtime

ENTRYPOINT ["php", "/app/typesetter"]

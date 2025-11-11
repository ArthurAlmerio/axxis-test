FROM php:8.4-fpm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /app/

RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libpq-dev \
    libsodium-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        intl \
        pdo \
        pdo_pgsql \
        pgsql \
        opcache \
        sodium \
        mbstring \
        pcntl \
        soap \
        zip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g yarn \
    && npm cache clean --force \
    && curl -sS https://get.symfony.com/cli/installer | bash && \
        mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

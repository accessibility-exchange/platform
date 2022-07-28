FROM php:8.1-fpm

RUN apt-get update

RUN apt-get install -y nginx git unzip supervisor rsync nano wget curl

RUN apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxml2-dev \
    libxml2 \
    libbz2-dev \
    libonig-dev \
    libpng-dev \
    libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath bz2 zip soap
RUN docker-php-ext-install opcache

RUN apt-get install -y libmagickwand-dev --no-install-recommends

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash

RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN printf "\n" | pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

COPY .deploy/accessibility-app/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
COPY .deploy/accessibility-app/nginx.conf /etc/nginx/nginx.conf
COPY .deploy/accessibility-app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENV APP_HOME /app
RUN mkdir $APP_HOME
WORKDIR $APP_HOME
COPY . /app

RUN composer install
RUN . /root/.nvm/nvm.sh && \
    . /root/.nvm/bash_completion && \
    cd /app && \
    nvm install 16

RUN chown www-data:root /app/public/ -R

ENTRYPOINT /app/.deploy/accessibility-app/entrypoint.sh

FROM php:8.1-fpm

ENV APP_DIR /app
ENV KUBE_DIR .kube/app

ARG CIPHERSWEET_KEY

RUN apt-get update

RUN apt-get install -y \
    curl \
    default-mysql-client \
    git \
    htop \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxml2-dev \
    libxml2 \
    libbz2-dev \
    libonig-dev \
    libpng-dev \
    libzip-dev \
    nano \
    nginx \
    rsync \
    supervisor \
    unzip \
    wget \
    s3cmd

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath bz2 zip soap
RUN docker-php-ext-install opcache

RUN apt-get install -y libmagickwand-dev --no-install-recommends

RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN printf "\n" | pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV NODE_VERSION 16
RUN curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt install -y nodejs \
    && npm install -g npm

COPY $KUBE_DIR/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
COPY $KUBE_DIR/nginx.conf /etc/nginx/nginx.conf
COPY $KUBE_DIR/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir $APP_DIR
WORKDIR $APP_DIR
COPY . $APP_DIR

RUN composer install

RUN npm install

RUN chown www-data:root $APP_DIR/public/ -R

ENTRYPOINT $APP_DIR/$KUBE_DIR/entrypoint.sh

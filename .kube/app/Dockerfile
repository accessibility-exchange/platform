FROM php:8.1-fpm

ENV PHP_VERSION 8.1
ENV NODE_VERSION 16
ENV NVM_VERSION 0.39.3
ENV NVM_DIR /root/.nvm

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

RUN wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v$NVM_VERSION/install.sh | bash
RUN . "$NVM_DIR/nvm.sh" && nvm install $NODE_VERSION
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

COPY $KUBE_DIR/php.ini /etc/php/$PHP_VERSION/cli/conf.d/99-sail.ini
COPY $KUBE_DIR/nginx.conf /etc/nginx/nginx.conf
COPY $KUBE_DIR/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir $APP_DIR
WORKDIR $APP_DIR
COPY . $APP_DIR

RUN composer install

RUN . "$NVM_DIR/nvm.sh" && nvm use $NODE_VERSION && npm ci

RUN chown www-data:root $APP_DIR/public/ -R

ENTRYPOINT $APP_DIR/$KUBE_DIR/entrypoint.sh

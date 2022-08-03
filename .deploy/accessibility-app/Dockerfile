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

RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN printf "\n" | pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV NVM_DIR /root/.nvm
ENV NODE_VERSION 16

RUN curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash

RUN . $NVM_DIR/nvm.sh && \
    nvm install $NODE_VERSION && \
    nvm alias default $NODE_VERSION && \
    nvm use default

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/v$NODE_VERSION/bin:$PATH

COPY .deploy/accessibility-app/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
COPY .deploy/accessibility-app/nginx.conf /etc/nginx/nginx.conf
COPY .deploy/accessibility-app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENV APP_HOME /app
RUN mkdir $APP_HOME
WORKDIR $APP_HOME
COPY . $APP_HOME

RUN composer install
RUN npm install

RUN chown www-data:root $APP_HOME/public/ -R

ENTRYPOINT $APP_HOME/.deploy/accessibility-app/entrypoint.sh

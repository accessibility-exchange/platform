FROM php:8.1-fpm

ARG APP_HOME
ENV APP_HOME $APP_HOME
ARG TIMEZONE 
ARG WWWGROUP
ARG WWWUSER

RUN usermod -u ${WWWUSER} www-data && \
    groupmod -g ${WWWGROUP} www-data

RUN rm -f /etc/localtime && \
    ln -s /usr/share/zoneinfo/${TIMEZONE} /etc/localtime

RUN apt-get update

RUN apt-get install -y \
    curl \
    gettext \
    git \
    nano \
    nginx \
    rsync \
    supervisor \
    unzip \
    wget 

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

RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN printf "\n" | pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

COPY . $APP_HOME
WORKDIR $APP_HOME

RUN composer install

SHELL ["/bin/bash", "--login", "-c"]

ARG NODE_VERSION
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
RUN nvm install $NODE_VERSION && \
    nvm use $NODE_VERSION
RUN npm install

SHELL ["/bin/sh", "-c"]


ARG PERMANENT_FILE_STORAGE
ENV PERMANENT_FILE_STORAGE $PERMANENT_FILE_STORAGE
ENV DOLLAR '$'
COPY .local-deploy/accessibility-app/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
RUN envsubst < ${APP_HOME}/.local-deploy/accessibility-app/timezone.ini > /etc/php/8.1/cli/conf.d/timezone.ini
COPY .local-deploy/accessibility-app/etc /etc 
RUN envsubst < /etc/nginx/snippets/laravel.conf.template > /etc/nginx/snippets/laravel.conf
RUN envsubst < ${APP_HOME}/.local-deploy/accessibility-app/entrypoint.sh > ${APP_HOME}/entrypoint.sh
RUN chmod +x ${APP_HOME}/entrypoint.sh
COPY .local-deploy/accessibility-app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chown -R www-data:www-data ${APP_HOME}

ENTRYPOINT ${APP_HOME}/entrypoint.sh

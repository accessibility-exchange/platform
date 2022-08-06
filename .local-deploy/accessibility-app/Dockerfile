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

# libssl-dev required for swoole development only 
RUN apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxml2-dev \
    libxml2 \
    libbz2-dev \
    libonig-dev \
    libpng-dev \
    libssl-dev \ 
    libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install \
    bcmath \
    bz2 \
    exif \
    mbstring \
    pcntl \
    pdo_mysql \
    opcache \
    soap \
    sockets \
    zip 

RUN apt-get install -y libmagickwand-dev --no-install-recommends

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# pcov,xdebug,igbinary,msgpack development only
RUN pecl install imagick pcov xdebug igbinary msgpack
RUN docker-php-ext-enable imagick pcov xdebug igbinary msgpack

# Development only
RUN yes | pecl install swoole 
RUN docker-php-ext-enable swoole

RUN printf "\n" | pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

COPY . $APP_HOME
WORKDIR $APP_HOME

RUN composer install

ARG NODE_VERSION
RUN curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt install -y nodejs \
    && npm install -g npm 
RUN npm install 
RUN npm run build

ARG APP_ENV
ARG PERMANENT_FILE_STORAGE
ENV APP_ENV $APP_ENV
ENV PERMANENT_FILE_STORAGE $PERMANENT_FILE_STORAGE
ENV DOLLAR '$'
COPY .local-deploy/accessibility-app/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
RUN envsubst < ${APP_HOME}/.local-deploy/accessibility-app/timezone.ini > /etc/php/8.1/cli/conf.d/timezone.ini
COPY .local-deploy/accessibility-app/etc /etc 
RUN envsubst < /etc/nginx/snippets/laravel.conf.template > /etc/nginx/snippets/laravel.conf
RUN envsubst < ${APP_HOME}/.local-deploy/accessibility-app/entrypoint.sh > ${APP_HOME}/entrypoint.sh
RUN chmod +x ${APP_HOME}/entrypoint.sh
COPY .local-deploy/accessibility-app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN envsubst < .local-deploy/accessibility-app/supervisord-local.conf > /etc/supervisor/conf.d/supervisord-local.conf

RUN apt -y autoremove \
    && apt clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN chown -R www-data:www-data ${APP_HOME}/public ${APP_HOME}/storage

ENTRYPOINT ${APP_HOME}/entrypoint.sh

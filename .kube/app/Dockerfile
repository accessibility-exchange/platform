FROM php:8.4-fpm

ENV PHP_VERSION 8.4
ENV NODE_VERSION 22.11.0
ENV NVM_VERSION 0.40.1
ENV NVM_DIR /var/www/.nvm

ENV APP_DIR /app
ENV KUBE_DIR .kube/app

ARG CIPHERSWEET_KEY

ARG USER_ID
# If argument passed then swap out numerical id for www-data user to allow for mirroring volumes from local to host set via .env file variable WWWUSER and is auto-generate when starting nix-shell for the first time
RUN [ -z "$USER_ID" ] || usermod -u $USER_ID www-data

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
RUN docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath bz2 zip soap intl opcache

RUN apt-get install -y \
    libmagickwand-dev \
    libmagickcore-dev \
    --no-install-recommends

# START BUILDING IMAGICK FROM SOURCE - this can be removed once pecl fixes issues installing with php8.4
# TODO - this can be removed when https://github.com/Imagick/imagick/issues/698 is resolved
RUN apt-get install -y \
    $PHPIZE_DEPS \
    libtool

RUN mkdir -p /tmp/imagick
WORKDIR /tmp/imagick
# upgrade version when bumping PHP version
RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/refs/tags/3.7.0.tar.gz
RUN tar --strip-components=1 -xf /tmp/imagick.tar.gz
RUN phpize
RUN ./configure
RUN make
RUN make install
WORKDIR /
RUN rm -rf /tmp/imagick
# END BUILDING IMAGICK FROM SOURCE

# RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN printf "\n" | pecl install apcu
RUN docker-php-ext-enable apcu

RUN pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

# TODO - this can be removed when https://github.com/Imagick/imagick/issues/698 is resolved
RUN apt-get remove -y \
    $PHPIZE_DEPS \
    libtool

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p $NVM_DIR
RUN wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v$NVM_VERSION/install.sh | bash
RUN . "$NVM_DIR/nvm.sh" && nvm install $NODE_VERSION
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

COPY $KUBE_DIR/php.ini /etc/php/$PHP_VERSION/cli/conf.d/99-sail.ini
# explicitly setup production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY $KUBE_DIR/nginx.conf /etc/nginx/nginx.conf
COPY $KUBE_DIR/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR $APP_DIR
# Change ownership as the files are copied rather than having to change after
COPY --chown=www-data:www-data . $APP_DIR
# Prevents error on npm ci when directory doesn not yet exist
RUN mkdir -p $APP_DIR/node_modules $APP_DIR/vendor
# Set proper ownership to prevent errors when installed with php-fpm user
RUN chown www-data:www-data $NVM_DIR/nvm.sh $APP_DIR/node_modules /var/www $APP_DIR/vendor

# run installers as the user that PHP runs under to keep ownership aligned
USER www-data
RUN composer install
RUN . "$NVM_DIR/nvm.sh" && nvm use $NODE_VERSION && npm ci
USER root

ENTRYPOINT $APP_DIR/$KUBE_DIR/entrypoint.sh
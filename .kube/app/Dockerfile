FROM php:8.4-fpm

ENV PHP_VERSION 8.4
# TODO - IMAGICK_VERSION can be removed once pecl fixes issues installing with php8.4
ENV IMAGICK_VERSION 3.7.0 # This should be bumped to the latest version, especially when PHP version is bumped and can be removed when https://github.com/accessibility-exchange/platform/issues/2425 is resolved
ENV NODE_VERSION 22.11.0
ENV NVM_VERSION 0.40.1
ENV NVM_DIR /var/www/.nvm

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
RUN docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath bz2 zip soap intl opcache

RUN apt-get install -y \
    libmagickwand-dev \
    libmagickcore-dev \
    --no-install-recommends

# START BUILDING IMAGICK FROM SOURCE - this can be removed once pecl fixes issues installing with php8.4
# see issue https://github.com/accessibility-exchange/platform/issues/2425
# TODO - this can be removed once pecl fixes issues installing with php8.4
RUN apt-get install -y \
    $PHPIZE_DEPS \
    libtool

RUN mkdir -p /tmp/imagick
WORKDIR /tmp/imagick
RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/tags/${IMAGICK_VERSION}.tar.gz
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

# TODO - this can be removed once pecl fixes issues installing with php8.4
RUN apt-get remove -y \
    $PHPIZE_DEPS \
    libtool

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p $NVM_DIR
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

RUN chown -R www-data:root $APP_DIR/public/ $NVM_DIR

ENTRYPOINT $APP_DIR/$KUBE_DIR/entrypoint.sh

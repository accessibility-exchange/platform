#!/bin/sh

set -e

if [ -f /usr/local/etc/php/php.ini-development ]; then
    mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/conf.d/php.ini
fi

# create self signed cert so that site can be served on HTTPS
openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -subj "$SSL_SUBJECT" \
    -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt

# mirror developers user id so that they can edit live files in the docker
if [ -n "$USER_ID" ]; then
  usermod -u $USER_ID www-data
fi

mkdir -p $FILES_PATH
mkdir -p $CACHE_PATH

## fix permissions before syncing to existing storage and cache https://github.com/accessibility-exchange/platform/issues/1226
chown -R www-data:www-data /app/storage /app/bootstrap/cache $FILES_PATH $CACHE_PATH

## sync files from container storage to permanent storage then remove container storage
rsync -a /app/storage/ $FILES_PATH
rm -rf /app/storage

## sync files from container cache to permanent storage then remove container cache
rsync -a /app/bootstrap/cache/ $CACHE_PATH
rm -rf /app/bootstrap/cache

## create symlinks from permanent storage & cache to application directory folders
ln -s $FILES_PATH /app/storage
ln -s $CACHE_PATH /app/bootstrap/cache

## fix permissions after syncing to existing storage and cache https://github.com/accessibility-exchange/platform/issues/1236
chown -R www-data:www-data $FILES_PATH $CACHE_PATH

# make sure to test mysql connection before running the php artisan commands
while ! mysqladmin ping -h$DB_HOST -uroot -p$DB_PASSWORD --silent; do
    sleep 1
done

# run before global so that storage is linked
php artisan deploy:local

if [ ! -f $FILES_PATH/../deploy.lock ]
then

  touch $FILES_PATH/../deploy.lock

  php artisan deploy:global

fi

rm -rf $FILES_PATH/../deploy.lock

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

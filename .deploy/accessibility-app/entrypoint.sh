#!/bin/sh

set -e

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

while ! mysqladmin ping -h$DB_HOST -uroot -p$DB_PASSWORD --silent; do
    sleep 1
done

if [ ! -f $FILES_PATH/../deploy.lock ]
then

  touch $FILES_PATH/../deploy.lock

  php artisan deploy:global

fi

rm -rf $FILES_PATH/../deploy.lock

php artisan deploy:local

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

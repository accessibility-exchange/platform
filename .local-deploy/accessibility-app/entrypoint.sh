#!/bin/sh

set -e

# TODO permanent remove cache lines once testing on per/pod caching is tested
if [ -f /usr/local/etc/php/php.ini-development ]; then
    mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/conf.d/php.ini
fi

# mirror developers user id so that they can edit live files in the docker
if [ -n "$USER_ID" ]; then
  usermod -u $USER_ID www-data
  chown -R www-data:root /app
fi

mkdir -p $FILES_PATH
# mkdir -p $CACHE_PATH removed per https://github.com/accessibility-exchange/platform/issues/1596

## fix permissions before syncing to existing storage and cache https://github.com/accessibility-exchange/platform/issues/1226
chown -R www-data:root /app/storage /app/bootstrap/cache $FILES_PATH $VIEW_COMPILED_PATH # $CACHE_PATH removed per https://github.com/accessibility-exchange/platform/issues/1596

## sync files from container storage to permanent storage then remove container storage
rsync -a /app/storage/ $FILES_PATH
rm -rf /app/storage

## sync files from container cache to permanent storage then remove container cache
## removed syncing to shared/permenant storage https://github.com/accessibility-exchange/platform/issues/1596
# rsync -a /app/bootstrap/cache/ $CACHE_PATH
# rm -rf /app/bootstrap/cache

## create symlinks from permanent storage & cache to application directory folders
ln -s $FILES_PATH /app/storage
## removed linked to shared/permenant storage https://github.com/accessibility-exchange/platform/issues/1596
# ln -s $CACHE_PATH /app/bootstrap/cache

# make sure to test mysql connection before running the php artisan commands
while ! mysqladmin ping -h$DB_HOST -uroot -p$DB_PASSWORD --silent; do
    sleep 1
done

php artisan deploy:local # run before global so that storage is linked https://github.com/accessibility-exchange/platform/issues/1357

flock -n -E 0 /opt/data -c "php artisan deploy:global" # run exclusively on a single instance at once

## fix permissions after syncing to existing storage and cache https://github.com/accessibility-exchange/platform/issues/1236
chown -R www-data:root /app/bootstrap/cache $FILES_PATH # $CACHE_PATH removed per and added path to cache in the pod https://github.com/accessibility-exchange/platform/issues/1596

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

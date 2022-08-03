#!/bin/sh

set -e

mkdir -p $FILES_PATH

rsync -a /app/storage/ $FILES_PATH/
rm -rf /app/storage

ln -s $FILES_PATH /app/storage

if [ ! -f $FILES_PATH/../deploy.lock ]
then

  touch $FILES_PATH/../deploy.lock

  php artisan migrate --step
  php artisan google-fonts:fetch
  
fi

rm -rf $FILES_PATH/../deploy.lock

php artisan storage:link
php artisan cache:clear
php artisan view:clear
php artisan route:cache
php artisan config:cache

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

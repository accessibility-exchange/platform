#!/bin/sh

set -e

mkdir -p $FILES_PATH

rsync -a /app/storage/ $FILES_PATH/
rm -rf /app/storage

ln -s $FILES_PATH /app/storage
chown www-data:root /opt/data/storage/ -R

if [ ! -f $FILES_PATH/../deploy.lock ]
then

  touch $FILES_PATH/../deploy.lock

  if [ "$APP_ENV" == "production" ]
  then
    php artisan migrate --step --force
  else
    php artisan migrate:fresh --step 
    php artisan db:seed DevSeeder
  fi

  php artisan google-fonts:fetch
  php artisan storage:link
  php artisan cache:clear
  php artisan view:clear
  php artisan route:cache
  php artisan config:cache
  
fi

rm -rf $FILES_PATH/../deploy.lock

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

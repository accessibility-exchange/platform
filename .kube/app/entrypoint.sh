#!/usr/bin/env bash

set -e

mkdir -p $FILES_PATH

# Removing in favor of changing ownership in the syncing stream
# chown -R www-data:www-data /app/storage /app/bootstrap/cache $FILES_PATH $VIEW_COMPILED_PATH

## sync files from container storage to permanent storage then remove container storage
rsync -a --chown=www-data:www-data /app/storage/ $FILES_PATH
rm -rf /app/storage

## create symlinks from permanent storage & cache to application directory folders
ln -s $FILES_PATH /app/storage

php artisan deploy:local

flock -n -E 0 /opt/data -c "php artisan deploy:global" # run exclusively on a single instance at once

# Run data migrations that aren't included in the DB migrations
flock -n -E 0 /opt/data -c "php artisan app:migrate-settings-data" # run exclusively on a single instance at once

# Generate the robots.txt and sitemap.xml files
php artisan seo:generate

# Add in symlink to ownership update, add in public folder where assets are compiled
chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public/build /app/public/*.{xml,txt} $FILES_PATH

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

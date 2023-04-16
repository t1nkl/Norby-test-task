#!/bin/bash

sleep 5

/usr/local/bin/composer install -n --prefer-dist -d /code

if [ ! -f /code/.env ]; then
  echo "Init dev env"
  cp /code/.env.example /code/.env
  php /code/artisan key:generate
fi

# Permissions
chmod -R 0777 /code/bootstrap
chmod -R 0777 /code/storage

# Migrate
php /code/artisan migrate --force

php /code/artisan test

php /code/artisan octane:start --host=0.0.0.0 --port=9000

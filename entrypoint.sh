#!/bin/sh

php artisan optimize:clear

php artisan storage:link

php artisan migrate --force
php artisan db:seed --force

exec "$@"

#!/bin/sh

php artisan storage:link
php artisan migrate --force
php artisan db:seed --force

exec "$@"

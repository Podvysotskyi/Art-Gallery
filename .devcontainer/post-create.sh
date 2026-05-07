#!/usr/bin/env bash

set -euo pipefail

composer install
npm install

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

php artisan storage:link || true
php artisan migrate --force

#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

composer install --no-interaction

if ! grep -q '^APP_KEY=base64' .env; then
    php artisan key:generate
fi

php artisan storage:link 2>/dev/null || true
php artisan migrate --force
php artisan db:seed --force

exec php artisan serve --host=0.0.0.0 --port=8000

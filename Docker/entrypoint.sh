#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-suggest --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Copying .env.example to .env"
    cp .env.example .env
else
    echo "env file already exists"
fi

php artisan migrate:fresh --force
php artisan db:seed --force
php artisan key:generate
php aritsan cache:clear
php artisan config:clear
php artisan route:clear

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"

#!/bin/sh
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --message 'A aplicação está sendo atualizada. Por favor tente novamente em um minuto.') || true
    # Update codebase
    git fetch origin master
    git reset --hard origin/master

    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader

    # Migrate database
    php artisan migrate --force

    # Note: If you're using queue workers, this is the place to restart them.
    # ...

    # Clear cache
    php artisan optimize

# Exit maintenance mode
php artisan up

echo "Application deployed!"

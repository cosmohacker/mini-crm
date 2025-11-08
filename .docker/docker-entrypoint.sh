#!/bin/bash
set -e

# Install Laravel if not already present
if [ ! -f "/var/www/html/composer.json" ]; then
    cd /var/www/html
    composer create-project laravel/laravel . "^12.0"
    chown -R www-data:www-data /var/www/html
fi

exec "$@"
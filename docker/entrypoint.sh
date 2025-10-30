#!/bin/sh
set -e

# Fix permissions for storage and bootstrap/cache directories (run as root)
if [ -d "/var/www/html/storage" ]; then
    chown -R novarosamt:www-data /var/www/html/storage
    chmod -R 775 /var/www/html/storage
fi

if [ -d "/var/www/html/bootstrap/cache" ]; then
    chown -R novarosamt:www-data /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/bootstrap/cache
fi

# Execute the original command (php-fpm will handle user switching)
exec "$@"


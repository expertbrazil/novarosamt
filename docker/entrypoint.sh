#!/bin/sh
set -e

# Fix permissions for storage and bootstrap/cache directories (run as root)
if [ -d "/var/www/html/storage" ]; then
    chown -R novarosamt:www-data /var/www/html/storage
    find /var/www/html/storage -type d -exec chmod 775 {} \;
    find /var/www/html/storage -type f -exec chmod 664 {} \;
fi

if [ -d "/var/www/html/bootstrap/cache" ]; then
    chown -R novarosamt:www-data /var/www/html/bootstrap/cache
    find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;
    find /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \;
fi

# Garantir que o diretório banners existe
mkdir -p /var/www/html/storage/app/public/banners
chown -R novarosamt:www-data /var/www/html/storage/app/public/banners
chmod 775 /var/www/html/storage/app/public/banners

# Execute the original command (php-fpm will handle user switching)
exec "$@"


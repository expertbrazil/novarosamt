#!/bin/sh
set -e

# Fix permissions for ALL project directories (run as root)
# This ensures the expert user (UID 1000) can always save/edit files

echo "=========================================="
echo "Ajustando permissões de TODO o projeto..."
echo "=========================================="

# Ensure novarosamt user exists with UID 1000 (matching expert user on host)
if ! id -u novarosamt >/dev/null 2>&1; then
    useradd -G www-data,root -u 1000 -d /home/novarosamt -s /bin/bash novarosamt || \
    usermod -u 1000 -g www-data novarosamt 2>/dev/null || true
fi

# Fix ownership of ENTIRE project to novarosamt:www-data (UID 1000)
echo "Ajustando ownership de todos os arquivos..."
chown -R novarosamt:www-data /var/www/html 2>/dev/null || true

# Fix directory permissions (775 = rwxrwxr-x) - allows expert user to write
echo "Ajustando permissões de diretórios..."
find /var/www/html -type d -exec chmod 775 {} \; 2>/dev/null || true

# Fix file permissions (664 = rw-rw-r--) - allows expert user to write
echo "Ajustando permissões de arquivos..."
find /var/www/html -type f -exec chmod 664 {} \; 2>/dev/null || true

# Ensure important directories exist and have correct permissions
echo "Criando diretórios importantes..."
mkdir -p /var/www/html/storage/app/public/banners
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/app/Jobs
mkdir -p /var/www/html/app/Http/Controllers
mkdir -p /var/www/html/app/Models
mkdir -p /var/www/html/app/Services

# Extra permissions for specific directories
chmod 775 /var/www/html/storage/app/public/banners
chmod 775 /var/www/html/storage/logs
chmod 775 /var/www/html/bootstrap/cache
chmod 775 /var/www/html/app/Jobs
chmod 775 /var/www/html/app/Http/Controllers
chmod 775 /var/www/html/app/Models
chmod 775 /var/www/html/app/Services

# Ensure storage and bootstrap/cache are writable
chown -R novarosamt:www-data /var/www/html/storage
chown -R novarosamt:www-data /var/www/html/bootstrap/cache
chown -R novarosamt:www-data /var/www/html/app
chown -R novarosamt:www-data /var/www/html/database
chown -R novarosamt:www-data /var/www/html/routes
chown -R novarosamt:www-data /var/www/html/resources
chown -R novarosamt:www-data /var/www/html/config

echo "=========================================="
echo "Permissões ajustadas com sucesso!"
echo "=========================================="

# Execute the original command (php-fpm will handle user switching)
exec "$@"


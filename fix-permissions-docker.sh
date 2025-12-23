#!/bin/bash

# Script para ajustar permissões dentro do container Docker
# Execute com: docker-compose exec app bash /var/www/html/fix-permissions-docker.sh

echo "Ajustando permissões dentro do container Docker..."

# Ajustar ownership de todos os arquivos em storage e bootstrap/cache
chown -R novarosamt:www-data /var/www/html/storage
chown -R novarosamt:www-data /var/www/html/bootstrap/cache

# Ajustar permissões de diretórios
find /var/www/html/storage -type d -exec chmod 775 {} \;
find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;

# Ajustar permissões de arquivos
find /var/www/html/storage -type f -exec chmod 664 {} \;
find /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \;

# Garantir que o diretório banners existe e tem permissões corretas
mkdir -p /var/www/html/storage/app/public/banners
chown -R novarosamt:www-data /var/www/html/storage/app/public/banners
chmod 775 /var/www/html/storage/app/public/banners

# Ajustar permissões do diretório app (especialmente Jobs, Models, Controllers)
if [ -d "/var/www/html/app" ]; then
    chown -R novarosamt:www-data /var/www/html/app
    find /var/www/html/app -type d -exec chmod 775 {} \;
    find /var/www/html/app -type f -exec chmod 664 {} \;
fi

# Garantir que o diretório Jobs existe e tem permissões corretas
mkdir -p /var/www/html/app/Jobs
chown -R novarosamt:www-data /var/www/html/app/Jobs
chmod 775 /var/www/html/app/Jobs

echo "Permissões ajustadas com sucesso!"


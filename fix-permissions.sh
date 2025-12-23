#!/bin/bash

# Script para ajustar permissões dos diretórios do Laravel
# Execute com: sudo ./fix-permissions.sh

echo "Ajustando permissões dos diretórios do Laravel..."

# Definir usuário e grupo
USER="expert"
GROUP="www-data"

# Ajustar ownership
echo "Ajustando ownership..."
chown -R $USER:$GROUP storage bootstrap/cache

# Ajustar permissões de diretórios
echo "Ajustando permissões de diretórios..."
find storage -type d -exec chmod 775 {} \;
find bootstrap/cache -type d -exec chmod 775 {} \;

# Ajustar permissões de arquivos
echo "Ajustando permissões de arquivos..."
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;

# Garantir que o diretório banners existe e tem permissões corretas
mkdir -p storage/app/public/banners
chown -R $USER:$GROUP storage/app/public/banners
chmod 775 storage/app/public/banners

echo "Permissões ajustadas com sucesso!"
echo ""
echo "Verificando permissões principais:"
ls -la storage/ | head -5
ls -la bootstrap/cache/ | head -5
ls -la storage/app/public/banners/ 2>/dev/null | head -5

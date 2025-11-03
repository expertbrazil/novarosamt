#!/bin/bash

# Script para corrigir permissões dos diretórios storage e bootstrap/cache
# Execute este script dentro do container ou no host

echo "Corrigindo permissões..."

# Criar diretórios se não existirem
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Ajustar permissões
chmod -R 775 storage bootstrap/cache
chown -R $(whoami):www-data storage bootstrap/cache 2>/dev/null || echo "Aviso: não foi possível alterar o proprietário (execute como root se necessário)"

echo "Permissões corrigidas!"
echo ""
echo "Para aplicar no container Docker, execute:"
echo "docker-compose exec app bash /var/www/html/fix-permissions.sh"





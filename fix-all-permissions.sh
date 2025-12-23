#!/bin/bash

# Script para corrigir permissões de TODO o projeto
# Execute com: ./fix-all-permissions.sh

echo "=========================================="
echo "Corrigindo permissões de TODO o projeto"
echo "=========================================="

# Corrigir permissões dentro do container Docker
echo "1. Corrigindo permissões dentro do container Docker..."
docker-compose exec -u root app bash -c "
    echo 'Ajustando ownership de todos os arquivos...'
    chown -R novarosamt:www-data /var/www/html
    
    echo 'Ajustando permissões de diretórios...'
    find /var/www/html -type d -exec chmod 775 {} \;
    
    echo 'Ajustando permissões de arquivos...'
    find /var/www/html -type f -exec chmod 664 {} \;
    
    echo 'Garantindo que diretórios importantes existem...'
    mkdir -p /var/www/html/app/Jobs
    mkdir -p /var/www/html/storage/app/public/banners
    mkdir -p /var/www/html/storage/logs
    mkdir -p /var/www/html/bootstrap/cache
    
    echo 'Ajustando permissões de diretórios específicos...'
    chmod 775 /var/www/html/app/Jobs
    chmod 775 /var/www/html/storage/app/public/banners
    chmod 775 /var/www/html/storage/logs
    chmod 775 /var/www/html/bootstrap/cache
    
    echo 'Permissões corrigidas dentro do container!'
"

# Corrigir permissões no host (se necessário)
echo ""
echo "2. Verificando permissões no host..."
if [ -d "app" ]; then
    echo "Ajustando permissões no host..."
    chmod -R 775 app/ database/ routes/ resources/ config/ 2>/dev/null || echo "Alguns arquivos podem precisar de sudo"
fi

echo ""
echo "=========================================="
echo "Permissões corrigidas com sucesso!"
echo "=========================================="
echo ""
echo "Diretórios principais verificados:"
echo "  - app/ (Controllers, Models, Jobs, Services)"
echo "  - database/ (migrations, seeders)"
echo "  - routes/"
echo "  - resources/ (views)"
echo "  - config/"
echo "  - storage/"
echo "  - bootstrap/cache/"
echo ""
echo "Agora você pode criar, editar e deletar arquivos sem problemas!"


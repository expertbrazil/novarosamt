#!/bin/bash

# Script de Deploy - Nova Rosa MT
# Uso: ./deploy.sh [ambiente]
# Exemplo: ./deploy.sh production

set -e

ENVIRONMENT=${1:-production}

echo "🚀 Iniciando deploy para ambiente: $ENVIRONMENT"
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para exibir mensagens
info() {
    echo -e "${GREEN}✓${NC} $1"
}

warn() {
    echo -e "${YELLOW}⚠${NC} $1"
}

error() {
    echo -e "${RED}✗${NC} $1"
    exit 1
}

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    error "Arquivo artisan não encontrado. Execute este script na raiz do projeto Laravel."
fi

info "Verificando dependências..."

# Verificar Node.js
if ! command -v node &> /dev/null; then
    warn "Node.js não encontrado. Pulando compilação de assets."
    SKIP_ASSETS=true
else
    info "Node.js encontrado: $(node --version)"
fi

# Verificar Composer
if ! command -v composer &> /dev/null; then
    error "Composer não encontrado. Instale o Composer primeiro."
fi

info "Composer encontrado: $(composer --version | head -n 1)"

# Verificar PHP
if ! command -v php &> /dev/null; then
    error "PHP não encontrado."
fi

info "PHP encontrado: $(php --version | head -n 1)"

echo ""
info "Limpando caches antigos..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""
info "Compilando assets..."
if [ "$SKIP_ASSETS" != true ]; then
    npm ci --production
    npm run build
    info "Assets compilados com sucesso!"
else
    warn "Pulando compilação de assets (Node.js não encontrado)"
fi

echo ""
info "Instalando dependências do Composer..."
composer install --optimize-autoloader --no-dev --no-interaction

echo ""
info "Otimizando autoloader..."
composer dump-autoload --optimize

echo ""
info "Executando migrações..."
php artisan migrate --force

echo ""
warn "Deseja popular estados e municípios? (s/N)"
read -r response
if [[ "$response" =~ ^([sS][iI][mM]|[sS])$ ]]; then
    php artisan seed:estados-municipios --force
fi

echo ""
info "Cacheando configurações para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
info "Verificando link do storage..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    info "Link do storage criado!"
else
    info "Link do storage já existe"
fi

echo ""
info "Verificando permissões..."
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    info "Permissões OK"
else
    warn "Verifique as permissões de storage/ e bootstrap/cache/"
    warn "Execute: chmod -R 755 storage bootstrap/cache"
fi

echo ""
info "Deploy concluído com sucesso! 🎉"
echo ""
warn "Lembre-se de:"
echo "  - Verificar o arquivo .env com as configurações corretas"
echo "  - Testar o site após o deploy"
echo "  - Verificar os logs em storage/logs/laravel.log"
echo "  - Monitorar o desempenho"


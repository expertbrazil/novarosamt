#!/bin/bash

# Script para corrigir conexão MySQL em produção
# Execute este script após fazer deploy das alterações

echo "🔧 Corrigindo conexão MySQL em produção..."

# Limpar todos os caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Recriar cache de configuração
php artisan config:cache

echo "✅ Cache limpo e configuração recriada!"
echo ""
echo "📋 Verifique se o arquivo .env tem as seguintes configurações:"
echo "   DB_CONNECTION=mysql"
echo "   DB_HOST=<seu-host-mysql>"
echo "   DB_PORT=3306"
echo "   DB_DATABASE=<seu-banco>"
echo "   DB_USERNAME=<seu-usuario>"
echo "   DB_PASSWORD=<sua-senha>"
echo ""
echo "⚠️  IMPORTANTE: Não defina DB_SOCKET no .env a menos que realmente precise usar socket Unix"
echo ""


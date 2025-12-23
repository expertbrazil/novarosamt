#!/bin/bash

# Script para corrigir conexão MySQL em produção
# Execute este script após fazer deploy das alterações

echo "🔧 Corrigindo conexão MySQL em produção..."
echo ""

# Limpar todos os caches
echo "1️⃣ Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Remover arquivos de cache manualmente se existirem
if [ -f "bootstrap/cache/config.php" ]; then
    rm -f bootstrap/cache/config.php
    echo "   ✓ Arquivo de cache de configuração removido"
fi

# Recriar cache de configuração
echo ""
echo "2️⃣ Recriando cache de configuração..."
php artisan config:cache

# Verificar conexão
echo ""
echo "3️⃣ Testando conexão com o banco de dados..."
if php artisan migrate:status > /dev/null 2>&1; then
    echo "   ✅ Conexão com o banco de dados funcionando!"
else
    echo "   ⚠️  Ainda há problemas com a conexão. Verifique o .env"
fi

echo ""
echo "✅ Processo concluído!"
echo ""
echo "📋 Verifique se o arquivo .env tem as seguintes configurações:"
echo "   DB_CONNECTION=mysql"
echo "   DB_HOST=<seu-host-mysql>"
echo "   DB_PORT=3306"
echo "   DB_DATABASE=<seu-banco>"
echo "   DB_USERNAME=<seu-usuario>"
echo "   DB_PASSWORD=<sua-senha>"
echo ""
echo "⚠️  IMPORTANTE:"
echo "   - Não defina DB_SOCKET no .env a menos que realmente precise usar socket Unix"
echo "   - Se DB_SOCKET estiver definido, remova essa linha"
echo "   - Após alterar o .env, execute novamente: php artisan config:clear && php artisan config:cache"
echo ""


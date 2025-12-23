#!/bin/bash

# Script URGENTE para corrigir conexão MySQL em produção
# Execute este script AGORA em produção

echo "🚨 CORREÇÃO URGENTE - Conexão MySQL"
echo "===================================="
echo ""

# 1. Limpar TODOS os caches
echo "1️⃣ Limpando TODOS os caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan optimize:clear 2>/dev/null || true

# 2. Remover arquivos de cache manualmente
echo "2️⃣ Removendo arquivos de cache..."
rm -f bootstrap/cache/config.php 2>/dev/null || true
rm -f bootstrap/cache/routes-v7.php 2>/dev/null || true
rm -f bootstrap/cache/services.php 2>/dev/null || true
rm -rf storage/framework/cache/data/* 2>/dev/null || true

# 3. Verificar .env
echo ""
echo "3️⃣ Verificando configuração do .env..."
if grep -q "DB_SOCKET" .env 2>/dev/null; then
    echo "   ⚠️  ATENÇÃO: DB_SOCKET encontrado no .env"
    echo "   Remova essa linha do .env se não estiver usando socket Unix"
fi

# 4. Recriar cache
echo ""
echo "4️⃣ Recriando cache de configuração..."
php artisan config:cache

echo ""
echo "✅ Processo concluído!"
echo ""
echo "📋 PRÓXIMOS PASSOS:"
echo "   1. Verifique se o .env tem DB_HOST configurado corretamente"
echo "   2. Certifique-se de que NÃO há DB_SOCKET no .env"
echo "   3. Se alterou o .env, execute: php artisan config:clear && php artisan config:cache"
echo "   4. Teste a aplicação acessando uma página"
echo ""


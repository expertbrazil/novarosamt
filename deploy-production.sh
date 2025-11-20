#!/bin/bash

# Script rápido para atualizar em produção após fazer upload dos arquivos
# Execute este script no servidor de produção após fazer git pull ou upload dos arquivos

echo "🚀 Atualizando aplicação em produção..."
echo ""

# Limpar caches antigos
echo "📦 Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Executar migrações (se houver novas)
echo ""
echo "🗄️  Verificando migrações..."
php artisan migrate --force

# Cachear para produção
echo ""
echo "⚡ Cacheando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
echo ""
echo "🔧 Otimizando autoloader..."
composer dump-autoload --optimize

echo ""
echo "✅ Atualização concluída!"
echo ""
echo "📝 Próximos passos:"
echo "  - Verifique se o site está funcionando"
echo "  - Teste as novas funcionalidades"
echo "  - Verifique os logs: tail -f storage/logs/laravel.log"


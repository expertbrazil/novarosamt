#!/bin/bash
# Script para corrigir acesso ao MySQL

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENV_FILE="$SCRIPT_DIR/.env"

echo "🔧 Corrigindo acesso ao MySQL..."

# Verificar se MySQL está rodando
if ! systemctl is-active --quiet mysql 2>/dev/null; then
    echo "⚠️  MySQL não está rodando. Tentando iniciar..."
    sudo systemctl start mysql 2>/dev/null || {
        echo "❌ Não foi possível iniciar o MySQL. Verifique se está instalado."
        exit 1
    }
fi

# Executar script SQL
echo "📦 Executando configuração do banco de dados..."
sudo mysql < "$SCRIPT_DIR/fix-mysql-access.sql"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Banco de dados configurado com sucesso!"
    echo ""
    
    # Atualizar .env para usar o usuário novarosamt
    if [ -f "$ENV_FILE" ]; then
        echo "📝 Atualizando arquivo .env..."
        
        # Atualizar DB_USERNAME para novarosamt
        if grep -q "^DB_USERNAME=" "$ENV_FILE"; then
            sed -i 's/^DB_USERNAME=.*/DB_USERNAME=novarosamt/' "$ENV_FILE"
        else
            echo "DB_USERNAME=novarosamt" >> "$ENV_FILE"
        fi
        
        # Garantir que DB_PASSWORD está configurado
        if ! grep -q "^DB_PASSWORD=" "$ENV_FILE"; then
            echo "DB_PASSWORD=root" >> "$ENV_FILE"
        fi
        
        echo "✅ Arquivo .env atualizado!"
        echo ""
        echo "Configuração aplicada:"
        echo "  DB_USERNAME=novarosamt"
        echo "  DB_PASSWORD=root"
        echo ""
        echo "🚀 Agora você pode executar:"
        echo "   php artisan migrate"
        echo "   php artisan db:seed"
    else
        echo "⚠️  Arquivo .env não encontrado. Configure manualmente:"
        echo "   DB_USERNAME=novarosamt"
        echo "   DB_PASSWORD=root"
    fi
else
    echo "❌ Erro ao configurar o banco de dados."
    exit 1
fi


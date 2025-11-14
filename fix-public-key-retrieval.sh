#!/bin/bash
# Script para corrigir erro "Public Key Retrieval is not allowed"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENV_FILE="$SCRIPT_DIR/.env"

echo "🔧 Corrigindo erro 'Public Key Retrieval is not allowed'..."

if [ ! -f "$ENV_FILE" ]; then
    echo "❌ Arquivo .env não encontrado!"
    exit 1
fi

# Ler valores atuais do .env
DB_HOST=$(grep "^DB_HOST=" "$ENV_FILE" | cut -d '=' -f2 | tr -d ' ')
DB_PORT=$(grep "^DB_PORT=" "$ENV_FILE" | cut -d '=' -f2 | tr -d ' ')
DB_DATABASE=$(grep "^DB_DATABASE=" "$ENV_FILE" | cut -d '=' -f2 | tr -d ' ')
DB_USERNAME=$(grep "^DB_USERNAME=" "$ENV_FILE" | cut -d '=' -f2 | tr -d ' ')
DB_PASSWORD=$(grep "^DB_PASSWORD=" "$ENV_FILE" | cut -d '=' -f2 | tr -d ' ')

# Valores padrão se não encontrados
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-novarosamt}
DB_USERNAME=${DB_USERNAME:-novarosamt}
DB_PASSWORD=${DB_PASSWORD:-root}

# Construir URL de conexão com allowPublicKeyRetrieval=true
DB_URL="mysql://${DB_USERNAME}:${DB_PASSWORD}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}?allowPublicKeyRetrieval=true"

# Atualizar ou adicionar DB_URL no .env
if grep -q "^DB_URL=" "$ENV_FILE"; then
    sed -i "s|^DB_URL=.*|DB_URL=${DB_URL}|" "$ENV_FILE"
else
    echo "" >> "$ENV_FILE"
    echo "# URL de conexão com allowPublicKeyRetrieval para MySQL 8.0+" >> "$ENV_FILE"
    echo "DB_URL=${DB_URL}" >> "$ENV_FILE"
fi

echo "✅ Arquivo .env atualizado!"
echo ""
echo "Configuração aplicada:"
echo "  DB_URL=${DB_URL}"
echo ""
echo "🚀 Agora você pode executar:"
echo "   php artisan migrate"
echo "   php artisan db:seed"


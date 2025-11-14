#!/bin/bash
# Script para alterar método de autenticação do MySQL para evitar erro "Public Key Retrieval"

echo "🔧 Alterando método de autenticação do MySQL..."

sudo mysql <<EOF
-- Alterar usuário para usar mysql_native_password (evita erro de Public Key Retrieval)
ALTER USER IF EXISTS 'novarosamt'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
ALTER USER IF EXISTS 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
FLUSH PRIVILEGES;
SELECT user, host, plugin FROM mysql.user WHERE user IN ('root', 'novarosamt');
EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Método de autenticação alterado com sucesso!"
    echo ""
    echo "🚀 Agora você pode executar:"
    echo "   php artisan migrate"
    echo "   php artisan db:seed"
else
    echo "❌ Erro ao alterar método de autenticação."
    exit 1
fi


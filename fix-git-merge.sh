#!/bin/bash

# Script para resolver conflitos de merge no servidor de produção
# Execute este script no servidor de produção antes de fazer git pull

echo "Resolvendo conflitos de arquivos não rastreados..."

# Fazer backup dos arquivos locais (caso sejam diferentes)
mkdir -p /tmp/git-backup-$(date +%Y%m%d-%H%M%S)
BACKUP_DIR="/tmp/git-backup-$(date +%Y%m%d-%H%M%S)"

echo "Fazendo backup dos arquivos em: $BACKUP_DIR"

# Fazer backup dos arquivos que podem ser sobrescritos
if [ -f "public/apple-touch-icon.png" ]; then
    cp public/apple-touch-icon.png "$BACKUP_DIR/" 2>/dev/null || true
fi
if [ -f "public/favicon-96x96.png" ]; then
    cp public/favicon-96x96.png "$BACKUP_DIR/" 2>/dev/null || true
fi
if [ -f "public/favicon.svg" ]; then
    cp public/favicon.svg "$BACKUP_DIR/" 2>/dev/null || true
fi
if [ -f "public/web-app-manifest-192x192.png" ]; then
    cp public/web-app-manifest-192x192.png "$BACKUP_DIR/" 2>/dev/null || true
fi
if [ -f "public/web-app-manifest-512x512.png" ]; then
    cp public/web-app-manifest-512x512.png "$BACKUP_DIR/" 2>/dev/null || true
fi

echo "Fazendo backup de arquivos modificados localmente..."

# Fazer backup de arquivos que podem ter mudanças locais
if [ -f "public/favicon.ico" ]; then
    cp public/favicon.ico "$BACKUP_DIR/" 2>/dev/null || true
fi
if [ -f "public/site.webmanifest" ]; then
    cp public/site.webmanifest "$BACKUP_DIR/" 2>/dev/null || true
fi

echo "Removendo arquivos não rastreados que conflitam..."

# Remover os arquivos não rastreados (eles serão restaurados pelo git pull)
rm -f public/apple-touch-icon.png
rm -f public/favicon-96x96.png
rm -f public/favicon.svg
rm -f public/web-app-manifest-192x192.png
rm -f public/web-app-manifest-512x512.png

echo "Descartando mudanças locais em arquivos modificados..."

# Descartar mudanças locais nos arquivos que conflitam
git checkout -- public/favicon.ico 2>/dev/null || true
git checkout -- public/site.webmanifest 2>/dev/null || true

echo "Arquivos preparados. Agora você pode fazer: git pull"
echo "Backup salvo em: $BACKUP_DIR"


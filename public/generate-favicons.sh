#!/bin/bash

# Script para gerar favicons a partir de uma imagem fonte
# Uso: ./generate-favicons.sh favicon-source.png

SOURCE_IMAGE=$1

if [ -z "$SOURCE_IMAGE" ]; then
    echo "Erro: Forneça o caminho da imagem fonte"
    echo "Uso: ./generate-favicons.sh favicon-source.png"
    exit 1
fi

if [ ! -f "$SOURCE_IMAGE" ]; then
    echo "Erro: Arquivo não encontrado: $SOURCE_IMAGE"
    exit 1
fi

echo "Gerando favicons a partir de: $SOURCE_IMAGE"

# Verificar se ImageMagick está instalado
if ! command -v convert &> /dev/null; then
    echo "ImageMagick não encontrado. Instalando..."
    echo "Por favor, instale manualmente:"
    echo "  Ubuntu/Debian: sudo apt-get install imagemagick"
    echo "  macOS: brew install imagemagick"
    exit 1
fi

# Gerar favicons
echo "Gerando favicon-16x16.png..."
convert "$SOURCE_IMAGE" -resize 16x16 favicon-16x16.png

echo "Gerando favicon-32x32.png..."
convert "$SOURCE_IMAGE" -resize 32x32 favicon-32x32.png

echo "Gerando apple-touch-icon.png (180x180)..."
convert "$SOURCE_IMAGE" -resize 180x180 apple-touch-icon.png

echo "Gerando android-chrome-192x192.png..."
convert "$SOURCE_IMAGE" -resize 192x192 android-chrome-192x192.png

echo "Gerando android-chrome-512x512.png..."
convert "$SOURCE_IMAGE" -resize 512x512 android-chrome-512x512.png

echo "Gerando favicon.ico (múltiplos tamanhos)..."
convert "$SOURCE_IMAGE" -define icon:auto-resize=16,32,48 favicon.ico

echo "✓ Favicons gerados com sucesso!"
echo ""
echo "Arquivos criados:"
echo "  - favicon.ico"
echo "  - favicon-16x16.png"
echo "  - favicon-32x32.png"
echo "  - apple-touch-icon.png"
echo "  - android-chrome-192x192.png"
echo "  - android-chrome-512x512.png"


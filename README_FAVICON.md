# Como Configurar o Favicon

## Passo 1: Preparar a Imagem

1. Crie ou obtenha uma imagem quadrada (recomendado: 512x512px ou maior)
2. Formato: PNG com fundo transparente (ou fundo sólido)
3. Salve como `favicon-source.png` na pasta `public/`

## Passo 2: Gerar os Favicons

Você pode usar ferramentas online ou criar manualmente:

### Opção A: Usando ferramenta online (Recomendado)
1. Acesse: https://realfavicongenerator.net/
2. Faça upload da sua imagem
3. Configure as opções:
   - iOS: Apple Touch Icon (180x180)
   - Android: Chrome (192x192 e 512x512)
   - Windows: Tile (144x144)
   - Favicon: 16x16, 32x32 e favicon.ico
4. Baixe o pacote gerado
5. Extraia os arquivos na pasta `public/`

### Opção B: Usando ImageMagick (via terminal)

```bash
# Instalar ImageMagick (se não tiver)
# Ubuntu/Debian:
sudo apt-get install imagemagick

# Criar os favicons a partir de uma imagem fonte
cd public

# Favicon 16x16
convert favicon-source.png -resize 16x16 favicon-16x16.png

# Favicon 32x32
convert favicon-source.png -resize 32x32 favicon-32x32.png

# Apple Touch Icon 180x180
convert favicon-source.png -resize 180x180 apple-touch-icon.png

# Android Chrome 192x192
convert favicon-source.png -resize 192x192 android-chrome-192x192.png

# Android Chrome 512x512
convert favicon-source.png -resize 512x512 android-chrome-512x512.png

# Favicon.ico (múltiplos tamanhos)
convert favicon-source.png -define icon:auto-resize=16,32,48 favicon.ico
```

## Arquivos Necessários

Após gerar, você deve ter os seguintes arquivos na pasta `public/`:

- `favicon.ico` (16x16, 32x32, 48x48)
- `favicon-16x16.png`
- `favicon-32x32.png`
- `apple-touch-icon.png` (180x180)
- `android-chrome-192x192.png`
- `android-chrome-512x512.png`
- `site.webmanifest` (já criado)

## Verificação

1. Limpe o cache do navegador
2. Acesse o site e verifique se o favicon aparece na aba
3. Teste em diferentes navegadores (Chrome, Firefox, Safari, Edge)

## Nota

O arquivo `site.webmanifest` já está configurado. Se você gerar um novo via ferramenta online, substitua o existente.


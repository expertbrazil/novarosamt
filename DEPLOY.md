# Guia de Deploy - Nova Rosa MT

## Checklist Pré-Deploy

### 1. Verificar Ambiente Local
- [ ] Assets compilados (`npm run build`)
- [ ] Código commitado no Git
- [ ] Testes passando (se houver)
- [ ] `.env` configurado corretamente

### 2. Preparar Arquivos para Produção

```bash
# Compilar assets
npm run build

# Otimizar autoloader
composer install --optimize-autoloader --no-dev

# Limpar e cachear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cachear para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Arquivos a Enviar para o Servidor

**Incluir:**
- Todos os arquivos do projeto (exceto os listados no .gitignore)
- `public/build/` (assets compilados)
- `vendor/` (dependências do Composer)
- `node_modules/` (opcional, apenas se necessário)

**NÃO incluir:**
- `.env` (criar novo no servidor)
- `node_modules/` (geralmente não necessário em produção)
- Arquivos de desenvolvimento

### 4. No Servidor de Produção

#### A. Conectar ao Servidor
```bash
# Via SSH
ssh usuario@servidor.com
```

#### B. Navegar até o Diretório
```bash
cd /caminho/do/projeto
```

#### C. Atualizar Código
```bash
# Se usar Git
git pull origin main

# Ou fazer upload dos arquivos via FTP/SFTP
```

#### D. Instalar Dependências
```bash
# Composer (sem dev dependencies)
composer install --optimize-autoloader --no-dev

# NPM (se necessário compilar no servidor)
npm ci --production
npm run build
```

#### E. Configurar Ambiente
```bash
# Copiar .env.example para .env (se não existir)
cp .env.example .env

# Editar .env com as configurações de produção
nano .env

# Gerar chave da aplicação
php artisan key:generate
```

#### F. Executar Migrações
```bash
# Executar migrações pendentes
php artisan migrate --force

# Popular estados e municípios (se necessário)
php artisan seed:estados-municipios --force
```

#### G. Otimizar Aplicação
```bash
# Limpar caches antigos
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cachear para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
composer dump-autoload --optimize
```

#### H. Configurar Permissões
```bash
# Dar permissões corretas
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### I. Configurar Link Simbólico do Storage
```bash
# Criar link simbólico para storage público
php artisan storage:link
```

### 5. Configurações do Servidor Web

#### Apache (.htaccess já deve estar em public/)
```apache
# Verificar se o DocumentRoot aponta para /public
DocumentRoot /caminho/do/projeto/public
```

#### Nginx
```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    root /caminho/do/projeto/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 6. Verificações Pós-Deploy

- [ ] Site acessível
- [ ] Assets carregando corretamente
- [ ] Banco de dados conectado
- [ ] Migrações executadas
- [ ] Storage linkado
- [ ] Permissões corretas
- [ ] Logs sem erros

### 7. Comandos Úteis

```bash
# Ver logs
tail -f storage/logs/laravel.log

# Verificar status
php artisan about

# Testar conexão com banco
php artisan tinker --execute="DB::connection()->getPdo();"

# Verificar rotas
php artisan route:list
```

## Variáveis de Ambiente Importantes (.env)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha

# Cache
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Mail (se configurado)
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
```

## Rollback (Se algo der errado)

```bash
# Reverter migrações (cuidado!)
php artisan migrate:rollback

# Limpar todos os caches
php artisan optimize:clear

# Restaurar backup do banco (se houver)
```

## Notas Importantes

1. **Sempre faça backup** do banco de dados antes do deploy
2. **Teste em ambiente de staging** antes de produção
3. **Mantenha `.env` seguro** - nunca commite no Git
4. **Monitore os logs** após o deploy
5. **Verifique permissões** de arquivos e diretórios


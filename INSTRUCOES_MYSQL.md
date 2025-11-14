# Instruções para Configurar MySQL

## Status Atual

✅ Arquivo `.env` criado e configurado para MySQL
✅ Dependências do Composer instaladas
✅ Chave da aplicação Laravel gerada
✅ Migrations do Spatie Permission publicadas
✅ Permissões do storage corrigidas
✅ Link simbólico do storage criado
✅ Dependências NPM instaladas
✅ Assets compilados

## Próximos Passos

### 1. Instalar e Configurar MySQL

Execute o script de instalação:

```bash
./setup-mysql.sh
```

Ou instale manualmente:

```bash
sudo apt update
sudo apt install -y mysql-server mysql-client
sudo systemctl start mysql
sudo systemctl enable mysql
```

### 2. Criar Banco de Dados

Conecte ao MySQL e crie o banco:

```bash
sudo mysql
```

No prompt do MySQL, execute:

```sql
CREATE DATABASE IF NOT EXISTS novarosamt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON novarosamt.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Executar Migrations

Após o MySQL estar configurado, execute:

```bash
php artisan migrate
```

### 4. Executar Seeders (Criar dados iniciais)

```bash
php artisan db:seed
```

Isso criará o usuário admin com as credenciais:
- Email: `admin@novarosamt.com`
- Senha: `password`

### 5. Iniciar o Servidor

Para desenvolvimento:

```bash
php artisan serve
```

Ou use o script do composer (com hot-reload do Vite):

```bash
php composer.phar run dev
```

A aplicação estará disponível em: http://localhost:8000

## Configuração do .env

O arquivo `.env` já está configurado com:
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=novarosamt`
- `DB_USERNAME=root`
- `DB_PASSWORD=root`

Se precisar alterar essas configurações, edite o arquivo `.env`.


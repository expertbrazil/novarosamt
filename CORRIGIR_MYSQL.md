# Como Corrigir o Erro de Acesso ao MySQL

## Erro
```
SQLSTATE[HY000] [1698] Access denied for user 'root'@'localhost'
```

## Causa
O MySQL moderno (8.0+) usa autenticação via socket (`auth_socket`) para o usuário root por padrão, o que impede conexões com senha.

## Solução Rápida

### Opção 1: Usar Usuário Específico (Recomendado)

Execute o script de correção:

```bash
sudo mysql < fix-mysql-access.sql
```

Ou execute manualmente:

```bash
sudo mysql
```

No prompt do MySQL, execute:

```sql
CREATE DATABASE IF NOT EXISTS novarosamt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'novarosamt'@'localhost';
CREATE USER 'novarosamt'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON novarosamt.* TO 'novarosamt'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Depois, atualize o arquivo `.env`:

```env
DB_USERNAME=novarosamt
DB_PASSWORD=root
```

### Opção 2: Alterar Autenticação do Root

Se preferir usar o root, altere o método de autenticação:

```bash
sudo mysql
```

No prompt do MySQL:

```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
FLUSH PRIVILEGES;
EXIT;
```

Mantenha o `.env` com:
```env
DB_USERNAME=root
DB_PASSWORD=root
```

## Verificar se Funcionou

Após aplicar uma das soluções, teste a conexão:

```bash
php artisan migrate
```

Se funcionar, continue com:

```bash
php artisan db:seed
```

## Script Automatizado

Você também pode usar o script shell:

```bash
./fix-mysql-access.sh
```

Este script executa o SQL automaticamente e mostra as opções de configuração.


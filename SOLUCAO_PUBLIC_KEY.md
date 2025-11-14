# Solução para "Public Key Retrieval is not allowed"

## Erro
```
SQLSTATE[HY000] [2002] Public Key Retrieval is not allowed
```

## Causa
O MySQL 8.0+ usa o método de autenticação `caching_sha2_password` por padrão, que requer recuperação de chave pública. O Laravel/PDO não permite isso por padrão por questões de segurança.

## Solução Recomendada: Alterar Método de Autenticação

A melhor solução é alterar o método de autenticação do usuário MySQL para `mysql_native_password`, que não requer recuperação de chave pública.

### Execute o script:

```bash
./fix-mysql-auth.sh
```

### Ou manualmente:

```bash
sudo mysql
```

No prompt do MySQL:

```sql
ALTER USER 'novarosamt'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
FLUSH PRIVILEGES;
EXIT;
```

## Alternativa: Usar DB_URL (se preferir manter caching_sha2_password)

Se preferir manter o método de autenticação padrão, adicione no `.env`:

```env
DB_URL=mysql://novarosamt:root@127.0.0.1:3306/novarosamt?allowPublicKeyRetrieval=true
```

**Nota:** Esta opção é menos segura, pois permite recuperação de chave pública.

## Verificar se Funcionou

Após aplicar a solução, teste:

```bash
php artisan config:clear
php artisan migrate
```

Se funcionar, continue com:

```bash
php artisan db:seed
```


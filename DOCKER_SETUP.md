# Configuração Docker - Portas

## Porta da Aplicação

A aplicação está configurada para rodar na **porta 9000** por padrão.

Se você precisar alterar, defina a variável de ambiente `FORWARD_HTTP_PORT` antes de iniciar os containers:

```bash
export FORWARD_HTTP_PORT=9000
docker-compose up -d
```

Ou no Windows PowerShell:
```powershell
$env:FORWARD_HTTP_PORT="9000"
docker-compose up -d
```

## Configuração no .env

Certifique-se de que o arquivo `.env` tenha a configuração correta:

```env
APP_URL=http://localhost:9000
```

## Portas Utilizadas

- **9000**: Aplicação web (Nginx)
- **3306**: MySQL (Banco de dados)

Se precisar mudar a porta do MySQL também, use a variável `FORWARD_DB_PORT`:

```env
FORWARD_DB_PORT=3307
```


# Portal de Produtos de Limpeza - Nova Rosa MT

Sistema web completo com portal público para pedidos de produtos de limpeza e painel administrativo com RBAC.

## Tecnologias

- Laravel 12 (PHP 8.2)
- Tailwind CSS 4
- MySQL 8
- Docker & Docker Compose
- Spatie Laravel Permission (RBAC)

## Pré-requisitos

- Docker Desktop instalado
- Docker Compose instalado

## Instalação

### 1. Configurar ambiente

Copie o arquivo `.env.example` para `.env` (se não existir):

```bash
cp .env.example .env
```

**Importante:** No arquivo `.env`, configure:
```env
APP_URL=http://localhost:9000
```

### 2. Construir e iniciar containers

```bash
docker-compose up -d --build
```

### 3. Instalar dependências do Composer

```bash
docker-compose exec app composer install
```

### 4. Gerar chave da aplicação

```bash
docker-compose exec app php artisan key:generate
```

### 5. Publicar migrations do Spatie Permission

```bash
docker-compose exec app php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 6. Executar migrations

```bash
docker-compose exec app php artisan migrate
```

### 7. Corrigir permissões do storage (importante!)

Execute este comando para garantir que o Laravel possa escrever no diretório storage:

```bash
docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
docker-compose exec app chown -R novarosamt:www-data /var/www/html/storage /var/www/html/bootstrap/cache
```

**Nota:** O entrypoint já faz isso automaticamente, mas se ainda tiver problemas de permissão, execute os comandos acima.

### 8. Criar link simbólico para storage

```bash
docker-compose exec app php artisan storage:link
```

### 9. Executar seeders (cria usuário admin e dados iniciais)

```bash
docker-compose exec app php artisan db:seed
```

**Credenciais padrão do admin:**
- Email: `admin@novarosamt.com`
- Senha: `password`

### 10. Instalar dependências NPM e compilar assets

```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

**Para desenvolvimento com hot-reload:**

```bash
docker-compose exec app npm run dev
```

## Comandos Úteis

### Acessar o container da aplicação

```bash
docker-compose exec app bash
```

### Executar comandos Artisan

```bash
docker-compose exec app php artisan <comando>
```

### Ver logs

```bash
docker-compose logs -f app
docker-compose logs -f db
```

### Parar containers

```bash
docker-compose down
```

## Acesso

- **Aplicação:** http://localhost:9000
- **Login Admin:** http://localhost:9000/login

### Credenciais Padrão

- Email: `admin@novarosamt.com`
- Senha: `password`

### Banco de Dados

- Host: `db`
- Porta: `3306`
- Database: `novarosamt`
- Username: `novarosamt`
- Password: `root`

## Estrutura do Sistema

### Portal Público

- `/` - Página inicial com produtos por categoria
- `/categoria/{slug}` - Produtos filtrados por categoria
- `/pedido` - Formulário público de pedidos (sem login necessário)
- `/pedido/sucesso/{id}` - Página de confirmação do pedido

### Painel Administrativo

- `/admin` - Dashboard com estatísticas
- `/admin/produtos` - CRUD completo de produtos
  - Listagem com busca e filtros
  - Criar, editar e excluir produtos
  - Upload de imagens
  - Gestão de estoque
- `/admin/pedidos` - Gestão de pedidos
  - Listagem com busca e filtros por status
  - Visualização detalhada
  - Atualização de status

## Roles e Permissões

O sistema utiliza RBAC (Role-Based Access Control) com as seguintes roles:

- **Admin** - Acesso completo (produtos, pedidos, usuários)
- **Gerente** - Gestão de produtos e pedidos
- **Vendedor** - Visualização e gestão de pedidos
- **Cliente** - Role para uso futuro

## Funcionalidades Implementadas

✅ Sistema de autenticação  
✅ RBAC com Spatie Laravel Permission  
✅ CRUD completo de produtos  
✅ CRUD completo de categorias  
✅ Formulário público de pedidos  
✅ Validação de CPF  
✅ Cálculo automático de totais  
✅ Integração WhatsApp (estrutura pronta)  
✅ Dashboard administrativo com estatísticas  
✅ Gestão de pedidos no admin  
✅ Upload de imagens para produtos  
✅ Sistema de estoque  
✅ Design responsivo com Tailwind CSS  
✅ Sidebar administrativa com navegação  

## Configuração do WhatsApp

Para habilitar o envio de pedidos via WhatsApp, configure no arquivo `.env`:

```env
WHATSAPP_API_URL=https://sua-api-whatsapp.com/api/send
WHATSAPP_API_TOKEN=seu-token-aqui
WHATSAPP_PHONE_NUMBER=5511999999999
```

## Notas Técnicas

- As imagens dos produtos são armazenadas em `storage/app/public/products`
- O sistema valida CPF no formulário de pedidos
- Os pedidos são salvos no banco e enviados via WhatsApp automaticamente
- O estoque é atualizado automaticamente quando um pedido é criado
- O sistema usa paginação nas listagens

## Próximos Passos (Opcional)

- Implementar gestão de usuários no admin
- Adicionar filtros avançados de busca
- Implementar relatórios e exportações
- Adicionar sistema de notificações
- Implementar histórico de alterações
- Adicionar testes automatizados

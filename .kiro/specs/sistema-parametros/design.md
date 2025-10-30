# Design Document - Módulo de Parâmetros do Sistema

## Overview

O módulo de parâmetros do sistema será implementado como uma funcionalidade administrativa que permite configurar aspectos fundamentais da aplicação. O design seguirá os padrões estabelecidos nos outros módulos, com interface moderna, responsiva e intuitiva.

## Architecture

### Database Design

**Tabela: `system_settings`**
```sql
- id (bigint, primary key)
- key (string, unique) - Chave única do parâmetro
- value (text, nullable) - Valor do parâmetro
- type (enum: string, integer, boolean, file, encrypted) - Tipo do valor
- category (string) - Categoria para organização
- description (text, nullable) - Descrição do parâmetro
- created_at (timestamp)
- updated_at (timestamp)
```

**Tabela: `system_settings_logs`**
```sql
- id (bigint, primary key)
- setting_key (string) - Chave do parâmetro alterado
- old_value (text, nullable) - Valor anterior
- new_value (text, nullable) - Novo valor
- user_id (bigint, foreign key) - Usuário que fez a alteração
- ip_address (string) - IP do usuário
- user_agent (text) - User agent do navegador
- created_at (timestamp)
```

### File Storage

**Estrutura de Diretórios:**
```
storage/app/public/
├── system/
│   ├── logos/
│   │   ├── current/ (logomarca atual)
│   │   └── history/ (versões anteriores)
│   └── uploads/
```

## Components and Interfaces

### 1. Controller: `SystemSettingsController`

**Métodos:**
- `index()` - Exibe página principal de configurações
- `update(Request $request)` - Atualiza configurações
- `uploadLogo(Request $request)` - Upload de logomarca
- `testSmtp(Request $request)` - Testa configurações SMTP
- `resetToDefault(Request $request)` - Restaura configurações padrão

### 2. Model: `SystemSetting`

**Funcionalidades:**
- Casts automáticos baseados no tipo
- Criptografia/descriptografia para valores sensíveis
- Cache de configurações para performance
- Validação de tipos de dados

### 3. Service: `SystemSettingsService`

**Responsabilidades:**
- Gerenciamento de cache de configurações
- Upload e processamento de imagens
- Envio de emails de teste SMTP
- Aplicação de configurações em tempo real

### 4. Middleware: `SystemSettingsMiddleware`

**Função:**
- Disponibilizar configurações globalmente nas views
- Cache inteligente de configurações
- Refresh automático quando necessário

## Data Models

### SystemSetting Model

```php
class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'category', 'description'];
    
    protected $casts = [
        'value' => 'string'
    ];
    
    // Métodos para diferentes tipos de dados
    public function getTypedValue()
    public function setTypedValue($value)
    public static function get($key, $default = null)
    public static function set($key, $value, $type = 'string')
}
```

### Configurações Padrão

```php
'system_settings' => [
    'company' => [
        'name' => 'Nova Rosa MT',
        'logo' => null,
        'phone' => null,
        'whatsapp' => null,
    ],
    'smtp' => [
        'host' => null,
        'port' => 587,
        'username' => null,
        'password' => null,
        'encryption' => 'tls',
        'from_address' => null,
        'from_name' => null,
    ],
    'system' => [
        'timezone' => 'America/Cuiaba',
        'date_format' => 'd/m/Y',
        'currency' => 'BRL',
    ]
]
```

## Error Handling

### Validação de Upload de Imagem

```php
'logo' => [
    'required',
    'image',
    'mimes:jpeg,png,jpg,gif,svg',
    'max:2048', // 2MB
    'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
]
```

### Validação de SMTP

```php
'smtp_host' => 'required|string|max:255',
'smtp_port' => 'required|integer|between:1,65535',
'smtp_username' => 'required|email|max:255',
'smtp_password' => 'required|string|min:6',
'smtp_encryption' => 'required|in:tls,ssl,none'
```

### Tratamento de Erros

- **Upload de arquivo**: Validação de tamanho, tipo e dimensões
- **Configurações SMTP**: Teste de conexão antes de salvar
- **Permissões**: Verificação de acesso administrativo
- **Dados inválidos**: Validação rigorosa com mensagens específicas

## Testing Strategy

### Unit Tests

1. **SystemSetting Model**
   - Teste de casts de tipos
   - Teste de criptografia/descriptografia
   - Teste de métodos get/set

2. **SystemSettingsService**
   - Teste de upload de imagens
   - Teste de configurações SMTP
   - Teste de cache de configurações

### Integration Tests

1. **SystemSettingsController**
   - Teste de upload de logomarca
   - Teste de atualização de configurações
   - Teste de permissões de acesso

2. **Middleware**
   - Teste de disponibilização de configurações
   - Teste de cache e refresh

### Feature Tests

1. **Interface de Usuário**
   - Teste de formulários de configuração
   - Teste de upload de arquivos
   - Teste de feedback visual

2. **Funcionalidades End-to-End**
   - Teste completo de configuração SMTP
   - Teste de aplicação de logomarca
   - Teste de persistência de configurações

## Security Considerations

### Criptografia de Dados Sensíveis

- Senhas SMTP criptografadas com Laravel Crypt
- Chaves de API protegidas
- Dados sensíveis nunca expostos em logs

### Controle de Acesso

- Middleware de autenticação obrigatório
- Verificação de papel administrativo
- Logs de auditoria para todas as alterações

### Validação de Uploads

- Verificação de tipo MIME
- Validação de extensões permitidas
- Escaneamento de malware (futuro)
- Limitação de tamanho de arquivo

## Performance Optimizations

### Cache Strategy

- Cache de configurações em Redis/File
- Invalidação inteligente de cache
- Lazy loading de configurações não críticas

### File Handling

- Otimização automática de imagens
- Geração de thumbnails
- CDN para assets estáticos (futuro)

## UI/UX Design

### Layout da Página

```
┌─────────────────────────────────────┐
│ Header: Configurações do Sistema    │
├─────────────────────────────────────┤
│ Tabs: [Empresa] [SMTP] [Sistema]    │
├─────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────────┐ │
│ │ Logomarca   │ │ Informações     │ │
│ │ [Upload]    │ │ Nome: [____]    │ │
│ │             │ │ Tel: [____]     │ │
│ │             │ │ WhatsApp: [___] │ │
│ └─────────────┘ └─────────────────┘ │
├─────────────────────────────────────┤
│ [Salvar] [Testar] [Restaurar]      │
└─────────────────────────────────────┘
```

### Componentes Visuais

- **Cards organizados por categoria**
- **Upload drag-and-drop para logomarca**
- **Indicadores visuais de status**
- **Botões de teste para configurações**
- **Feedback em tempo real**
- **Modo escuro compatível**

### Responsividade

- Layout adaptativo para mobile
- Formulários otimizados para touch
- Imagens responsivas
- Navegação por tabs em mobile
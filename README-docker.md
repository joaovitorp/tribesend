# Docker Setup para Desenvolvimento Local

## Serviços Incluídos

- **App**: Aplicação Laravel com PHP 8.4
- **PostgreSQL**: Banco de dados principal
- **Redis**: Cache e sessões
- **MailTip (MailHog)**: Captura de emails para desenvolvimento

## Como usar

### 1. Configurar variáveis de ambiente

Crie um arquivo `.env` baseado no `.env.example` com as seguintes configurações para Docker:

```bash
# Database
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=tribesend
DB_USERNAME=tribesend
DB_PASSWORD=password

# Redis
REDIS_HOST=redis
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailtip
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

### 2. Construir e iniciar os containers

```bash
# Construir e iniciar todos os serviços
docker-compose up --build -d

# Ou apenas iniciar (após primeira build)
docker-compose up -d
```

### 3. Executar comandos Laravel

```bash
# Acessar o container da aplicação
docker-compose exec app bash

# Dentro do container, executar comandos Laravel
php artisan migrate
php artisan db:seed
php artisan key:generate
```

### 4. Acessar os serviços

- **Aplicação**: http://localhost:8000
- **MailTip (Web UI)**: http://localhost:8025
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

## Comandos úteis

```bash
# Ver logs dos containers
docker-compose logs -f

# Parar todos os serviços
docker-compose down

# Parar e remover volumes (dados serão perdidos)
docker-compose down -v

# Reconstruir apenas o container da app
docker-compose build app

# Executar comandos Artisan
docker-compose exec app php artisan [comando]

# Instalar dependências
docker-compose exec app composer install
docker-compose exec app npm install
```

## Estrutura de Volumes

- **Código da aplicação**: `.` (raiz do projeto) → `/var/www/app`
- **Configuração PHP**: `./docker/api/php/extra-php.ini` → `/usr/local/etc/php/conf.d/99_extra.ini`
- **Dados PostgreSQL**: Volume persistente `pgsql_data`
- **Dados Redis**: Volume persistente `redis_data`

## Troubleshooting

### Erro de compatibilidade Redis com PHP 8.4
Se ocorrer erro `fatal error: standard/php_random.h: No such file or directory`, o Dockerfile já foi configurado para:
1. Tentar instalar Redis 6.1.0 (compatível com PHP 8.4)
2. Se falhar, instalar a versão mais recente disponível automaticamente

### Permissões de arquivo
Se houver problemas de permissão (como `Permission denied` em `/storage/framework/views/`):

**Correção imediata:**
```bash
docker-compose exec app chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache
```

**Verificar permissões:**
```bash
docker-compose exec app ls -la /var/www/app/storage
```

**Nota:** O Dockerfile já está configurado com um script de entrada que define as permissões automaticamente a cada inicialização do container.

### Limpar cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

### Reconstruir apenas se necessário
Se houver problemas persistentes:
```bash
# Limpar tudo e reconstruir
docker-compose down -v
docker system prune -f
docker-compose up --build -d
```

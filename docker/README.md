# Docker Setup para TribeSend

Este setup Docker inclui Laravel com Octane FrankenPHP, Node.js 20+, e todos os serviços necessários.

## Serviços Incluídos

- **app**: Laravel com FrankenPHP + Octane + Node.js 20
- **nginx**: Proxy reverso e servidor web
- **pgsql**: PostgreSQL 16
- **redis**: Redis 7 para cache e sessões
- **mailpit**: Servidor de email para desenvolvimento
- **queue**: Worker de filas Laravel

## Portas Expostas

- **80**: Nginx (aplicação principal)
- **443**: Nginx HTTPS (configurar SSL se necessário)
- **5432**: PostgreSQL
- **6379**: Redis
- **8025**: Mailpit Web UI
- **1025**: Mailpit SMTP

## Como usar

### 1. Configurar ambiente

```bash
# Copie o arquivo de exemplo de ambiente
cp docker/env-docker-example .env

# Gere uma chave de aplicação
docker-compose run --rm app php artisan key:generate
```

### 2. Iniciar os serviços

```bash
# Construir e iniciar todos os serviços
docker-compose up -d --build

# Ou iniciar apenas alguns serviços
docker-compose up -d pgsql redis mailpit
```

### 3. Configurar a aplicação

```bash
# Executar migrations
docker-compose exec app php artisan migrate

# Executar seeders
docker-compose exec app php artisan db:seed

# Limpar caches
docker-compose exec app php artisan optimize:clear
```

### 4. Comandos úteis

```bash
# Ver logs
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f queue

# Executar comandos Artisan
docker-compose exec app php artisan tinker
docker-compose exec app php artisan queue:work

# Executar comandos NPM
docker-compose exec app npm run dev
docker-compose exec app npm run build

# Acessar bash do container
docker-compose exec app bash

# Parar todos os serviços
docker-compose down

# Parar e remover volumes
docker-compose down -v
```

## URLs de Acesso

- **Aplicação**: http://localhost
- **Mailpit**: http://localhost:8025
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

## Estrutura de Arquivos

```
docker/
├── frankenphp/
│   ├── Dockerfile          # Imagem customizada com FrankenPHP + Node.js
│   └── php.ini            # Configurações PHP otimizadas
├── nginx/
│   ├── nginx.conf         # Configuração principal do Nginx
│   └── sites/
│       └── default.conf   # Virtual host para a aplicação
├── pgsql/
│   └── init/
│       └── 01-create-extensions.sql  # Extensões PostgreSQL
├── redis/
│   └── redis.conf         # Configurações Redis
├── env-docker-example     # Exemplo de variáveis de ambiente
└── README.md             # Esta documentação
```

## Performance e Otimizações

### FrankenPHP + Octane
- Workers configurados automaticamente baseado no CPU
- Cache OPcache habilitado
- Sessões armazenadas no Redis
- Máximo de 500 requests por worker

### Nginx
- Proxy reverso otimizado para FrankenPHP
- Cache de arquivos estáticos
- Compressão Gzip habilitada
- Rate limiting para APIs e login

### PostgreSQL
- Extensões úteis pré-instaladas (uuid-ossp, pgcrypto, citext)
- Volume persistente para dados

### Redis
- Configurado como cache e sessões
- Política de memória LRU
- Persistência com AOF

## Troubleshooting

### Aplicação não carrega
- Verifique se todos os containers estão rodando: `docker-compose ps`
- Verifique os logs: `docker-compose logs app nginx`
- Certifique-se que a chave da aplicação foi gerada

### Erro de permissão
```bash
docker-compose exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
```

### Problema com assets
```bash
docker-compose exec app npm run build
```

### Banco de dados não conecta
- Verifique se o PostgreSQL está rodando: `docker-compose ps pgsql`
- Verifique as credenciais no arquivo `.env`
- Execute as migrations: `docker-compose exec app php artisan migrate`

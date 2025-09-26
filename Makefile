# Makefile para facilitar comandos Docker

.PHONY: help build up down restart logs shell artisan npm test

# Cores para output
GREEN=\033[0;32m
YELLOW=\033[1;33m
RED=\033[0;31m
NC=\033[0m # No Color

help: ## Mostra esta ajuda
	@echo "$(GREEN)Comandos disponíveis:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

build: ## Constrói as imagens Docker
	@echo "$(GREEN)Construindo imagens Docker...$(NC)"
	docker-compose build --no-cache

up: ## Inicia todos os serviços
	@echo "$(GREEN)Iniciando serviços...$(NC)"
	docker-compose up -d

down: ## Para todos os serviços
	@echo "$(RED)Parando serviços...$(NC)"
	docker-compose down

restart: down up ## Reinicia todos os serviços

logs: ## Mostra logs de todos os serviços
	docker-compose logs -f

logs-app: ## Mostra logs apenas da aplicação
	docker-compose logs -f app

logs-nginx: ## Mostra logs do Nginx
	docker-compose logs -f nginx

logs-queue: ## Mostra logs da queue
	docker-compose logs -f queue

shell: ## Acessa o bash do container da aplicação
	docker-compose exec app bash

artisan: ## Executa comandos Artisan (uso: make artisan CMD="migrate")
	docker-compose exec app php artisan $(CMD)

tinker: ## Abre o Laravel Tinker
	docker-compose exec app php artisan tinker

npm: ## Executa comandos NPM (uso: make npm CMD="run dev")
	docker-compose exec app npm $(CMD)

migrate: ## Executa as migrations
	@echo "$(GREEN)Executando migrations...$(NC)"
	docker-compose exec app php artisan migrate

migrate-fresh: ## Recria o banco e executa migrations
	@echo "$(YELLOW)Recriando banco de dados...$(NC)"
	docker-compose exec app php artisan migrate:fresh --seed

seed: ## Executa os seeders
	@echo "$(GREEN)Executando seeders...$(NC)"
	docker-compose exec app php artisan db:seed

test: ## Executa os testes
	docker-compose exec app php artisan test

test-coverage: ## Executa testes com coverage
	docker-compose exec app php artisan test --coverage

queue-work: ## Inicia o worker de filas
	docker-compose exec app php artisan queue:work --verbose

queue-restart: ## Reinicia os workers de fila
	docker-compose exec app php artisan queue:restart

cache-clear: ## Limpa todos os caches
	@echo "$(GREEN)Limpando caches...$(NC)"
	docker-compose exec app php artisan optimize:clear

optimize: ## Otimiza a aplicação para produção
	@echo "$(GREEN)Otimizando aplicação...$(NC)"
	docker-compose exec app php artisan optimize

setup: ## Configuração inicial completa
	@echo "$(GREEN)Configuração inicial...$(NC)"
	@if [ ! -f .env ]; then \
		echo "$(YELLOW)Copiando arquivo .env...$(NC)"; \
		cp docker/env-docker-example .env; \
	fi
	@echo "$(GREEN)Gerando chave da aplicação...$(NC)"
	docker-compose run --rm app php artisan key:generate
	@echo "$(GREEN)Executando migrations...$(NC)"
	docker-compose exec app php artisan migrate
	@echo "$(GREEN)Limpando caches...$(NC)"
	docker-compose exec app php artisan optimize:clear
	@echo "$(GREEN)Setup completo!$(NC)"

install: build up setup ## Instalação completa (build + up + setup)

status: ## Mostra status dos containers
	docker-compose ps

clean: ## Remove containers, volumes e imagens não utilizadas
	@echo "$(RED)Limpando Docker...$(NC)"
	docker-compose down -v
	docker system prune -f

clean-all: ## Remove tudo relacionado ao projeto (CUIDADO!)
	@echo "$(RED)Removendo TUDO (containers, volumes, imagens)...$(NC)"
	docker-compose down -v --rmi all
	docker system prune -af

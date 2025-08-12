# Makefile para proyecto Laravel con Docker

.PHONY: help up down build restart logs shell composer artisan migrate test clean

# Colores para output
GREEN=\033[0;32m
YELLOW=\033[1;33m
RED=\033[0;31m
NC=\033[0m # No Color

# Mostrar ayuda por defecto
help: ## Mostrar esta ayuda
	@echo "$(GREEN)Comandos disponibles:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

# Comandos Docker
up: ## Levantar contenedores
	@echo "$(GREEN)Levantando contenedores...$(NC)"
	docker-compose up

down: ## Detener contenedores
	@echo "$(RED)Deteniendo contenedores...$(NC)"
	docker-compose down

build: ## Construir imágenes
	@echo "$(GREEN)Construyendo imágenes...$(NC)"
	docker-compose build

rebuild: ## Reconstruir y levantar contenedores
	@echo "$(GREEN)Reconstruyendo contenedores...$(NC)"
	docker-compose down
	docker-compose build
	docker-compose up -d

restart: ## Reiniciar contenedores
	@echo "$(YELLOW)Reiniciando contenedores...$(NC)"
	docker-compose restart

logs: ## Ver logs de contenedores
	docker-compose logs -f

logs-app: ## Ver logs del contenedor app
	docker-compose logs -f app

logs-db: ## Ver logs del contenedor db
	docker-compose logs -f db

# Comandos de aplicación
shell: ## Acceder al contenedor app
	docker-compose exec app bash

root-shell: ## Acceder al contenedor app como root
	docker-compose exec --user=root app bash

# Comandos Laravel
composer: ## Ejecutar composer install
	@echo "$(GREEN)Instalando dependencias PHP...$(NC)"
	docker-compose exec app composer install

composer-update: ## Actualizar dependencias PHP
	@echo "$(GREEN)Actualizando dependencias PHP...$(NC)"
	docker-compose exec app composer update

artisan: ## Ejecutar comando artisan (ej: make artisan cmd="migrate")
	docker-compose exec app php artisan $(cmd)

migrate: ## Ejecutar migraciones
	@echo "$(GREEN)Ejecutando migraciones...$(NC)"
	docker-compose exec app php artisan migrate

migrate-fresh: ## Rehacer migraciones desde cero
	@echo "$(YELLOW)Rehaciendo migraciones...$(NC)"
	docker-compose exec app php artisan migrate:fresh

migrate-seed: ## Ejecutar migraciones con seeders
	@echo "$(GREEN)Ejecutando migraciones con seeders...$(NC)"
	docker-compose exec app php artisan migrate --seed

key-generate: ## Generar clave de aplicación
	docker-compose exec app php artisan key:generate

cache-clear: ## Limpiar caché
	@echo "$(GREEN)Limpiando caché...$(NC)"
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

# Comandos de desarrollo
test: ## Ejecutar tests
	docker-compose exec app php artisan test

test-coverage: ## Ejecutar tests con cobertura
	docker-compose exec app php artisan test --coverage

npm-install: ## Instalar dependencias NPM
	@echo "$(GREEN)Instalando dependencias NPM...$(NC)"
	docker-compose exec app npm install

npm-dev: ## Ejecutar Vite en modo desarrollo
	docker-compose exec app npm run dev

npm-build: ## Construir assets para producción
	docker-compose exec app npm run build

# Comandos de base de datos
db-shell: ## Acceder a MySQL shell
	docker-compose exec db mysql -u tesis_user -p tesis_db

db-dump: ## Crear backup de base de datos
	@echo "$(GREEN)Creando backup de base de datos...$(NC)"
	docker-compose exec db mysqldump -u tesis_user -p tesis_db > backup_$(shell date +%Y%m%d_%H%M%S).sql

# Comandos de limpieza
clean: ## Limpiar contenedores, imágenes y volúmenes no utilizados
	@echo "$(RED)Limpiando Docker...$(NC)"
	docker system prune -a --volumes

clean-all: ## Detener y eliminar todo (contenedores, volúmenes, imágenes)
	@echo "$(RED)Eliminando todo...$(NC)"
	docker-compose down -v --rmi all
	docker system prune -a --volumes

# Comandos útiles
status: ## Ver estado de contenedores
	docker-compose ps

install: ## Instalación inicial completa
	@echo "$(GREEN)Instalación inicial...$(NC)"
	make build
	make up
	make composer
	make key-generate
	make migrate
	@echo "$(GREEN)¡Instalación completada! Accede a http://localhost:8000$(NC)"

fresh-install: ## Instalación limpia completa
	@echo "$(GREEN)Instalación limpia...$(NC)"
	make down
	make clean-all
	make install
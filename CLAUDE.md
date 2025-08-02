# CLAUDE.md

Este archivo proporciona orientación a Claude Code (claude.ai/code) cuando trabaja con código en este repositorio.

## Estado del Proyecto

Este es actualmente un repositorio vacío. Cuando se agregue código a este proyecto, este archivo debe actualizarse con:

## Comandos Comunes

### Makefile (Recomendado)
- `make help` - Ver todos los comandos disponibles
- `make up` - Levantar contenedores
- `make down` - Detener contenedores
- `make shell` - Acceder al contenedor app
- `make migrate` - Ejecutar migraciones
- `make install` - Instalación inicial completa
- `make logs` - Ver logs de todos los contenedores
- `make test` - Ejecutar tests

### Docker (comandos directos)
- `docker-compose up -d` - Iniciar todos los servicios en segundo plano
- `docker-compose down` - Detener y eliminar contenedores
- `docker-compose build` - Construir las imágenes Docker
- `docker-compose exec app bash` - Acceder al contenedor de la aplicación

### Laravel (dentro del contenedor)
- `docker-compose exec app composer install` - Instalar dependencias PHP
- `docker-compose exec app php artisan migrate` - Ejecutar migraciones
- `docker-compose exec app php artisan make:model NombreModelo` - Crear modelo
- `docker-compose exec app php artisan make:livewire NombreComponente` - Crear componente Livewire
- `docker-compose exec app npm run dev` - Compilar assets en desarrollo
- `docker-compose exec app npm run build` - Compilar assets para producción

## Descripción de la Arquitectura

### Servicios Docker
- **app**: Contenedor PHP 8.2-FPM con Laravel
- **webserver**: Nginx como servidor web (puerto 8000)
- **db**: MySQL 8.0 como base de datos (puerto 3306)
- **phpmyadmin**: Interfaz web para administrar MySQL (puerto 8080)

### Estructura del Proyecto
- `Dockerfile` - Configuración del contenedor Laravel
- `docker-compose.yml` - Orquestación de servicios
- `docker/nginx/default.conf` - Configuración de Nginx

## URLs de Acceso
- Aplicación Laravel: http://localhost:8000
- phpMyAdmin: http://localhost:8080

## Configuración de Base de Datos
- Host: `db`
- Base de datos: `tesis_db`
- Usuario: `tesis_user`
- Contraseña: `user_password`

## Notas de Desarrollo

- Los permisos de Claude Code están configurados en `.claude/settings.local.json`
- Todos los comandos Laravel deben ejecutarse dentro del contenedor usando `docker-compose exec app`
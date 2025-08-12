# CLAUDE.md

Este archivo proporciona orientación a Claude Code (claude.ai/code) cuando trabaja con código en este repositorio.

## Descripción del Proyecto

Esta es una aplicación Laravel 12 para un sistema de pedidos de comida rápida con tres módulos diferenciados:
- **Módulo Cliente**: Interfaz de pedidos con carrito, checkout y seguimiento de pedidos
- **Módulo Empleado**: Dashboard por roles (Ventas, Cocina, Delivery)
- **Módulo Admin**: Gestión completa del negocio con CRUD, analítica y personalización visual de la tienda

La aplicación utiliza Livewire 3.6 para componentes reactivos, se ejecuta en contenedores Docker y conecta clientes, empleados y administradores en un flujo eficiente de pedidos de comida.

## Comandos Comunes

### Makefile (Recomendado)
- `make help` - Ver todos los comandos disponibles
- `make install` - Instalación inicial completa (build + up + composer + key:generate + migrate)
- `make up` - Levantar contenedores
- `make down` - Detener contenedores
- `make shell` - Acceder al contenedor app
- `make migrate` - Ejecutar migraciones
- `make test` - Ejecutar tests con PHPUnit
- `make logs` - Ver logs de todos los contenedores

### Docker (comandos directos)
- `docker-compose up -d` - Iniciar todos los servicios en segundo plano
- `docker-compose down` - Detener y eliminar contenedores
- `docker-compose build` - Construir las imágenes Docker
- `docker-compose exec app bash` - Acceder al contenedor de la aplicación

### Laravel (dentro del contenedor)
- `docker-compose exec app composer install` - Instalar dependencias PHP
- `docker-compose exec app php artisan migrate` - Ejecutar migraciones
- `docker-compose exec app php artisan test` - Ejecutar tests
- `docker-compose exec app php artisan make:model NombreModelo` - Crear modelo
- `docker-compose exec app php artisan make:livewire NombreComponente` - Crear componente Livewire
- `docker-compose exec app npm install` - Instalar dependencias NPM
- `docker-compose exec app npm run dev` - Compilar assets en desarrollo
- `docker-compose exec app npm run build` - Compilar assets para producción

## Descripción de la Arquitectura

### Stack Tecnológico
- **Backend**: Laravel 12 con PHP 8.2
- **Frontend**: Livewire 3.6 + AlpineJS + Tailwind CSS 4.0
- **Build Tool**: Vite 7.0
- **Base de Datos**: MySQL 8.0
- **Servidor Web**: Nginx Alpine
- **Containerización**: Docker + Docker Compose

### Servicios Docker
- **app**: Contenedor PHP 8.2-FPM con Laravel (imagen personalizada)
- **webserver**: Nginx como servidor web (puerto 8000)
- **db**: MySQL 8.0 como base de datos (puerto 3306)

### Estructura del Proyecto
La aplicación sigue la estructura estándar de Laravel con estas características específicas:
- `app/Http/Controllers/` - Controladores organizados por módulo (Cliente, Empleado, Admin)
- `app/Models/` - Modelos Eloquent (User con roles, Producto, Pedido, DetallePedido)
- `app/Livewire/` - Componentes Livewire para interactividad en tiempo real
- `resources/views/` - Vistas Blade organizadas por módulo
- `database/migrations/` - Migraciones para sistema multi-rol

### URLs de Acceso
- Aplicación Laravel: http://localhost:8000
- Base de Datos MySQL: localhost:3306

### Configuración de Base de Datos
- Host: `db` (nombre del contenedor)
- Base de datos: `tesis_db`
- Usuario: `tesis_user`
- Contraseña: `user_password`
- Root password: `root_password`

### Testing
- Framework: PHPUnit 11.5
- Configuración: `phpunit.xml`
- Tests ubicados en: `tests/Feature/` y `tests/Unit/`
- Base de datos de prueba: SQLite en memoria

## Notas de Desarrollo

- Todos los comandos Laravel deben ejecutarse dentro del contenedor usando `docker-compose exec app`
- La aplicación utiliza Livewire para componentes reactivos sin JavaScript complejo
- AlpineJS se usa para interacciones pequeñas del lado del cliente
- Tailwind CSS 4.0 para estilos responsivos
- Los assets se compilan con Vite (configurado en `vite.config.js`)
- Sistema de roles implementado para diferenciación de usuarios (Cliente, Empleado, Admin)
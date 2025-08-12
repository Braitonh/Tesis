# Arquitectura del Sistema

## Arquitectura General

### Patrón Arquitectónico
*[MVC, Microservicios, Monolítico, etc.]*

### Stack Tecnológico
- **Backend:** Laravel 11 (PHP 8.2)
- **Frontend:** Livewire 3.6 + AlpineJS + Tailwind CSS
- **Build Tool:** Vite
- **Base de Datos:** MySQL 8.0
- **Servidor Web:** Nginx
- **Containerización:** Docker + Docker Compose
- **Gestión de Dependencias:** Composer (PHP), NPM (JavaScript)

## Arquitectura de Contenedores

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   tesis-webserver │    │    tesis-app    │    │    tesis-db     │
│                 │    │                 │    │                 │
│   Nginx:alpine  │ -> │  PHP 8.2-FPM    │ -> │   MySQL 8.0     │
│   Puerto: 8000  │    │  Laravel 11     │    │   Puerto: 3306  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │
┌─────────────────┐              │
│ tesis-phpmyadmin│ <────────────┘
│                 │
│ phpMyAdmin      │
│ Puerto: 8080    │
└─────────────────┘
```

## Estructura de Directorios Laravel

```
/var/www/
├── app/                    # Lógica de aplicación
│   ├── Http/Controllers/   # Controladores
│   ├── Models/            # Modelos Eloquent
│   └── Services/          # Servicios de negocio
├── config/                # Configuraciones
├── database/              # Migraciones y seeders
│   ├── migrations/        # Migraciones de BD
│   └── seeders/          # Datos de prueba
├── public/               # Archivos públicos
├── resources/            # Vistas y assets
│   ├── views/           # Plantillas Blade
│   └── js/              # JavaScript
├── routes/              # Definición de rutas
│   ├── web.php         # Rutas web
│   └── api.php         # Rutas API
└── storage/            # Archivos generados
```

## Capas de la Aplicación

### 1. Capa de Presentación
- **Responsabilidad:** Interfaz de usuario interactiva para los 3 módulos
- **Tecnologías:** Livewire 3.6, AlpineJS, Blade Templates, Tailwind CSS
- **Ubicación:** `resources/views/` y `app/Livewire/`
- **Componentes Livewire:**
  - `CatalogoProductos` - Catálogo dinámico con filtros
  - `CarritoCompras` - Carrito interactivo en tiempo real
  - `TrackingPedido` - Seguimiento de pedidos live
  - `DashboardEmpleado` - Panel por roles con updates automáticos
  - `AdminPanel` - Gestión administrativa reactiva

### 2. Capa de Controladores
- **Responsabilidad:** Manejo de requests HTTP por módulo
- **Tecnologías:** Laravel Controllers
- **Ubicación:** `app/Http/Controllers/`
- **Estructura:**
  - `ClienteController` - Funcionalidades de cliente
  - `EmpleadoController` - Gestión por roles de empleado
  - `AdminController` - Panel administrativo
  - `Api/` - Controladores para API REST

### 3. Capa de Servicios
- **Responsabilidad:** Lógica de negocio específica
- **Tecnologías:** Clases PHP personalizadas
- **Ubicación:** `app/Services/`
- **Servicios principales:**
  - `PedidoService` - Gestión completa de pedidos
  - `PagoService` - Procesamiento de pagos
  - `NotificacionService` - Sistema de notificaciones
  - `ReporteService` - Generación de reportes

### 4. Capa de Modelos
- **Responsabilidad:** Interacción con base de datos
- **Tecnologías:** Eloquent ORM
- **Ubicación:** `app/Models/`
- **Modelos principales:**
  - `User` - Usuarios multi-rol
  - `Producto` - Catálogo de productos
  - `Pedido` - Órdenes de compra
  - `DetallePedido` - Items del pedido

### 5. Capa de Datos
- **Responsabilidad:** Persistencia de datos
- **Tecnologías:** MySQL 8.0
- **Ubicación:** Contenedor `tesis-db`

## Patrones de Diseño Utilizados

### Repository Pattern
*[Si se implementa]*

### Service Pattern
*[Para lógica de negocio]*

### Observer Pattern
*[Para eventos de Laravel]*

## Configuración de Red

### Red Docker
- **Nombre:** `tesis-network`
- **Driver:** bridge
- **Comunicación interna:** Por nombre de contenedor

### Puertos Expuestos
- **8000:** Aplicación web (Nginx)
- **8080:** phpMyAdmin
- **3306:** MySQL (opcional, para clientes externos)

## Consideraciones de Seguridad

### Autenticación
*[Método de autenticación utilizado]*

### Autorización
*[Sistema de roles y permisos]*

### Validación
*[Validaciones de entrada de datos]*

## Escalabilidad

### Horizontal
*[Estrategias para escalar horizontalmente]*

### Vertical
*[Recursos que se pueden aumentar]*

### Base de Datos
*[Estrategias de escalabilidad de BD]*
# Requisitos del Sistema

## Requisitos Funcionales

## MÓDULO CLIENTE

### RF001 - Registro y Autenticación de Usuario
**Descripción:** El sistema debe permitir a los usuarios registrarse e iniciar sesión mediante email, redes sociales o checkout como invitado
**Prioridad:** Alta
**Actor:** Cliente
**Precondiciones:** Ninguna
**Flujo Principal:**
1. El usuario accede a la página de registro
2. Selecciona método de registro (email, Google, Facebook o guest)
3. Completa los datos requeridos
4. El sistema valida la información y crea la cuenta
5. El usuario recibe confirmación y accede al sistema

### RF002 - Catálogo de Productos
**Descripción:** El sistema debe mostrar un catálogo dinámico de productos con filtros y búsqueda
**Prioridad:** Alta
**Actor:** Cliente
**Precondiciones:** Usuario en el sistema
**Flujo Principal:**
1. El usuario accede al catálogo
2. Ve listado de productos con imágenes, precios y descripciones
3. Puede filtrar por categoría, precio, combos
4. Puede buscar productos específicos
5. Ve información detallada al seleccionar un producto

### RF003 - Gestión de Carrito de Compras
**Descripción:** El sistema debe permitir añadir/quitar productos, aplicar cupones y proceder al checkout
**Prioridad:** Alta
**Actor:** Cliente
**Precondiciones:** Usuario navegando catálogo
**Flujo Principal:**
1. El usuario añade productos al carrito
2. Puede modificar cantidades o eliminar productos
3. Puede aplicar cupones de descuento
4. Ve resumen total con impuestos y descuentos
5. Procede al checkout

### RF004 - Proceso de Checkout y Pago
**Descripción:** El sistema debe gestionar el proceso de pago y selección de método de entrega
**Prioridad:** Alta
**Actor:** Cliente
**Precondiciones:** Carrito con productos
**Flujo Principal:**
1. El usuario revisa resumen de pedido
2. Selecciona método de entrega (pickup/delivery)
3. Ingresa datos de facturación/entrega
4. Selecciona método de pago
5. Confirma pedido y realiza pago
6. Recibe confirmación con número de pedido

### RF005 - Tracking de Pedido en Tiempo Real
**Descripción:** El sistema debe mostrar el estado actual del pedido desde preparación hasta entrega
**Prioridad:** Alta
**Actor:** Cliente
**Precondiciones:** Pedido realizado
**Flujo Principal:**
1. El usuario accede al tracking con número de pedido
2. Ve estado actual (En cocina, Preparando, Listo, En camino, Entregado)
3. Recibe notificaciones de cambios de estado
4. Ve tiempo estimado de entrega

### RF006 - Sistema de Valoraciones y Comentarios
**Descripción:** El sistema debe permitir a los clientes valorar y comentar sobre pedidos entregados
**Prioridad:** Media
**Actor:** Cliente
**Precondiciones:** Pedido entregado
**Flujo Principal:**
1. El cliente recibe solicitud de valoración post-entrega
2. Califica del 1 al 5 estrellas
3. Puede añadir comentarios opcionales
4. Envía valoración que se registra en el sistema

## MÓDULO EMPLEADO

### RF007 - Gestión de Pedidos - Rol Ventas/Mostrador
**Descripción:** Los empleados de ventas pueden ver, aceptar/rechazar pedidos y gestionar pagos presenciales
**Prioridad:** Alta
**Actor:** Empleado Ventas
**Precondiciones:** Usuario autenticado con rol Ventas
**Flujo Principal:**
1. El empleado ve lista de pedidos entrantes
2. Puede aceptar o rechazar pedidos con justificación
3. Gestiona pagos presenciales
4. Actualiza estado de pedidos
5. Ve métricas de ventas del turno

### RF008 - Gestión de Cocina - Rol Cocina
**Descripción:** Los empleados de cocina ven cola de órdenes con prioridades y temporizadores
**Prioridad:** Alta
**Actor:** Empleado Cocina
**Precondiciones:** Usuario autenticado con rol Cocina
**Flujo Principal:**
1. El empleado ve cola de pedidos priorizados
2. Selecciona pedido y marca como "Preparando"
3. Ve temporizador de tiempo de preparación
4. Puede ver detalles e ingredientes del pedido
5. Marca pedido como "Listo" al finalizar

### RF009 - Gestión de Delivery - Rol Delivery
**Descripción:** Los empleados de delivery gestionan rutas, confirmaciones de retiro y entrega
**Prioridad:** Alta
**Actor:** Empleado Delivery
**Precondiciones:** Usuario autenticado con rol Delivery
**Flujo Principal:**
1. El empleado ve pedidos listos para entrega
2. Selecciona pedidos y genera ruta sugerida
3. Confirma retiro de pedidos del local
4. Actualiza estado a "En camino"
5. Confirma entrega con firma digital del cliente

### RF010 - Sistema de Roles Múltiples
**Descripción:** El sistema debe permitir asignar múltiples roles a un mismo empleado
**Prioridad:** Media
**Actor:** Administrador/Sistema
**Precondiciones:** Usuario empleado existente
**Flujo Principal:**
1. El administrador accede a gestión de usuarios
2. Selecciona empleado y asigna roles adicionales
3. El empleado puede cambiar entre roles en su interfaz
4. Cada rol muestra funcionalidades específicas
5. Se registran acciones por rol para auditoría

## MÓDULO ADMINISTRADOR

### RF011 - Gestión de Productos (CRUD)
**Descripción:** El administrador puede crear, leer, actualizar y eliminar productos del catálogo
**Prioridad:** Alta
**Actor:** Administrador
**Precondiciones:** Usuario autenticado con rol Admin
**Flujo Principal:**
1. El administrador accede a gestión de productos
2. Puede crear nuevo producto con datos completos
3. Puede editar productos existentes
4. Puede activar/desactivar productos
5. Puede eliminar productos (con validaciones)
6. Gestiona imágenes y categorías

### RF012 - Gestión de Usuarios y Empleados
**Descripción:** El administrador puede gestionar cuentas de clientes y empleados, asignar roles
**Prioridad:** Alta
**Actor:** Administrador
**Precondiciones:** Usuario autenticado con rol Admin
**Flujo Principal:**
1. El administrador ve listado de usuarios
2. Puede crear nuevas cuentas de empleados
3. Puede asignar/modificar roles y permisos
4. Puede activar/desactivar cuentas
5. Ve actividad y métricas por usuario

### RF013 - Constructor Visual de Tienda
**Descripción:** El administrador puede personalizar la apariencia de la tienda online
**Prioridad:** Media
**Actor:** Administrador
**Precondiciones:** Usuario autenticado con rol Admin
**Flujo Principal:**
1. El administrador accede al constructor visual
2. Puede modificar colores, logos y banners
3. Puede reordenar categorías y productos destacados
4. Puede personalizar textos y descripciones
5. Ve preview en tiempo real de los cambios
6. Publica cambios al sitio web

### RF014 - Reportes y Analítica
**Descripción:** El sistema debe generar reportes de ventas, tiempos y métricas del negocio
**Prioridad:** Alta
**Actor:** Administrador
**Precondiciones:** Usuario autenticado con rol Admin
**Flujo Principal:**
1. El administrador selecciona tipo de reporte
2. Define período de tiempo a analizar
3. Ve gráficos de ventas, productos más vendidos
4. Ve métricas de tiempo de preparación y entrega
5. Ve valoraciones promedio y comentarios
6. Puede exportar reportes en PDF/Excel

### RF015 - Configuraciones Generales
**Descripción:** El administrador puede configurar parámetros generales del sistema
**Prioridad:** Media
**Actor:** Administrador
**Precondiciones:** Usuario autenticado con rol Admin
**Flujo Principal:**
1. El administrador accede a configuraciones
2. Define horarios de apertura y cierre
3. Configura costos y zonas de envío
4. Gestiona cupones y promociones globales
5. Configura métodos de pago disponibles
6. Define mensajes automáticos del sistema

## Requisitos No Funcionales

### Rendimiento
- Tiempo de respuesta de páginas < 2 segundos
- Tiempo de carga inicial < 3 segundos
- Soporte para 50 usuarios concurrentes mínimo
- Procesamiento de pagos < 5 segundos
- Actualización de estado de pedidos en tiempo real < 1 segundo

### Seguridad
- Autenticación obligatoria para empleados y administradores
- Encriptación SSL/TLS para todas las comunicaciones
- Hashing seguro de contraseñas (bcrypt)
- Validación de entrada en todos los formularios
- Protección contra SQL injection y XSS
- Sesiones seguras con timeout automático
- Logs de auditoría para acciones críticas

### Usabilidad
- Interfaz responsive para dispositivos móviles y desktop
- Compatibilidad con navegadores modernos (Chrome, Firefox, Safari, Edge)
- Navegación intuitiva con máximo 3 clics para completar compra
- Mensajes de error claros y específicos
- Indicadores visuales de estado y progreso
- Accesibilidad web básica (contraste, tamaños de fuente)

### Escalabilidad
- Arquitectura modular para crecimiento horizontal
- Base de datos optimizada para consultas frecuentes
- Caché para contenido estático y consultas comunes
- Separación de responsabilidades por módulos
- Posibilidad de balanceador de carga futuro

### Disponibilidad
- Uptime objetivo: 99.5% (máximo 3.6 horas de downtime mensual)
- Backup automático diario de base de datos
- Recuperación de datos en caso de fallo < 4 horas
- Mantenimiento programado en horarios de baja demanda
- Monitoreo básico de servicios críticos

### Compatibilidad
- Navegadores: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- Dispositivos: Desktop 1024px+, Tablet 768px+, Mobile 320px+
- Sistemas operativos: Windows 10+, macOS 10.15+, iOS 14+, Android 8+

## Restricciones Técnicas

### Tecnológicas
- Uso obligatorio de Laravel como framework backend
- MySQL como sistema de gestión de base de datos
- Containerización con Docker para desarrollo y despliegue
- Versionado de código con Git
- Frontend con Blade templates (no SPA inicialmente)

### Infraestructura
- Servidor con mínimo 2GB RAM y 20GB almacenamiento
- Conexión a internet estable para pagos online
- Certificado SSL válido para producción
- Dominio propio para el sitio web

### Regulatorias
- Cumplimiento con GDPR para datos personales
- Cumplimiento con normas locales de facturación
- Términos y condiciones claros para usuarios
- Política de privacidad visible y accesible

## Casos de Uso Principales

### CU001 - Realizar Pedido Online (Cliente)
**Actor:** Cliente
**Objetivo:** Completar una compra de comida online exitosamente
**Flujo Básico:**
1. Cliente navega catálogo y selecciona productos
2. Añade productos al carrito y revisa resumen
3. Procede al checkout e ingresa datos de entrega
4. Selecciona método de pago y confirma pedido
5. Recibe confirmación y número de tracking

**Flujos Alternativos:**
- Cliente como invitado (sin registro)
- Aplicación de cupones de descuento
- Modificación de pedido antes de confirmación

### CU002 - Gestionar Pedidos en Cocina (Empleado)
**Actor:** Empleado de Cocina
**Objetivo:** Procesar eficientemente los pedidos en cola
**Flujo Básico:**
1. Empleado ve lista priorizada de pedidos pendientes
2. Selecciona pedido y revisa ingredientes/especificaciones
3. Marca pedido como "Preparando" e inicia temporizador
4. Completa preparación y marca como "Listo"
5. Notifica a delivery o cliente para retiro

**Flujos Alternativos:**
- Cancelación de pedido por falta de ingredientes
- Modificación de tiempo estimado
- Priorización manual de pedidos urgentes

### CU003 - Administrar Catálogo (Administrador)
**Actor:** Administrador
**Objetivo:** Mantener el catálogo de productos actualizado
**Flujo Básico:**
1. Administrador accede al panel de gestión de productos
2. Crea/edita productos con información completa
3. Sube imágenes y establece precios
4. Configura categorías y disponibilidad
5. Publica cambios al catálogo público

**Flujos Alternativos:**
- Productos agotados temporalmente
- Ofertas y promociones especiales
- Productos por temporada
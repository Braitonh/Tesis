# Dashboard Analítico con Chart.js

## Objetivo

Transformar `dashboard.blade.php` en un dashboard analítico completo con gráficos interactivos usando Chart.js, mostrando métricas clave del negocio solo para administradores.

## Métricas a Implementar

1. **Tarjetas de Resumen (KPI Cards)**

- Ventas totales del mes
- Pedidos del mes
- Productos más vendidos (top 5)
- Ingresos promedio por pedido

2. **Gráficos de Ventas**

- Ventas por día (últimos 30 días) - Línea
- Ventas por semana (últimas 12 semanas) - Línea
- Ventas por mes (últimos 12 meses) - Barra

3. **Gráficos de Pedidos**

- Pedidos por estado (Pendiente, En Preparación, Listo, En Camino, Entregado, Cancelado) - Dona
- Pedidos por día de la semana - Barra
- Tasa de cancelación - Indicador

4. **Gráficos de Productos**

- Top 10 productos más vendidos - Barra horizontal
- Productos por categoría - Pie
- Productos con stock bajo - Lista/Tabla

5. **Gráficos de Ingresos**

- Ingresos por método de pago - Pie
- Ingresos diarios (últimos 30 días) - Línea

## Archivos a Modificar/Crear

### Nuevos Archivos

- `app/Livewire/Dashboard/DashboardHome.php` - Componente Livewire con lógica de estadísticas
- `resources/views/livewire/dashboard/dashboard-home.blade.php` - Vista con gráficos Chart.js

### Archivos a Modificar

- `resources/views/dashboard.blade.php` - Actualizar para mostrar componente analítico solo para admins
- `package.json` - Agregar Chart.js como dependencia
- `resources/js/app.js` - Importar y configurar Chart.js

## Implementación

1. **Instalación de Chart.js**

- Agregar `chart.js` a `package.json`
- Importar en `resources/js/app.js`
- Configurar para uso global

2. **Componente Livewire DashboardHome**

- Métodos para calcular estadísticas:
- `getVentasPorDia()` - Ventas últimos 30 días
- `getVentasPorSemana()` - Ventas últimas 12 semanas
- `getVentasPorMes()` - Ventas últimos 12 meses
- `getPedidosPorEstado()` - Conteo por estado
- `getTopProductos()` - Top 10 productos más vendidos
- `getProductosPorCategoria()` - Distribución por categoría
- `getIngresosPorMetodoPago()` - Distribución de pagos
- `getKPIs()` - Métricas principales
- Computed properties para optimizar consultas

3. **Vista Blade con Chart.js**

- Grid responsivo con tarjetas KPI
- Canvas elements para cada gráfico
- Scripts Alpine.js/JavaScript para inicializar gráficos
- Filtros de fecha (opcional para futuras mejoras)

4. **Control de Acceso**

- Verificar rol admin en el componente
- Mostrar mensaje de acceso denegado si no es admin

## Estructura de Datos

Las consultas utilizarán:

- `Pedido` con relaciones `detalles`, `transaccion`
- `DetallePedido` con relación `producto`
- `Producto` con relación `categoria`
- Agregaciones con `DB::raw()` para agrupar por fechas
- Filtros por `estado_pago = 'pagado'` para ventas reales

## Diseño UI

- Usar el mismo estilo de tarjetas que `admin-pedidos.blade.php` y `admin-productos.blade.php`
- Colores consistentes: naranja (primary), verde (éxito), rojo (alerta), azul (info)
- Grid responsivo: 1 columna móvil, 2 tablet, 3-4 desktop
- Gráficos con altura mínima de 300px para legibilidad
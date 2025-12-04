# Sketch de Presentación - Sistema de Gestión de Pedidos Digital

## Estructura General
- **Total de Slides:** 22
- **Tema Principal:** Sistema de Gestión de Pedidos Digital
- **Color Principal:** Naranja (#FF6600)

---

## Slide 1: Título
**Tipo:** Portada
- **Título Principal:** SISTEMA DE GESTIÓN
- **Subtítulo:** DE PEDIDOS DIGITAL
- **Diseño:** Fondo degradado naranja con elementos decorativos animados (blobs, círculos)

---

## Slide 2: Introducción - Problemática
**Título:** INTRODUCCIÓN
**Subtítulo:** La Problemática de los Sistemas Tradicionales

**Contenido:**
- Descripción del problema de sistemas en papel
- 6 limitaciones principales:
  1. Errores humanos frecuentes
  2. Pérdida de información
  3. Falta de trazabilidad
  4. Ineficiencia en comunicación
  5. Análisis limitado
  6. Escalabilidad nula

---

## Slide 3: Cuadro Comparativo
**Título:** CUADRO COMPARATIVO

**Tabla comparativa:** Sistema en Papel vs Sistema de Terceros vs Sistema Propio

**Características evaluadas:**
- Costo inicial
- Costo mensual
- Comisiones
- Control
- Personalización
- Datos
- Escalabilidad
- Tiempo de respuesta

**Conclusión destacada:** Sistema Propio ofrece control total, sin comisiones recurrentes

---

## Slide 4: Ventajas del Sistema Propio
**Título:** VENTAJAS DEL SISTEMA PROPIO

**6 ventajas principales:**
1. **Control Total** - Sin dependencia de terceros
2. **Rentabilidad** - Sin comisiones, ahorro $1,500-$2,500/mes
3. **Personalización** - Adaptado a procesos propios
4. **Escalabilidad** - Crecimiento sin límites
5. **Propiedad de Datos** - Datos propios, privacidad garantizada
6. **Mantenimiento** - Actualizaciones a demanda

---

## Slide 5: Flujo del Sistema - Detallado
**Título:** FLUJO DEL SISTEMA

**5 pasos principales:**
1. Cliente Realiza Pedido
2. Procesamiento de Pago
3. Cocina Recibe
4. Delivery Asigna
5. Entrega

**Estados del Pedido:**
- Pendiente
- En Preparación
- Listo
- En Camino
- Entregado
- Cancelado

**Características del Flujo:**
- Tiempo Real
- Notificaciones
- Trazabilidad
- Sincronización

---

## Slide 6: Roles del Sistema y Casos de Uso
**Título:** ROLES DEL SISTEMA

**5 roles principales:**
1. **Administrador** - Acceso completo, gestión usuarios/productos, dashboard
2. **Cliente** - Realizar pedidos, ver estado, gestionar perfil
3. **Cocina** - Ver pedidos tiempo real, actualizar estados, marcar listo
4. **Delivery** - Ver disponibles, asignar pedidos, confirmar entrega
5. **Ventas** - Gestionar pedidos, procesar pagos, reportes básicos

**Control de Acceso:**
- Autenticación
- Verificación Email
- Autorización por rol
- Redirección automática

---

## Slide 7: Flujo del Rol Cliente
**Título:** FLUJO DEL ROL: CLIENTE

**4 pasos:**
1. Realizar Pedido
2. Seguimiento
3. Recibe notificación
4. Recibe el pedido

**Características Clave:**
- Tiempo Real
- Notificaciones
- Historial Completo

**Permisos:**
- Realizar Pedidos
- Ver Estado (Tiempo Real)
- Gestionar Perfil
- Ver Historial

---

## Slide 8: Flujo del Rol Ventas
**Título:** FLUJO DEL ROL: VENTAS

**5 pasos:**
1. Gestionar Pedidos
2. Procesar Pagos
3. Actualizar Estados
4. Ver Transacciones
5. Reportes

**Características Clave:**
- Gestión Completa de Pedidos
- Procesamiento de Pagos
- Control de Estados
- Reportes Básicos

**Permisos:**
- Gestionar Todos los Pedidos
- Procesar Pagos (Efectivo)
- Modificar Estados
- Ver Transacciones

---

## Slide 9: Flujo del Rol Cocina
**Título:** FLUJO DEL ROL: COCINA

**5 pasos:**
1. Recibir Notificación (WebSocket)
2. Ver Pendientes
3. Iniciar Preparación
4. Ver Detalles
5. Marcar Listo

**Características Clave:**
- WebSocket para Notificaciones
- Tiempo Real
- Vista Detallada de Pedidos
- Sincronización Automática

**Permisos:**
- Ver Pedidos Pendientes
- Actualizar Estados
- Ver Detalles Completos
- Marcar Listo

---

## Slide 10: Flujo del Rol Delivery
**Título:** FLUJO DEL ROL: DELIVERY

**5 pasos:**
1. Ver Disponibles
2. Asignar Pedido
3. Ver Detalles
4. Confirmar Entrega
5. Notificar

**Características Clave:**
- Asignación de Pedidos
- Notificaciones Automáticas
- Vista Detalles de Entrega
- Confirmación Rápida

**Permisos:**
- Ver Pedidos Disponibles
- Asignar Pedidos
- Ver Detalles de Entrega
- Confirmar Entrega

---

## Slide 11: Panel Analítico
**Título:** PANEL ANALÍTICO

**Métricas principales:**
- Ventas del Mes (+15%)
- Pedidos del Mes (+8%)
- Promedio por Pedido
- Tasa de Cancelación (2.3%)

**Gráficos de Ventas:**
- Ventas por día (30 días)
- Ventas por semana (12 semanas)
- Ventas por mes (12 meses)

**Gráficos de Pedidos:**
- Pedidos por estado
- Pedidos por día de semana

**Gráficos de Productos:**
- Top 10 más vendidos
- Productos por categoría
- Stock bajo

**Gráficos de Ingresos:**
- Ingresos por método de pago
- Ingresos diarios

**Valor:** Decisiones basadas en datos en tiempo real

---

## Slide 12: Tecnologías Utilizadas
**Título:** TECNOLOGÍAS UTILIZADAS

**Stack tecnológico:**
1. **Laravel 12 (PHP)** - Framework MVC, ORM Eloquent
2. **Livewire 3.6** - Interactividad sin JS complejo
3. **Laravel Reverb** - WebSockets nativo
4. **Tailwind CSS 4** - Diseño rápido y responsive
5. **Docker** - Containerización
6. **MySQL 8.0** - Base de datos relacional

---

## Slide 13: Metodología de Desarrollo
**Título:** METODOLOGÍA DE DESARROLLO

**Metodología:**
- Desarrollo incremental por módulos
- Priorización de funcionalidades
- Flexibilidad para cambios

**Control de Versiones:**
- Git para trazabilidad
- Estrategia de branches (main, develop, feature)
- Workflow estructurado

**Proceso de Desarrollo:**
1. Análisis - Requisitos y arquitectura
2. Desarrollo - Módulos base y core
3. Testing - Unitario e integración
4. Optimización - Rendimiento y consultas
5. Documentación - Técnica y usuario

---

## Slide 14: Arquitectura Monolítica
**Título:** ARQUITECTURA MONOLÍTICA

**Definición:** Modelo donde toda la aplicación se desarrolla como una sola unidad

**Ventajas:**
1. **Simplicidad** - Un solo repositorio, debugging fácil
2. **Despliegue** - Un solo despliegue, rollback simple
3. **Rendimiento** - Sin latencia de red, transacciones ACID

**Patrones de Diseño:**
- Observer
- Service Layer
- Event-Driven

---

## Slide 15: Conclusión
**Título:** CONCLUSIÓN

**Comparativa:**

**Sistema Tradicional:**
- Tiempo: 15-20 min
- Tasa de error: 8-10%
- Análisis manual y limitado

**Sistema Digital Propio:**
- Tiempo: 5-8 min
- Tasa de error: <1%
- Análisis en tiempo real

**Beneficios clave:**
- Eficiencia - Reducción de errores y tiempo
- Rentabilidad - Ahorro $1,500-$2,500/mes
- Experiencia - Transparencia y conveniencia
- Datos - Decisiones informadas

**Reflexión Final:** La digitalización es necesaria para competir. Sistema propio mejora eficiencia, control total, elimina comisiones y ofrece datos valiosos.

---

## Slide 16: Agradecimientos
**Tipo:** Slide de cierre
**Título:** AGRADECIMIENTOS
**Diseño:** Similar a portada, fondo degradado naranja con decoraciones

---

## Slide 17: Preguntas y Consultas
**Título:** PREGUNTAS Y CONSULTAS
**Diseño:** Fondo blanco con decoraciones naranjas

---

## Slide 18: Demo Interactiva - Código QR
**Título:** DEMO INTERACTIVA

**Funcionalidad:**
- Input para URL del login
- Generador de código QR dinámico
- Instrucciones de uso

**Instrucciones:**
1. Ingresar URL completa del login
2. Generar QR
3. Escanear con dispositivo móvil
4. Usar credenciales de siguientes slides

---

## Slide 19: Credenciales Cliente
**Título:** CREDENCIALES: CLIENTE

**Credenciales:**
- **Email:** cliente@fooddesk.com
- **Contraseña:** abc123
- Botones para copiar al portapapeles

**Capacidades en demo:**
- Navegar catálogo
- Realizar pedido completo
- Ver estado en tiempo real
- Recibir notificaciones

---

## Slide 20: Credenciales Cocina
**Título:** CREDENCIALES: COCINA

**Credenciales:**
- **Email:** cocina@fooddesk.com
- **Contraseña:** abc123
- Botones para copiar al portapapeles

**Capacidades en demo:**
- Ver pedidos nuevos en tiempo real
- Actualizar estado de preparación
- Ver detalles completos
- Marcar pedidos como "Listo"

---

## Slide 21: Credenciales Delivery
**Título:** CREDENCIALES: DELIVERY

**Credenciales:**
- **Email:** delivery@fooddesk.com
- **Contraseña:** abc123
- Botones para copiar al portapapeles

**Capacidades en demo:**
- Ver pedidos listos para entrega
- Asignar pedidos
- Ver detalles de entrega
- Confirmar entrega y notificar

---

## Slide 22: Flujo de Demo Completo
**Título:** FLUJO DE DEMO COMPLETO

**5 pasos del flujo completo:**
1. **Cliente realiza pedido** - Navegar catálogo, agregar productos, seleccionar pago
2. **Cocina recibe notificación** - Ver pedido en tiempo real, actualizar estados, marcar listo
3. **Delivery asigna pedido** - Ver pedidos listos, asignar, actualizar a "En Camino"
4. **Cliente recibe notificación** - Notificación automática cuando pedido está en camino
5. **Delivery confirma entrega** - Confirmar entrega, estado "Entregado", notificar cliente

---

## Notas de Diseño

### Paleta de Colores
- **Color Principal:** Naranja (#FF6600, orange-500)
- **Color Secundario:** Naranja oscuro (orange-600)
- **Fondo:** Blanco
- **Texto:** Gris oscuro (gray-700, gray-800)

### Animaciones
- **slideIn:** Entrada de slides
- **float:** Movimiento flotante para decoraciones
- **pulse:** Pulsación para círculos decorativos

### Navegación
- Botones Anterior/Siguiente
- Navegación con teclado (Flechas, Espacio, Home, End)
- Contador de slides (X / 22)
- Deshabilitación de botones en extremos

### Funcionalidades Interactivas
- Generador de QR dinámico
- Copiar credenciales al portapapeles
- Validación de URLs
- Feedback visual en acciones

---

## Estructura de Contenido

### Primera Parte: Contexto (Slides 1-4)
1. Título
2. Problemática
3. Comparativa
4. Ventajas

### Segunda Parte: Sistema (Slides 5-11)
5. Flujo general
6. Roles
7-10. Flujos por rol
11. Panel analítico

### Tercera Parte: Técnico (Slides 12-15)
12. Tecnologías
13. Metodología
14. Arquitectura
15. Conclusión

### Cuarta Parte: Demo (Slides 16-22)
16. Agradecimientos
17. Preguntas
18. QR Demo
19-21. Credenciales por rol
22. Flujo completo demo


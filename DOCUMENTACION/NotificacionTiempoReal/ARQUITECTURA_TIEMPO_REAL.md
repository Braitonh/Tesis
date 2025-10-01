# Arquitectura del Sistema de Notificaciones en Tiempo Real

## 📋 Índice

1. [Introducción](#introducción)
2. [Componentes del Sistema](#componentes-del-sistema)
3. [Flujo de Comunicación](#flujo-de-comunicación)
4. [Explicación Detallada de Cada Componente](#explicación-detallada-de-cada-componente)
5. [Cómo se Conectan los Componentes](#cómo-se-conectan-los-componentes)
6. [Ejemplo Práctico Paso a Paso](#ejemplo-práctico-paso-a-paso)
7. [Diagrama Completo](#diagrama-completo)

---

## Introducción

Este sistema permite que **múltiples usuarios vean actualizaciones instantáneas** cuando se crea o modifica un pedido, sin necesidad de recargar la página. Por ejemplo:

- 👨‍🍳 **Chef en cocina** ve aparecer un nuevo pedido inmediatamente
- 👤 **Administrador** ve el cambio de estado en tiempo real
- 🛵 **Cliente** ve cuando su pedido está listo

Todo esto funciona gracias a una arquitectura de **eventos y WebSockets** que conecta el backend con el frontend.

---

## Componentes del Sistema

### 🎯 Vista General

```
┌─────────────────────────────────────────────────────────────────┐
│                        ARQUITECTURA COMPLETA                     │
└─────────────────────────────────────────────────────────────────┘

┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   Frontend   │◄──►│   Backend    │◄──►│    Cache     │
│ (Navegador)  │    │   (Laravel)  │    │   (Redis)    │
│              │    │              │    │              │
│ - Echo (JS)  │    │ - Observers  │    │ - Queue      │
│ - Livewire   │    │ - Events     │    │ - Sessions   │
└──────────────┘    │ - Reverb     │    └──────────────┘
                    └──────────────┘
                           │
                           ▼
                    ┌──────────────┐
                    │   Database   │
                    │   (MySQL)    │
                    └──────────────┘
```

### 📦 Lista de Componentes

| # | Componente | Tipo | Descripción |
|---|------------|------|-------------|
| 1 | **Model Observers** | PHP/Laravel | Detecta cambios en modelos automáticamente |
| 2 | **Events (Eventos)** | PHP/Laravel | Representa algo que pasó en el sistema |
| 3 | **Broadcasting** | PHP/Laravel | Sistema que envía eventos a canales |
| 4 | **Queue Worker** | PHP/Proceso | Procesa eventos en segundo plano |
| 5 | **Redis** | Servicio | Almacena mensajes temporalmente |
| 6 | **Reverb** | Servicio/WebSocket | Servidor que mantiene conexiones en tiempo real |
| 7 | **Laravel Echo** | JavaScript | Cliente que escucha eventos en el navegador |
| 8 | **Livewire** | PHP/JavaScript | Framework que refresca componentes automáticamente |
| 9 | **Channels** | Configuración | Define quién puede escuchar qué eventos |

---

## Flujo de Comunicación

### 🔄 Flujo Completo (Secuencia de Eventos)

```
1️⃣  Usuario crea/edita pedido en la aplicación
                    ↓
2️⃣  Laravel guarda el pedido en MySQL
                    ↓
3️⃣  Observer detecta el cambio automáticamente
                    ↓
4️⃣  Observer dispara un Evento (PedidoCreado/PedidoCambioEstado)
                    ↓
5️⃣  Laravel Broadcasting encola el evento en Redis
                    ↓
6️⃣  Queue Worker procesa el evento (modo sync = inmediato)
                    ↓
7️⃣  Broadcasting envía el evento a Reverb (WebSocket Server)
                    ↓
8️⃣  Reverb transmite el evento a todos los clientes conectados
                    ↓
9️⃣  Laravel Echo (JS) recibe el evento en el navegador
                    ↓
🔟  Livewire refresca el componente automáticamente
                    ↓
✅  Usuario ve la actualización en pantalla (sin recargar)
```

---

## Explicación Detallada de Cada Componente

### 1️⃣ Model Observers (Observadores de Modelos)

**¿Qué es?**
- Es una clase PHP que "observa" un modelo Eloquent
- Se ejecuta automáticamente cuando algo cambia en ese modelo

**¿Para qué sirve?**
- Detectar cuando se crea, actualiza o elimina un registro
- Disparar acciones automáticas sin modificar el código del controlador

**¿Dónde está?**
- Archivo: `app/Observers/PedidoObserver.php`
- Registro: `app/Providers/AppServiceProvider.php`

**Código:**
```php
// app/Observers/PedidoObserver.php
class PedidoObserver
{
    public function created(Pedido $pedido): void
    {
        // Se ejecuta AUTOMÁTICAMENTE cuando se crea un pedido
        if ($pedido->estado_pago === 'pagado') {
            event(new PedidoCreado($pedido));
        }
    }

    public function updated(Pedido $pedido): void
    {
        // Se ejecuta AUTOMÁTICAMENTE cuando se actualiza un pedido
        if ($pedido->isDirty('estado')) {
            $estadoAnterior = $pedido->getOriginal('estado');
            $estadoNuevo = $pedido->estado;

            event(new PedidoCambioEstado($pedido, $estadoAnterior, $estadoNuevo));
        }
    }
}

// app/Providers/AppServiceProvider.php
public function boot(): void
{
    Pedido::observe(PedidoObserver::class); // ← Registra el observer
}
```

**Conexión con otros componentes:**
- 📥 **Recibe**: Cambios en el modelo `Pedido` (de Laravel Eloquent)
- 📤 **Envía**: Dispara eventos (`PedidoCreado`, `PedidoCambioEstado`)

---

### 2️⃣ Events (Eventos de Broadcasting)

**¿Qué es?**
- Una clase PHP que representa algo que pasó en el sistema
- Implementa `ShouldBroadcast` para que se transmita vía WebSocket

**¿Para qué sirve?**
- Encapsular información sobre lo que pasó
- Definir a qué canales se debe enviar la notificación
- Estructurar los datos que recibirá el frontend

**¿Dónde está?**
- `app/Events/PedidoCreado.php` - Cuando se crea un pedido
- `app/Events/PedidoCambioEstado.php` - Cuando cambia el estado
- `app/Events/PedidoCancelado.php` - Cuando se cancela

**Código:**
```php
// app/Events/PedidoCreado.php
class PedidoCreado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pedido $pedido) {}

    // Define a QUÉ CANALES se envía este evento
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('pedidos.' . $this->pedido->id), // ← Canal privado del cliente
            new Channel('cocina'),                               // ← Canal público de cocina
            new Channel('admin'),                                // ← Canal público de admin
        ];
    }

    // Nombre del evento que escuchará JavaScript
    public function broadcastAs(): string
    {
        return 'pedido.creado';
    }

    // Datos que recibirá el frontend
    public function broadcastWith(): array
    {
        return [
            'pedido' => [
                'id' => $this->pedido->id,
                'numero_pedido' => $this->pedido->numero_pedido,
                'estado' => $this->pedido->estado,
                'total' => $this->pedido->total,
                // ... más datos
            ],
            'mensaje' => "Nuevo pedido {$this->pedido->numero_pedido} recibido",
        ];
    }
}
```

**Estructura de Canales:**
```
PedidoCreado se envía a:
├── pedidos.{id} (privado) → Solo el cliente dueño puede escuchar
├── cocina (público)       → Todos en módulo cocina pueden escuchar
└── admin (público)        → Todos en módulo admin pueden escuchar

PedidoCambioEstado se envía a:
├── pedidos.{id} (privado)
├── cocina (si estado = en_preparacion o listo)
└── admin (siempre)
```

**Conexión con otros componentes:**
- 📥 **Recibe**: Llamada desde `PedidoObserver` con `event(new PedidoCreado(...))`
- 📤 **Envía**: Se encola en Redis para ser procesado por Queue Worker

---

### 3️⃣ Broadcasting (Sistema de Transmisión)

**¿Qué es?**
- Sistema de Laravel que maneja el envío de eventos a canales
- Configurado en `config/broadcasting.php`

**¿Para qué sirve?**
- Conectar Laravel con el servidor WebSocket (Reverb)
- Encolar eventos para procesamiento asíncrono
- Gestionar autenticación de canales privados

**¿Dónde está?**
- Configuración: `config/broadcasting.php`
- Variables de entorno: `.env`

**Configuración:**
```php
// config/broadcasting.php
'connections' => [
    'reverb' => [
        'driver' => 'reverb',
        'key' => env('REVERB_APP_KEY'),
        'secret' => env('REVERB_APP_SECRET'),
        'app_id' => env('REVERB_APP_ID'),
        'options' => [
            'host' => env('REVERB_SERVER', env('REVERB_HOST')), // ← Nombre del contenedor Docker
            'port' => env('REVERB_PORT', 8080),
            'scheme' => env('REVERB_SCHEME', 'http'),
        ],
    ],
],
```

**Variables importantes en .env:**
```env
# Para que Laravel se conecte a Reverb
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=tesis-app
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret
REVERB_HOST=0.0.0.0          # ← Reverb escucha en todas las interfaces
REVERB_SERVER=reverb          # ← Laravel se conecta al contenedor 'reverb'
REVERB_PORT=8080
REVERB_SCHEME=http

# Para que JavaScript se conecte a Reverb
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="localhost"  # ← Navegador se conecta a localhost
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**Conexión con otros componentes:**
- 📥 **Recibe**: Eventos con `ShouldBroadcast`
- 📤 **Envía**: Eventos a Redis Queue y luego a Reverb

---

### 4️⃣ Queue Worker (Procesador de Cola)

**¿Qué es?**
- Un proceso que corre continuamente en segundo plano
- Procesa trabajos (jobs) de la cola de forma asíncrona

**¿Para qué sirve?**
- Procesar eventos de broadcasting sin bloquear la aplicación
- Enviar eventos a Reverb de forma eficiente

**¿Dónde está?**
- Servicio: `docker-compose.yml` (contenedor `queue`)
- Configuración: `.env` (`QUEUE_CONNECTION=sync`)

**Configuración en Docker:**
```yaml
# docker-compose.yml
queue:
  container_name: tesis-queue
  command: php artisan queue:work --tries=3 --timeout=90 --sleep=3
  volumes:
    - ./:/var/www
  depends_on:
    - redis
    - db
  restart: unless-stopped
```

**Modos de operación:**

| Modo | Configuración | Comportamiento |
|------|---------------|----------------|
| **sync** | `QUEUE_CONNECTION=sync` | Procesa eventos INMEDIATAMENTE (sin cola) |
| **database** | `QUEUE_CONNECTION=database` | Encola en MySQL, requiere worker corriendo |
| **redis** | `QUEUE_CONNECTION=redis` | Encola en Redis, requiere worker corriendo |

**Actualmente configurado:**
```env
QUEUE_CONNECTION=sync  # ← Procesa inmediatamente, sin cola
```

Esto significa que cuando se dispara un evento:
1. Se procesa **inmediatamente** (no se encola)
2. Se envía a Reverb **al instante**
3. **No necesita** el contenedor `queue` corriendo

**Conexión con otros componentes:**
- 📥 **Recibe**: Jobs de broadcasting desde Redis/Database
- 📤 **Envía**: Eventos procesados a Reverb vía HTTP

---

### 5️⃣ Redis (Cache y Message Broker)

**¿Qué es?**
- Base de datos en memoria ultra-rápida
- Funciona como cache y sistema de mensajería

**¿Para qué sirve?**
- Almacenar jobs de la cola temporalmente (cuando `QUEUE_CONNECTION=redis`)
- Cache de sesiones y datos
- Mejorar rendimiento general de la aplicación

**¿Dónde está?**
- Servicio: `docker-compose.yml` (contenedor `redis`)
- Puerto: `6379`

**Configuración en Docker:**
```yaml
# docker-compose.yml
redis:
  image: redis:alpine
  container_name: tesis-redis
  ports:
    - "6379:6379"
  networks:
    - tesis-network
```

**Variables en .env:**
```env
REDIS_HOST=redis          # ← Nombre del contenedor Docker
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_STORE=redis         # ← Usar Redis para cache
```

**Uso actual:**
- ✅ **Cache**: Almacena sesiones, configuración en cache
- ⏸️ **Queue**: No se usa actualmente (modo sync)

**Conexión con otros componentes:**
- 📥 **Recibe**: Datos de cache de Laravel, jobs de cola (si se usa)
- 📤 **Envía**: Datos cached a Laravel, jobs al Queue Worker

---

### 6️⃣ Reverb (Servidor WebSocket)

**¿Qué es?**
- Servidor WebSocket nativo de Laravel 11+
- Mantiene conexiones bidireccionales persistentes con navegadores

**¿Para qué sirve?**
- Recibir eventos de Laravel vía HTTP
- Transmitir eventos a todos los navegadores conectados vía WebSocket
- Gestionar suscripciones a canales

**¿Dónde está?**
- Servicio: `docker-compose.yml` (contenedor `reverb`)
- Puerto: `8080`
- Comando: `php artisan reverb:start`

**Configuración en Docker:**
```yaml
# docker-compose.yml
reverb:
  container_name: tesis-reverb
  command: php artisan reverb:start --host=0.0.0.0 --port=8080
  volumes:
    - ./:/var/www
  ports:
    - "8080:8080"  # ← Puerto expuesto al host
  depends_on:
    - redis
    - db
  restart: unless-stopped
```

**Cómo funciona:**

```
┌─────────────────────────────────────────────────┐
│              REVERB (WebSocket Server)          │
├─────────────────────────────────────────────────┤
│                                                 │
│  Entrada (HTTP)                                │
│  ← Laravel Broadcasting envía eventos          │
│                                                 │
│  Canales activos:                              │
│  ├── cocina (3 clientes conectados)           │
│  ├── admin (2 clientes conectados)            │
│  └── pedidos.15 (1 cliente conectado)         │
│                                                 │
│  Salida (WebSocket)                            │
│  → Transmite a todos los clientes suscritos   │
│                                                 │
└─────────────────────────────────────────────────┘
```

**Protocolo de comunicación:**

1. **Navegador se conecta**:
   ```
   ws://localhost:8080/app/local-key
   ```

2. **Navegador se suscribe a canal**:
   ```json
   {
     "event": "pusher:subscribe",
     "data": {
       "channel": "cocina"
     }
   }
   ```

3. **Reverb recibe evento de Laravel**:
   ```
   POST http://reverb:8080/apps/tesis-app/events
   Body: { evento de broadcasting }
   ```

4. **Reverb transmite a clientes suscritos**:
   ```json
   {
     "event": "pedido.creado",
     "channel": "cocina",
     "data": { ... datos del pedido ... }
   }
   ```

**Conexión con otros componentes:**
- 📥 **Recibe**: Eventos de Laravel Broadcasting vía HTTP
- 📤 **Envía**: Eventos a navegadores vía WebSocket

---

### 7️⃣ Laravel Echo (Cliente JavaScript)

**¿Qué es?**
- Librería JavaScript que conecta el navegador con Reverb
- Cliente WebSocket que escucha eventos en tiempo real

**¿Para qué sirve?**
- Establecer conexión WebSocket con Reverb
- Suscribirse a canales
- Escuchar eventos específicos
- Ejecutar código JavaScript cuando llega un evento

**¿Dónde está?**
- Configuración: `resources/js/bootstrap.js`
- Instalación: `package.json` (laravel-echo + pusher-js)

**Inicialización:**
```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,      // ← 'local-key'
    wsHost: import.meta.env.VITE_REVERB_HOST,      // ← 'localhost'
    wsPort: import.meta.env.VITE_REVERB_PORT,      // ← 8080
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,                                // ← http (no https)
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});
```

**Uso en componentes:**
```javascript
// resources/views/livewire/cocina/cocina.blade.php
@push('scripts')
<script>
    if (window.Echo) {
        // Suscribirse al canal 'cocina'
        window.Echo.channel('cocina')
            // Escuchar evento 'pedido.creado'
            .listen('.pedido.creado', (e) => {
                console.log('Nuevo pedido creado:', e);
                // Aquí iría código para mostrar notificación
            })
            // Escuchar evento 'pedido.cambio-estado'
            .listen('.pedido.cambio-estado', (e) => {
                console.log('Estado cambió:', e);
            });
    }
</script>
@endpush
```

**Tipos de canales:**

| Tipo | Sintaxis | Autenticación | Ejemplo |
|------|----------|---------------|---------|
| **Público** | `channel('nombre')` | No requiere | `Echo.channel('cocina')` |
| **Privado** | `private('nombre')` | Requiere | `Echo.private('pedidos.15')` |
| **Presencia** | `join('nombre')` | Requiere | `Echo.join('chat')` (no usado) |

**Conexión con otros componentes:**
- 📥 **Recibe**: Eventos de Reverb vía WebSocket
- 📤 **Envía**: Puede disparar eventos de Livewire

---

### 8️⃣ Livewire (Framework de Componentes Reactivos)

**¿Qué es?**
- Framework PHP que permite crear componentes interactivos
- Combina PHP y JavaScript sin escribir mucho JS

**¿Para qué sirve?**
- Refrescar componentes automáticamente cuando llegan eventos
- Gestionar estado del componente (propiedades)
- Sincronizar frontend y backend sin recargar página

**¿Dónde está?**
- Componentes PHP: `app/Livewire/`
- Vistas Blade: `resources/views/livewire/`

**Componente de ejemplo:**
```php
// app/Livewire/Cocina/Cocina.php
class Cocina extends Component
{
    // Define qué eventos Livewire debe escuchar
    protected function getListeners()
    {
        return [
            "echo:cocina,.pedido.creado" => '$refresh',           // ← Refresca el componente
            "echo:cocina,.pedido.cambio-estado" => '$refresh',
            "echo:cocina,.pedido.cancelado" => '$refresh',
        ];
    }

    // Computed property que se recalcula al refrescar
    public function getPedidosPendientesProperty()
    {
        return Pedido::where('estado', 'pendiente')
            ->where('estado_pago', 'pagado')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.cocina.cocina', [
            'pedidosPendientes' => $this->pedidosPendientes, // ← Se actualiza automáticamente
        ]);
    }
}
```

**Cómo funciona `$refresh`:**

```
1. Echo recibe evento vía WebSocket
           ↓
2. Livewire detecta el listener "echo:cocina,.pedido.creado"
           ↓
3. Livewire ejecuta la acción '$refresh'
           ↓
4. Livewire re-renderiza el componente
           ↓
5. Se ejecutan las computed properties (getPedidosPendientesProperty)
           ↓
6. Vista se actualiza con nuevos datos
           ↓
7. Usuario ve el cambio en pantalla
```

**Ventajas de usar Livewire:**
- ✅ No necesitas escribir código AJAX
- ✅ El componente se refresca automáticamente
- ✅ Mantiene el estado entre refreshes
- ✅ Se integra perfectamente con Laravel Echo

**Conexión con otros componentes:**
- 📥 **Recibe**: Eventos de Echo vía `getListeners()`
- 📤 **Envía**: Puede disparar eventos PHP con `$this->dispatch()`

---

### 9️⃣ Channels (Canales de Broadcasting)

**¿Qué es?**
- Configuración que define quién puede escuchar qué canales
- Sistema de autorización para canales privados

**¿Para qué sirve?**
- Proteger canales privados (solo el dueño puede escuchar)
- Definir lógica de autorización
- Permitir canales públicos sin restricciones

**¿Dónde está?**
- `routes/channels.php`

**Configuración:**
```php
// routes/channels.php

// Canal privado - Solo el dueño del pedido puede escuchar
Broadcast::channel('pedidos.{pedidoId}', function ($user, $pedidoId) {
    $pedido = \App\Models\Pedido::find($pedidoId);
    // Retorna true si el usuario autenticado es el dueño
    return $pedido && (int) $user->id === (int) $pedido->user_id;
});

// Canal público - Cualquier usuario autenticado puede escuchar
Broadcast::channel('cocina', function ($user) {
    return auth()->check(); // Solo requiere estar logueado
});

// Canal público - Cualquier usuario autenticado puede escuchar
Broadcast::channel('admin', function ($user) {
    return auth()->check();
});

// Canal público - Cualquier usuario autenticado puede escuchar
Broadcast::channel('delivery', function ($user) {
    return auth()->check();
});
```

**Flujo de autorización:**

```
1. Cliente intenta suscribirse a canal privado:
   Echo.private('pedidos.15')
           ↓
2. Echo envía request de autorización a Laravel:
   POST /broadcasting/auth
   Body: { channel_name: 'private-pedidos.15' }
           ↓
3. Laravel ejecuta callback de channels.php:
   function ($user, $pedidoId) { ... }
           ↓
4. Si retorna true → Autorizado
   Si retorna false → Denegado
           ↓
5. Reverb permite/deniega la suscripción
```

**Diferencias entre tipos de canales:**

| Aspecto | Canal Público | Canal Privado |
|---------|---------------|---------------|
| **Sintaxis** | `channel('nombre')` | `private('nombre')` |
| **Autorización** | No requiere | Requiere callback en routes/channels.php |
| **Prefijo** | Sin prefijo | `private-` |
| **Uso** | Datos públicos (cocina, admin) | Datos de usuario (pedidos específicos) |

**Conexión con otros componentes:**
- 📥 **Recibe**: Requests de autorización desde Laravel Echo
- 📤 **Envía**: Respuesta (autorizado/denegado) a Reverb

---

## Cómo se Conectan los Componentes

### 🔗 Mapa de Conexiones

```
┌─────────────────────────────────────────────────────────────────┐
│                    MAPA DE INTERACCIONES                         │
└─────────────────────────────────────────────────────────────────┘

[Usuario crea pedido]
        │
        ▼
[Modelo Pedido] ──────────┐
        │                  │
        │ guarda en        │ detecta cambio
        ▼                  │
   [MySQL DB]              │
                           ▼
                  [PedidoObserver]
                           │
                           │ dispara evento
                           ▼
                    [PedidoCreado]
                           │
                           │ implementa ShouldBroadcast
                           ▼
                  [Broadcasting System]
                           │
                           │ (modo sync = inmediato)
                           ▼
                       [Reverb] ◄────── conexión WebSocket ─────┐
                           │                                     │
                    transmite a clientes                         │
                           │                                     │
              ┌────────────┼────────────┐                       │
              ▼            ▼            ▼                        │
        [Navegador 1] [Navegador 2] [Navegador 3]              │
              │            │            │                        │
        [Laravel Echo] [Laravel Echo] [Laravel Echo]            │
              │            │            │                        │
         escucha evento                                          │
              │                                                  │
              ▼                                                  │
        [Livewire]                                              │
              │                                                  │
         $refresh                                                │
              │                                                  │
              ▼                                                  │
    [Vista actualizada] ────── mantiene conexión ──────────────┘
```

### 📊 Tabla de Interacciones

| Desde | Hacia | Protocolo | Datos |
|-------|-------|-----------|-------|
| Usuario | Laravel | HTTP POST | Formulario de pedido |
| Laravel | MySQL | SQL | INSERT/UPDATE pedido |
| MySQL | Observer | Evento Eloquent | Modelo actualizado |
| Observer | Event | Llamada PHP | `event(new PedidoCreado())` |
| Event | Broadcasting | Laravel Queue | Evento serializado |
| Broadcasting | Reverb | HTTP POST | JSON con datos del evento |
| Reverb | Navegador | WebSocket | Mensaje en tiempo real |
| Navegador (Echo) | Livewire | JavaScript | Dispara listener de Livewire |
| Livewire | Laravel | HTTP POST | Request de refresh |
| Laravel | Navegador | HTTP Response | HTML actualizado |

---

## Ejemplo Práctico Paso a Paso

### 📝 Escenario: Cliente Crea un Nuevo Pedido

Vamos a seguir **exactamente** qué pasa cuando un cliente crea un pedido y cómo llega la notificación al chef en cocina.

#### Paso 1: Cliente envía formulario

```
🌐 Navegador del Cliente
    │
    │ POST /cliente/checkout
    │ Body: { productos: [...], direccion: "...", ... }
    ▼
```

#### Paso 2: Laravel procesa el pedido

```php
// app/Http/Controllers/CheckoutController.php (ejemplo)
public function store(Request $request)
{
    $pedido = Pedido::create([
        'user_id' => auth()->id(),
        'numero_pedido' => 'ORD-' . time(),
        'estado' => 'pendiente',
        'estado_pago' => 'pagado',
        'total' => 150.00,
        // ... otros datos
    ]);

    // Laravel guarda en MySQL
    // ↓
}
```

```
📁 MySQL Database
    │
    │ INSERT INTO pedidos ...
    ▼
    Pedido guardado con ID = 15
```

#### Paso 3: Observer detecta el cambio

```php
// app/Observers/PedidoObserver.php
public function created(Pedido $pedido): void
{
    // Este método se ejecuta AUTOMÁTICAMENTE después del INSERT

    if ($pedido->estado_pago === 'pagado') {
        // ✅ Pedido está pagado, disparar evento
        event(new PedidoCreado($pedido));
    }
}
```

```
🔍 PedidoObserver
    │
    │ Detecta: Nuevo pedido #15 creado
    │ Condición: estado_pago = 'pagado' ✅
    ▼
    Dispara: event(new PedidoCreado($pedido))
```

#### Paso 4: Evento se crea y se encola

```php
// app/Events/PedidoCreado.php
class PedidoCreado implements ShouldBroadcast
{
    public function __construct(public Pedido $pedido) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('pedidos.15'),  // ← Canal privado del cliente
            new Channel('cocina'),              // ← Canal público de cocina
            new Channel('admin'),               // ← Canal público de admin
        ];
    }

    public function broadcastAs(): string
    {
        return 'pedido.creado'; // ← Nombre del evento
    }

    public function broadcastWith(): array
    {
        return [
            'pedido' => [
                'id' => 15,
                'numero_pedido' => 'ORD-1696123456',
                'estado' => 'pendiente',
                'total' => 150.00,
                // ...
            ],
            'mensaje' => 'Nuevo pedido ORD-1696123456 recibido',
        ];
    }
}
```

```
📦 Evento PedidoCreado
    │
    │ Contiene: Pedido #15
    │ Se enviará a: pedidos.15, cocina, admin
    ▼
```

#### Paso 5: Broadcasting procesa el evento

```
⚙️ Broadcasting System (config/broadcasting.php)
    │
    │ Conexión: 'reverb'
    │ Modo: QUEUE_CONNECTION=sync (inmediato)
    ▼
    Procesa INMEDIATAMENTE (no encola)
```

**Con modo sync:**
```
Broadcasting
    │
    │ No pasa por Redis
    │ No espera queue worker
    ▼
    Envía DIRECTAMENTE a Reverb
```

#### Paso 6: Broadcasting envía a Reverb

```
📡 HTTP POST Request
    │
    │ URL: http://reverb:8080/apps/tesis-app/events
    │ Headers:
    │   Authorization: Bearer local-secret
    │   Content-Type: application/json
    │ Body:
    {
      "channels": ["cocina", "admin", "private-pedidos.15"],
      "name": "pedido.creado",
      "data": {
        "pedido": { ... },
        "mensaje": "Nuevo pedido ORD-1696123456 recibido"
      }
    }
    ▼
```

#### Paso 7: Reverb recibe y distribuye

```
🌐 Reverb (WebSocket Server)
    │
    │ Recibe evento vía HTTP POST
    │ Tiene 5 clientes WebSocket conectados:
    │
    ├─ Cliente 1: Suscrito a 'cocina' (Chef)
    ├─ Cliente 2: Suscrito a 'cocina' (Chef backup)
    ├─ Cliente 3: Suscrito a 'admin' (Admin)
    ├─ Cliente 4: Suscrito a 'private-pedidos.15' (Cliente dueño)
    └─ Cliente 5: Suscrito a 'private-pedidos.20' (Otro cliente)
    │
    │ Reverb envía el evento a:
    │   ✅ Cliente 1 (está suscrito a 'cocina')
    │   ✅ Cliente 2 (está suscrito a 'cocina')
    │   ✅ Cliente 3 (está suscrito a 'admin')
    │   ✅ Cliente 4 (está suscrito a 'private-pedidos.15')
    │   ❌ Cliente 5 (no está suscrito a ninguno de esos canales)
    ▼
```

#### Paso 8: Echo recibe el evento en el navegador

```javascript
// En el navegador del Chef (Módulo Cocina)

// resources/views/livewire/cocina/cocina.blade.php
window.Echo.channel('cocina')
    .listen('.pedido.creado', (e) => {
        console.log('Nuevo pedido creado:', e);
        // e.pedido.id = 15
        // e.pedido.numero_pedido = 'ORD-1696123456'
        // e.mensaje = 'Nuevo pedido ORD-1696123456 recibido'
    });
```

```
🌐 Navegador del Chef
    │
    │ WebSocket recibe mensaje:
    {
      "event": "pedido.creado",
      "channel": "cocina",
      "data": { ... }
    }
    │
    │ Laravel Echo detecta el evento
    │ Ejecuta el callback .listen()
    ▼
    console.log() se ejecuta ✅
```

#### Paso 9: Livewire refresca el componente

```php
// app/Livewire/Cocina/Cocina.php
protected function getListeners()
{
    return [
        "echo:cocina,.pedido.creado" => '$refresh', // ← Ejecuta $refresh automáticamente
    ];
}
```

```
⚡ Livewire detecta el evento
    │
    │ Listener: "echo:cocina,.pedido.creado"
    │ Acción: '$refresh'
    ▼
    Livewire hace request AJAX a Laravel para re-renderizar
```

#### Paso 10: Componente se actualiza

```
🔄 Livewire Refresh
    │
    │ POST /__livewire/update
    │ Component: Cocina
    │ Action: $refresh
    ▼
    Laravel re-ejecuta el método render()
    │
    ▼
    getPedidosPendientesProperty() se recalcula
    │
    │ SELECT * FROM pedidos WHERE estado = 'pendiente' ...
    ▼
    ¡Ahora incluye el Pedido #15!
    │
    ▼
    Vista HTML se genera con el nuevo pedido
    │
    ▼
    Response enviado al navegador
    │
    ▼
    Livewire actualiza el DOM sin recargar
```

#### Paso 11: Chef ve el nuevo pedido

```
👨‍🍳 Pantalla del Chef
    │
    │ ANTES: 3 pedidos pendientes
    │
    ▼ (Actualización automática)
    │
    │ DESPUÉS: 4 pedidos pendientes
    │
    └─── ¡Nuevo pedido #15 aparece en la lista!
         Sin recargar la página ✅
```

### ⏱️ Tiempo Total

```
┌──────────────────────────┬──────────┐
│ Paso                     │ Tiempo   │
├──────────────────────────┼──────────┤
│ Cliente envía form       │ 0ms      │
│ Laravel guarda en DB     │ ~50ms    │
│ Observer dispara evento  │ ~1ms     │
│ Broadcasting a Reverb    │ ~10ms    │
│ Reverb a navegadores     │ ~5ms     │
│ Livewire refresh         │ ~100ms   │
├──────────────────────────┼──────────┤
│ TOTAL                    │ ~166ms   │
└──────────────────────────┴──────────┘

¡Menos de 200 milisegundos!
```

---

## Diagrama Completo

### 🎨 Arquitectura Visual Completa

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ARQUITECTURA COMPLETA                                │
│                    Sistema de Notificaciones en Tiempo Real                  │
└─────────────────────────────────────────────────────────────────────────────┘

                              ┌─────────────┐
                              │   USUARIO   │
                              │  (Cliente)  │
                              └──────┬──────┘
                                     │
                              HTTP POST /checkout
                                     │
                                     ▼
    ┌────────────────────────────────────────────────────────────┐
    │                    BACKEND (Laravel)                        │
    │                                                             │
    │  ┌──────────────┐          ┌──────────────┐              │
    │  │ Controller   │──save──→ │    Pedido    │              │
    │  │  (HTTP)      │          │    Model     │              │
    │  └──────────────┘          └──────┬───────┘              │
    │                                    │                       │
    │                            INSERT/UPDATE                   │
    │                                    │                       │
    │                                    ▼                       │
    │                          ┌─────────────────┐              │
    │                          │  MySQL Database │              │
    │                          └─────────────────┘              │
    │                                    │                       │
    │                          Eloquent Event                    │
    │                                    │                       │
    │                                    ▼                       │
    │                          ┌─────────────────┐              │
    │                          │ PedidoObserver  │              │
    │                          │   (Detector)    │              │
    │                          └────────┬────────┘              │
    │                                   │                        │
    │                           event(new ...)                   │
    │                                   │                        │
    │                                   ▼                        │
    │                          ┌─────────────────┐              │
    │                          │  PedidoCreado   │              │
    │                          │     (Event)     │              │
    │                          │ ShouldBroadcast │              │
    │                          └────────┬────────┘              │
    │                                   │                        │
    │                          broadcastOn()                     │
    │                                   │                        │
    │          ┌────────────────────────┼────────────────┐      │
    │          │                        │                │       │
    │          ▼                        ▼                ▼       │
    │   Channel('cocina')     Channel('admin')  PrivateChannel │
    │                                                            │
    │                          ┌─────────────────┐              │
    │                          │  Broadcasting   │              │
    │                          │     System      │              │
    │                          └────────┬────────┘              │
    │                                   │                        │
    │                     QUEUE_CONNECTION=sync                  │
    │                        (procesa inmediato)                 │
    │                                   │                        │
    └───────────────────────────────────┼────────────────────────┘
                                        │
                                 HTTP POST
                              (evento serializado)
                                        │
                                        ▼
    ┌────────────────────────────────────────────────────────────┐
    │                  REVERB (WebSocket Server)                  │
    │                     Puerto: 8080                            │
    │                                                             │
    │  Canales activos:                                          │
    │  ├── cocina      (3 conexiones WebSocket)                 │
    │  ├── admin       (2 conexiones WebSocket)                 │
    │  └── pedidos.15  (1 conexión WebSocket)                   │
    │                                                             │
    │  Distribuye evento a todos los suscritos                   │
    └──────────┬──────────────────────┬──────────────────────────┘
               │                      │
         WebSocket                WebSocket
               │                      │
    ┌──────────▼─────────┐   ┌───────▼──────────┐
    │   NAVEGADOR 1      │   │   NAVEGADOR 2    │
    │   (Chef Cocina)    │   │  (Admin Panel)   │
    │                    │   │                  │
    │ ┌────────────────┐ │   │ ┌──────────────┐ │
    │ │ Laravel Echo   │ │   │ │ Laravel Echo │ │
    │ │  (JavaScript)  │ │   │ │ (JavaScript) │ │
    │ └───────┬────────┘ │   │ └──────┬───────┘ │
    │         │          │   │        │         │
    │  Echo.channel()   │   │  Echo.channel()  │
    │         │          │   │        │         │
    │         ▼          │   │        ▼         │
    │ ┌────────────────┐ │   │ ┌──────────────┐ │
    │ │    Livewire    │ │   │ │   Livewire   │ │
    │ │   Component    │ │   │ │  Component   │ │
    │ │                │ │   │ │              │ │
    │ │  getListeners()│ │   │ │ getListeners()│ │
    │ │      ↓         │ │   │ │      ↓       │ │
    │ │   $refresh     │ │   │ │   $refresh   │ │
    │ └───────┬────────┘ │   │ └──────┬───────┘ │
    │         │          │   │        │         │
    │   AJAX Request    │   │  AJAX Request    │
    │         │          │   │        │         │
    │         ▼          │   │        ▼         │
    │ ┌────────────────┐ │   │ ┌──────────────┐ │
    │ │  Vista HTML    │ │   │ │  Vista HTML  │ │
    │ │  Actualizada   │ │   │ │ Actualizada  │ │
    │ └────────────────┘ │   │ └──────────────┘ │
    │                    │   │                  │
    │ ✅ Lista con      │   │ ✅ Lista con     │
    │    nuevo pedido   │   │    nuevo pedido  │
    └────────────────────┘   └──────────────────┘
```

### 📋 Leyenda de Colores y Símbolos

```
🌐 = Navegador/Cliente
📁 = Base de Datos
⚙️ = Proceso/Servicio
📦 = Evento/Dato
🔍 = Observer/Detector
📡 = Comunicación HTTP
⚡ = Comunicación WebSocket
🔄 = Actualización/Refresh
✅ = Éxito/Completado
❌ = Denegado/Falló
```

---

## Resumen Ejecutivo

### 🎯 Conceptos Clave

1. **Observer Pattern**: Detecta cambios automáticamente sin modificar código existente
2. **Event Broadcasting**: Permite enviar eventos desde Laravel a clientes externos
3. **WebSocket**: Conexión persistente bidireccional para comunicación en tiempo real
4. **Queue Sync**: Modo síncrono que procesa eventos inmediatamente sin cola
5. **Livewire Listeners**: Refrescan componentes automáticamente cuando llegan eventos
6. **Canales Privados**: Protegen información sensible con autorización
7. **Canales Públicos**: Permiten broadcast a múltiples clientes sin restricciones

### ✅ Ventajas del Sistema

- ⚡ **Instantáneo**: Actualizaciones en <200ms
- 🔒 **Seguro**: Canales privados con autorización
- 📡 **Eficiente**: Una conexión WebSocket maneja múltiples eventos
- 🔄 **Automático**: No requiere código manual de sincronización
- 🎯 **Escalable**: Puede manejar muchos clientes simultáneos
- 🛠️ **Mantenible**: Cada componente tiene responsabilidad única

### 📊 Flujo Simplificado

```
Usuario → Laravel → Observer → Event → Broadcasting → Reverb → Echo → Livewire → Vista
```

### 🔧 Comandos Útiles

```bash
# Ver logs de Reverb
make logs-reverb

# Ver logs del queue worker (si se usa)
make queue-logs

# Ver estado de servicios
docker-compose ps

# Reiniciar Reverb
docker-compose restart reverb

# Compilar assets frontend
docker-compose exec app npm run build

# Limpiar cache de Laravel
docker-compose exec app php artisan config:clear
```

---

**Última actualización:** Octubre 1, 2025
**Versión del documento:** 1.0.0
**Autor:** Sistema de Tesis - Combate Mborore

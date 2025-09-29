# 🛒 Documentación del Carrito de Compras

## Tabla de Contenidos

1. [Introducción](#introducción)
2. [Arquitectura General](#arquitectura-general)
3. [Trait HasShoppingCart](#trait-hasshoppingcart)
4. [Componentes Livewire](#componentes-livewire)
5. [Sistema de Eventos](#sistema-de-eventos)
6. [Vistas Blade](#vistas-blade)
7. [Flujo Completo](#flujo-completo)
8. [Problemas Resueltos](#problemas-resueltos)
9. [Mejoras Futuras](#mejoras-futuras)

---

## Introducción

El sistema de carrito de compras está implementado utilizando **Laravel 12**, **Livewire 3.6**, y **Tailwind CSS 4.0**. La arquitectura está diseñada para ser modular, reutilizable y escalable.

### Características Principales

- ✅ Almacenamiento en sesión PHP
- ✅ Actualización en tiempo real sin recargar la página
- ✅ Modal centrado con diseño moderno
- ✅ Sistema de confirmación para acciones destructivas
- ✅ Comunicación entre componentes mediante eventos
- ✅ Animaciones CSS suaves
- ✅ Sin dependencias de Alpine.js en el modal (solo Livewire)

---

## Arquitectura General

El sistema está construido con una arquitectura de **3 capas**:

```
┌─────────────────────────────────────────────┐
│          TRAIT (HasShoppingCart)            │
│   Lógica reutilizable del carrito          │
│   - Agregar/Eliminar productos              │
│   - Calcular totales                        │
│   - Gestión de sesión                       │
└─────────────────────────────────────────────┘
                    ↕️
┌─────────────────────────────────────────────┐
│      COMPONENTES LIVEWIRE                   │
│   - ClienteBienvenida (Catálogo)           │
│   - CarritoCompras (Modal)                  │
│   - CarritoBadge (Indicador)                │
└─────────────────────────────────────────────┘
                    ↕️
┌─────────────────────────────────────────────┐
│           VISTAS BLADE                      │
│   - cliente-bienvenida.blade.php           │
│   - carrito-compras.blade.php              │
│   - carrito-badge.blade.php                │
└─────────────────────────────────────────────┘
```

### Ventajas de esta Arquitectura

1. **Separación de responsabilidades**: Cada capa tiene un propósito específico
2. **Reutilización**: El trait puede usarse en múltiples componentes
3. **Escalabilidad**: Fácil agregar nuevos componentes (checkout, wishlist, etc.)
4. **Mantenibilidad**: Código organizado y fácil de entender
5. **Testeable**: Cada capa puede probarse independientemente

---

## Trait HasShoppingCart

**Ubicación**: `app/Traits/HasShoppingCart.php`

Este trait contiene toda la lógica del carrito y puede ser reutilizado por cualquier componente Livewire.

### Estructura de Datos en Sesión

```php
session()->get('cart') = [
    1 => [  // productoId
        'cantidad' => 2,
        'precio' => 15.99
    ],
    5 => [
        'cantidad' => 1,
        'precio' => 12.99
    ]
]
```

### Métodos Principales

#### `getCart(): array`

Obtiene el carrito completo de la sesión.

```php
public function getCart(): array
{
    return session()->get('cart', []);
}
```

**Retorna**: Array con la estructura `[productoId => ['cantidad', 'precio']]`

---

#### `addToCart(int $productoId, int $cantidad = 1): void`

Agrega un producto al carrito o incrementa su cantidad si ya existe.

```php
public function addToCart(int $productoId, int $cantidad = 1): void
{
    $producto = Producto::find($productoId);

    // Validación
    if (!$producto || !$producto->activo || $producto->estado === 'agotado') {
        return;
    }

    $cart = $this->getCart();

    if (isset($cart[$productoId])) {
        // Ya existe: incrementar cantidad
        $cart[$productoId]['cantidad'] += $cantidad;
    } else {
        // Nuevo: agregar
        $cart[$productoId] = [
            'cantidad' => $cantidad,
            'precio' => $producto->precio_descuento ?? $producto->precio,
        ];
    }

    session()->put('cart', $cart);
}
```

**Parámetros**:
- `$productoId`: ID del producto a agregar
- `$cantidad`: Cantidad a agregar (default: 1)

**Validaciones**:
1. Producto existe en BD
2. Producto está activo
3. Producto no está agotado

---

#### `updateCartItem(int $productoId, int $cantidad): void`

Actualiza la cantidad de un producto específico.

```php
public function updateCartItem(int $productoId, int $cantidad): void
{
    if ($cantidad <= 0) {
        $this->removeFromCart($productoId);
        return;
    }

    $cart = $this->getCart();

    if (isset($cart[$productoId])) {
        $cart[$productoId]['cantidad'] = $cantidad;
        session()->put('cart', $cart);
    }
}
```

---

#### `incrementCartItem(int $productoId): void`

Incrementa en 1 la cantidad de un producto.

```php
public function incrementCartItem(int $productoId): void
{
    $cart = $this->getCart();

    if (isset($cart[$productoId])) {
        $cart[$productoId]['cantidad']++;
        session()->put('cart', $cart);
    }
}
```

---

#### `decrementCartItem(int $productoId): void`

Decrementa en 1 la cantidad de un producto. Si llega a 0, lo elimina.

```php
public function decrementCartItem(int $productoId): void
{
    $cart = $this->getCart();

    if (isset($cart[$productoId])) {
        $cart[$productoId]['cantidad']--;

        if ($cart[$productoId]['cantidad'] <= 0) {
            $this->removeFromCart($productoId);
        } else {
            session()->put('cart', $cart);
        }
    }
}
```

---

#### `removeFromCart(int $productoId): void`

Elimina completamente un producto del carrito.

```php
public function removeFromCart(int $productoId): void
{
    $cart = $this->getCart();
    unset($cart[$productoId]);
    session()->put('cart', $cart);
}
```

---

#### `clearCart(): void`

Vacía completamente el carrito.

```php
public function clearCart(): void
{
    session()->forget('cart');
}
```

---

#### `getCartItems(): Collection`

Obtiene todos los items del carrito con información completa del producto.

```php
public function getCartItems(): Collection
{
    $cart = $this->getCart();

    if (empty($cart)) {
        return collect();
    }

    $productoIds = array_keys($cart);
    $productos = Producto::with('categoria')
        ->whereIn('id', $productoIds)
        ->get();

    return $productos->map(function ($producto) use ($cart) {
        $cartItem = $cart[$producto->id];

        return (object) [
            'producto' => $producto,
            'cantidad' => $cartItem['cantidad'],
            'precio' => $cartItem['precio'],
            'subtotal' => $cartItem['cantidad'] * $cartItem['precio'],
        ];
    });
}
```

**Retorna**: Collection de objetos con:
- `producto`: Modelo completo del producto con relación `categoria`
- `cantidad`: Cantidad en el carrito
- `precio`: Precio unitario (puede ser con descuento)
- `subtotal`: cantidad × precio

---

#### `getCartCount(): int`

Obtiene el número total de items en el carrito.

```php
public function getCartCount(): int
{
    $cart = $this->getCart();
    return array_sum(array_column($cart, 'cantidad'));
}
```

**Ejemplo**: Si tienes 2 hamburguesas y 3 pizzas, retorna `5`.

---

#### `getCartTotal(): float`

Calcula el total del carrito.

```php
public function getCartTotal(): float
{
    return $this->getCartItems()->sum('subtotal');
}
```

---

## Componentes Livewire

### 1. ClienteBienvenida

**Ubicación**: `app/Livewire/Cliente/ClienteBienvenida.php`

Este componente maneja el catálogo de productos y la acción de agregar al carrito.

#### Estructura

```php
#[Layout('layouts.cliente')]
class ClienteBienvenida extends Component
{
    use HasShoppingCart;

    public $usuario;
    public $categoriaSeleccionada = '';

    public function mount()
    {
        $this->usuario = Auth::user();
    }
```

#### Método Principal: `agregarAlCarrito($productoId)`

```php
#[On('agregar-al-carrito')]
public function agregarAlCarrito($productoId)
{
    Log::info('Método agregarAlCarrito llamado', ['productoId' => $productoId]);

    $producto = Producto::find($productoId);

    if (!$producto) {
        Log::warning('Producto no encontrado', ['productoId' => $productoId]);
        session()->flash('error', 'Producto no encontrado');
        return;
    }

    if (!$producto->activo || $producto->estado === 'agotado') {
        Log::warning('Producto no disponible', [
            'producto' => $producto->nombre,
            'activo' => $producto->activo,
            'estado' => $producto->estado
        ]);
        session()->flash('error', 'Este producto no está disponible');
        return;
    }

    $this->addToCart($productoId, 1);
    Log::info('Producto agregado al carrito', [
        'producto' => $producto->nombre,
        'carrito' => $this->getCart()
    ]);

    $this->dispatch('carrito-actualizado');
    session()->flash('message', "¡{$producto->nombre} agregado al carrito!");
}
```

**Flujo**:
1. Escucha el evento `'agregar-al-carrito'`
2. Busca y valida el producto
3. Agrega al carrito usando el trait
4. Dispara evento `'carrito-actualizado'`
5. Muestra mensaje de éxito

---

### 2. CarritoCompras

**Ubicación**: `app/Livewire/Cliente/CarritoCompras.php`

Maneja el modal del carrito y todas las operaciones sobre los productos.

#### Estructura

```php
class CarritoCompras extends Component
{
    use HasShoppingCart;

    public $showModal = false;
    public $showConfirmClear = false;

    protected $listeners = ['carrito-actualizado' => '$refresh'];
```

#### Métodos de Control del Modal

```php
public function toggleModal()
{
    $this->showModal = !$this->showModal;
}

#[On('abrir-carrito')]
public function abrirCarrito()
{
    $this->showModal = true;
}
```

#### Operaciones sobre Items

```php
public function increment($productoId)
{
    $this->incrementCartItem($productoId);
    $this->dispatch('carrito-actualizado');
}

public function decrement($productoId)
{
    $this->decrementCartItem($productoId);
    $this->dispatch('carrito-actualizado');
}

public function remove($productoId)
{
    $this->removeFromCart($productoId);
    $this->dispatch('carrito-actualizado');
    session()->flash('message', 'Producto eliminado del carrito');
}
```

#### Sistema de Confirmación para Vaciar

```php
public function confirmClear()
{
    $this->showConfirmClear = true;
}

public function cancelClear()
{
    $this->showConfirmClear = false;
}

public function clear()
{
    $this->clearCart();
    $this->showConfirmClear = false;
    $this->dispatch('carrito-actualizado');
    session()->flash('message', 'Carrito vaciado correctamente');
}
```

#### Computed Properties

```php
public function getItemsProperty()
{
    return $this->getCartItems();
}

public function getCountProperty()
{
    return $this->getCartCount();
}

public function getTotalProperty()
{
    return $this->getCartTotal();
}
```

**Uso en la vista**:
```blade
{{ $items }}  <!-- Collection de objetos -->
{{ $count }}  <!-- int -->
{{ $total }}  <!-- float -->
```

---

### 3. CarritoBadge

**Ubicación**: `app/Livewire/Cliente/CarritoBadge.php`

Muestra el indicador del carrito en el navbar.

```php
class CarritoBadge extends Component
{
    use HasShoppingCart;

    protected $listeners = ['carrito-actualizado' => '$refresh'];

    public function getCountProperty()
{
        return $this->getCartCount();
    }

    public function render()
    {
        return view('livewire.cliente.carrito-badge');
    }
}
```

**Vista** (`carrito-badge.blade.php`):

```blade
<button wire:click="$dispatch('abrir-carrito')"
        class="relative text-white hover:text-orange-200 transition-colors">
    <i class="fas fa-shopping-cart text-xl"></i>

    @if($count > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold
                     rounded-full w-5 h-5 flex items-center justify-center">
            {{ $count }}
        </span>
    @endif
</button>
```

---

## Sistema de Eventos

### Tabla de Eventos

| Evento | Disparado Por | Escuchado Por | Parámetros | Acción |
|--------|---------------|---------------|------------|---------|
| `agregar-al-carrito` | Botón de producto | ClienteBienvenida | `{ productoId: int }` | Agrega producto al carrito |
| `carrito-actualizado` | ClienteBienvenida, CarritoCompras | CarritoCompras, CarritoBadge | Ninguno | Refresca los componentes |
| `abrir-carrito` | CarritoBadge | CarritoCompras | Ninguno | Abre el modal del carrito |

### Cómo Disparar Eventos

#### Desde Blade (con Alpine.js)

```blade
<button wire:click="$dispatch('nombre-evento', { param: 'valor' })">
    Click
</button>
```

#### Desde PHP (Componente Livewire)

```php
$this->dispatch('nombre-evento', ['param' => 'valor']);
```

### Cómo Escuchar Eventos

#### Opción 1: Con Atributo `#[On]`

```php
#[On('nombre-evento')]
public function metodo($param)
{
    // Tu lógica aquí
}
```

#### Opción 2: Con `$listeners`

```php
protected $listeners = [
    'nombre-evento' => 'metodo',
    'otro-evento' => '$refresh'  // Refresca el componente
];
```

---

## Vistas Blade

### 1. Modal del Carrito (`carrito-compras.blade.php`)

#### Estructura Principal

```blade
<div>
    <!-- Modal Principal del Carrito -->
    @if($showModal)
        <!-- Backdrop -->
        <div wire:click="toggleModal"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
        </div>

        <!-- Modal Centrado -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl
                        max-h-[90vh] overflow-hidden flex flex-col animate-scale-in">

                <!-- Header -->
                <!-- Items del Carrito -->
                <!-- Footer con Total y Botones -->
            </div>
        </div>
    @endif

    <!-- Modal de Confirmación -->
    @if($showConfirmClear)
        <!-- ... -->
    @endif
</div>
```

#### Items del Carrito

```blade
<div class="flex-1 overflow-y-auto p-6 space-y-4">
    @forelse($items as $item)
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200"
             wire:key="cart-item-{{ $item->producto->id }}">

            <!-- Imagen del producto -->
            <div class="w-20 h-20 rounded-lg overflow-hidden">
                <img src="{{ $item->producto->image_url }}"
                     alt="{{ $item->producto->nombre }}">
            </div>

            <!-- Información del producto -->
            <div>
                <h3>{{ $item->producto->nombre }}</h3>
                <p>${{ number_format($item->precio, 2) }}</p>

                <!-- Controles de cantidad -->
                <div class="flex items-center gap-2">
                    <button wire:click="decrement({{ $item->producto->id }})">
                        <i class="fas fa-minus"></i>
                    </button>

                    <span>{{ $item->cantidad }}</span>

                    <button wire:click="increment({{ $item->producto->id }})">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button wire:click="remove({{ $item->producto->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <!-- Subtotal -->
                <p>Subtotal: ${{ number_format($item->subtotal, 2) }}</p>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p>Tu carrito está vacío</p>
        </div>
    @endforelse
</div>
```

**Importante**:
- `wire:key`: Identificador único para cada item (mejora rendimiento de Livewire)
- `@forelse`: Como `@foreach` pero con manejo de colección vacía

#### Modal de Confirmación

```blade
@if($showConfirmClear)
    <!-- Backdrop más oscuro -->
    <div wire:click="cancelClear"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] animate-fade-in">
    </div>

    <!-- Modal Centrado -->
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-scale-in">
            <div class="p-6">
                <!-- Icono de Advertencia -->
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>

                <!-- Título y Mensaje -->
                <h3>¿Vaciar carrito?</h3>
                <p>Esta acción eliminará todos los productos de tu carrito.</p>

                <!-- Información adicional -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p>Se eliminarán {{ $count }} productos por ${{ number_format($total, 2) }}</p>
                </div>

                <!-- Botones -->
                <div class="flex gap-3">
                    <button wire:click="cancelClear">Cancelar</button>
                    <button wire:click="clear">Sí, Vaciar</button>
                </div>
            </div>
        </div>
    </div>
@endif
```

---

### 2. Animaciones CSS (`resources/css/app.css`)

```css
/* Animaciones para el carrito */
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.animate-scale-in {
    animation: scale-in 0.3s ease-out;
}
```

**Uso**:
- `animate-fade-in`: Para el backdrop (fondo con blur)
- `animate-scale-in`: Para los modales (efecto zoom desde el centro)

---

## Flujo Completo

### Agregar un Producto al Carrito

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Usuario hace click en "Agregar al Carrito"              │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Botón dispara evento:                                    │
│    $dispatch('agregar-al-carrito', { productoId: 2 })      │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. ClienteBienvenida escucha el evento                      │
│    #[On('agregar-al-carrito')]                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Ejecuta agregarAlCarrito($productoId)                    │
│    - Busca producto en BD                                   │
│    - Valida disponibilidad                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. Llama a addToCart($productoId, 1) del trait             │
│    - Verifica si existe en carrito                          │
│    - Suma cantidad o agrega nuevo                           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. Actualiza la sesión PHP                                  │
│    session()->put('cart', $cart)                           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. Dispara evento 'carrito-actualizado'                    │
│    $this->dispatch('carrito-actualizado')                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 8. CarritoCompras y CarritoBadge escuchan y refrescan      │
│    - Badge actualiza contador                               │
│    - Modal (si está abierto) muestra nuevo producto        │
└─────────────────────────────────────────────────────────────┘
```

### Vaciar el Carrito con Confirmación

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Usuario hace click en "Vaciar"                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Ejecuta wire:click="confirmClear"                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. CarritoCompras::confirmClear()                           │
│    $this->showConfirmClear = true                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Aparece modal de confirmación                            │
│    - Muestra advertencia                                    │
│    - Muestra cantidad y total                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
        ┌─────────────────┴──────────────────┐
        │                                     │
        ↓                                     ↓
┌──────────────────┐              ┌──────────────────┐
│ Click "Cancelar" │              │ Click "Sí,Vaciar"│
└──────────────────┘              └──────────────────┘
        │                                     │
        ↓                                     ↓
┌──────────────────┐              ┌──────────────────┐
│ cancelClear()    │              │ clear()          │
│ Cierra modal     │              │ Vacía carrito    │
└──────────────────┘              │ Cierra modal     │
                                  │ Dispara evento   │
                                  └──────────────────┘
                                            ↓
                            ┌───────────────────────────┐
                            │ Badge y Modal se refrescan│
                            └───────────────────────────┘
```

---

## Problemas Resueltos

### 1. Error: `$parent` no definido

**Error Completo**:
```
Alpine Expression Error: undefined
Expression: "$wire.$parent.agregarAlCarrito(2)"
```

**Causa**:
- El componente `product-card` es un componente Blade anónimo, NO un componente Livewire
- No tiene acceso directo a métodos del componente padre
- `$parent` solo funciona entre componentes Livewire anidados

**Solución**:
Usar sistema de eventos de Livewire:

```blade
<!-- ❌ Antes (no funciona) -->
<button wire:click="$parent.agregarAlCarrito({{ $producto->id }})">

<!-- ✅ Después (funciona) -->
<button wire:click="$dispatch('agregar-al-carrito', { productoId: {{ $producto->id }} })">
```

Y en el componente padre:

```php
#[On('agregar-al-carrito')]
public function agregarAlCarrito($productoId)
{
    // Tu lógica aquí
}
```

---

### 2. Error: `$wire` no definido en Alpine

**Error Completo**:
```
Alpine Expression Error: $wire is not defined
Expression: "$wire.showModal"
```

**Causa**:
- Alpine.js se inicializa antes que Livewire inyecte el magic helper `$wire`
- Durante el morphing del DOM, Alpine intenta evaluar expresiones pero `$wire` no existe aún
- Problema de sincronización entre Alpine y Livewire

**Solución**:
Eliminar Alpine.js del modal y usar solo directivas de Livewire:

```blade
<!-- ❌ Antes (con Alpine, no funciona) -->
<div x-data="{ show: $wire.entangle('showModal') }"
     x-show="show">
    <!-- contenido -->
</div>

<!-- ✅ Después (solo Livewire, funciona) -->
@if($showModal)
    <div>
        <!-- contenido -->
    </div>
@endif
```

**Ventajas de esta solución**:
- Sin errores de sincronización
- Código más simple
- Mejor rendimiento (menos JavaScript)
- 100% compatible con Livewire morphing

---

### 3. Modal Lateral vs Modal Centrado

**Problema Inicial**:
El modal aparecía como slide-over en el lateral derecho.

**Solución**:
Cambiar el posicionamiento CSS:

```blade
<!-- ❌ Antes: Slide-over lateral -->
<div class="fixed right-0 top-0 h-full w-full sm:w-96
            animate-slide-in-right">
    <!-- contenido -->
</div>

<!-- ✅ Después: Modal centrado -->
<div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="w-full max-w-2xl max-h-[90vh] animate-scale-in">
        <!-- contenido -->
    </div>
</div>
```

**Cambios en animación**:

```css
/* ❌ Antes */
@keyframes slide-in-right {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

/* ✅ Después */
@keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
```

---

### 4. Confirmación Básica vs Modal Personalizado

**Antes**: Usaba `wire:confirm` (confirmación nativa del navegador)

```blade
<button wire:click="clear"
        wire:confirm="¿Estás seguro de vaciar el carrito?">
    Vaciar
</button>
```

**Problemas**:
- Diseño poco atractivo (alert nativo del navegador)
- Sin información contextual (cantidad, total)
- No personalizable

**Después**: Modal de confirmación personalizado

```blade
<button wire:click="confirmClear">Vaciar</button>

<!-- Modal de confirmación con diseño personalizado -->
@if($showConfirmClear)
    <!-- Icono, mensaje, información, botones estilizados -->
@endif
```

**Ventajas**:
- Diseño consistente con la aplicación
- Muestra información relevante (cantidad, total)
- Mejor UX con colores y iconos
- Totalmente personalizable

---

## Mejoras Futuras

### 1. Persistencia en Base de Datos

**Problema Actual**: El carrito solo existe en la sesión PHP

**Mejora**:
```php
// Migración
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('producto_id')->constrained();
    $table->integer('cantidad');
    $table->decimal('precio', 10, 2);
    $table->timestamps();
});
```

**Beneficios**:
- Carrito persistente entre sesiones
- Recuperación de carrito abandonado
- Sincronización multi-dispositivo

---

### 2. Validación de Stock

**Problema Actual**: No valida stock disponible vs cantidad en carrito

**Mejora**:
```php
public function addToCart(int $productoId, int $cantidad = 1): void
{
    $producto = Producto::find($productoId);

    // Obtener cantidad actual en carrito
    $cart = $this->getCart();
    $cantidadEnCarrito = $cart[$productoId]['cantidad'] ?? 0;

    // Validar stock
    if (($cantidadEnCarrito + $cantidad) > $producto->stock) {
        throw new \Exception('Stock insuficiente');
    }

    // ... resto del código
}
```

---

### 3. Límites de Cantidad por Producto

**Mejora**:
```php
public function incrementCartItem(int $productoId): void
{
    $producto = Producto::find($productoId);
    $cart = $this->getCart();

    // Límite máximo: 10 unidades por producto
    if ($cart[$productoId]['cantidad'] >= 10) {
        session()->flash('error', 'Máximo 10 unidades por producto');
        return;
    }

    $cart[$productoId]['cantidad']++;
    session()->put('cart', $cart);
}
```

---

### 4. Wishlist (Lista de Deseos)

**Implementación**:
Reutilizar el mismo trait con una clave diferente:

```php
// En HasShoppingCart trait
public function addToWishlist(int $productoId): void
{
    $wishlist = session()->get('wishlist', []);

    if (!isset($wishlist[$productoId])) {
        $wishlist[$productoId] = [
            'added_at' => now()
        ];
    }

    session()->put('wishlist', $wishlist);
}
```

---

### 5. Sistema de Cupones

**Mejora**:
```php
// app/Models/Cupon.php
class Cupon extends Model
{
    public function aplicarDescuento(float $total): float
    {
        if ($this->tipo === 'porcentaje') {
            return $total * (1 - $this->valor / 100);
        }

        return max(0, $total - $this->valor);
    }
}

// En CarritoCompras.php
public $cuponAplicado = null;

public function aplicarCupon(string $codigo)
{
    $cupon = Cupon::where('codigo', $codigo)
        ->where('activo', true)
        ->first();

    if ($cupon) {
        $this->cuponAplicado = $cupon;
        session()->flash('message', 'Cupón aplicado correctamente');
    }
}

public function getTotalProperty()
{
    $total = $this->getCartTotal();

    if ($this->cuponAplicado) {
        return $this->cuponAplicado->aplicarDescuento($total);
    }

    return $total;
}
```

---

### 6. Carrito Recuperable (Email)

**Mejora**:
```php
// Job para enviar email de recuperación
class EnviarCarritoAbandonado implements ShouldQueue
{
    public function handle()
    {
        $carritos = Cart::where('updated_at', '<', now()->subHours(24))
            ->whereHas('user')
            ->get();

        foreach ($carritos as $carrito) {
            Mail::to($carrito->user)->send(
                new CarritoAbandonadoMail($carrito)
            );
        }
    }
}
```

---

### 7. Carrito Multi-Tienda

Para sistemas con múltiples vendedores:

```php
public function addToCart(int $productoId, int $tiendaId, int $cantidad = 1): void
{
    $cart = $this->getCart();

    // Agrupar por tienda
    if (!isset($cart[$tiendaId])) {
        $cart[$tiendaId] = [];
    }

    if (isset($cart[$tiendaId][$productoId])) {
        $cart[$tiendaId][$productoId]['cantidad'] += $cantidad;
    } else {
        $cart[$tiendaId][$productoId] = [
            'cantidad' => $cantidad,
            'precio' => $producto->precio,
        ];
    }

    session()->put('cart', $cart);
}
```

---

## Conclusión

El sistema de carrito de compras implementado es:

✅ **Modular**: Trait reutilizable + componentes independientes
✅ **Escalable**: Fácil agregar nuevas funcionalidades
✅ **Mantenible**: Código organizado y bien documentado
✅ **Performante**: Sin dependencias pesadas, solo Livewire + CSS
✅ **UX optimizada**: Modal centrado, confirmaciones claras, animaciones suaves

### Archivos Clave

```
app/
├── Traits/
│   └── HasShoppingCart.php          # Lógica del carrito
├── Livewire/
│   └── Cliente/
│       ├── ClienteBienvenida.php    # Catálogo
│       ├── CarritoCompras.php       # Modal del carrito
│       └── CarritoBadge.php         # Indicador del navbar

resources/
├── views/
│   ├── livewire/
│   │   └── cliente/
│   │       ├── cliente-bienvenida.blade.php
│   │       ├── carrito-compras.blade.php
│   │       └── carrito-badge.blade.php
│   └── components/
│       └── product-card.blade.php    # Card de producto
└── css/
    └── app.css                       # Animaciones CSS
```

---

**Autor**: Sistema de Carrito de Compras
**Fecha**: 2025-09-29
**Versión**: 1.0.0
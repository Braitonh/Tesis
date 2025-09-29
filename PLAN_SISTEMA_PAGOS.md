# Plan de Integración: Sistema de Pagos Simulado

## 📋 Descripción General

Implementación de un sistema de pagos simulado para el módulo de checkout del sistema de pedidos de comida rápida. Esta solución es ideal para proyectos educativos/tesis ya que demuestra conocimientos técnicos completos sin requerir integraciones reales con pasarelas de pago.

## 🎯 Objetivos

- Demostrar comprensión del flujo completo de procesamiento de pagos
- Implementar múltiples métodos de pago (Efectivo, Tarjeta, Billetera Digital)
- Manejar estados de transacciones y pedidos
- Validaciones de seguridad básicas para datos de tarjetas
- Arquitectura escalable para futura integración real

## 🏗️ Arquitectura del Sistema

### Componentes Principales

```
Cliente → Checkout → Selección de Método de Pago
                            ↓
                    [Efectivo] → Crear Pedido Directamente
                            ↓
                    [Tarjeta/Billetera] → Formulario de Pago
                            ↓
                    Procesamiento Simulado (2-3 seg)
                            ↓
                    [Éxito 90%] → Crear Pedido + Transacción
                    [Fallo 10%] → Mostrar Error + Reintentar
```

## 📊 Cambios en Base de Datos

### 1. Nueva Tabla: `transacciones`

```sql
CREATE TABLE transacciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NULL,
    metodo_pago ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital'),
    estado ENUM('pendiente', 'procesando', 'aprobado', 'rechazado', 'cancelado'),
    monto DECIMAL(10, 2),
    numero_transaccion VARCHAR(50) UNIQUE,
    detalles_tarjeta JSON NULL, -- últimos 4 dígitos, tipo de tarjeta
    mensaje_respuesta TEXT NULL,
    fecha_procesamiento TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE SET NULL
);
```

### 2. Modificar Tabla: `pedidos`

```sql
ALTER TABLE pedidos
ADD COLUMN estado_pago ENUM('pendiente', 'pagado', 'fallido') DEFAULT 'pendiente',
ADD COLUMN metodo_pago_preferido ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital') DEFAULT 'efectivo';
```

## 🔧 Implementación Backend

### Archivos a Crear/Modificar

#### 1. **Migration: `create_transacciones_table.php`**
- Crear tabla de transacciones
- Definir relaciones con pedidos

#### 2. **Migration: `add_payment_fields_to_pedidos_table.php`**
- Agregar campos de pago a pedidos existentes

#### 3. **Model: `Transaccion.php`**
```php
- Relación con Pedido
- Accessors para datos sensibles
- Scopes para filtrar por estado
- Generador de número de transacción único
```

#### 4. **Actualizar Model: `Pedido.php`**
```php
- Relación hasOne con Transaccion
- Método para verificar si está pagado
- Scope para pedidos pagados/pendientes
```

#### 5. **Service: `PagoSimuladoService.php`**
```php
class PagoSimuladoService
{
    // Procesar pago simulado
    public function procesarPago($datos)

    // Validar número de tarjeta (algoritmo Luhn)
    private function validarNumeroTarjeta($numero)

    // Simular respuesta de pasarela (90% éxito)
    private function simularRespuestaPasarela()

    // Generar número de transacción único
    private function generarNumeroTransaccion()

    // Cifrar últimos 4 dígitos para guardar
    private function cifrarDatosTarjeta($datos)
}
```

#### 6. **Actualizar Livewire: `CarritoCheckout.php`**
```php
class CarritoCheckout extends Component
{
    // Propiedades existentes
    public $direccion_entrega;
    public $telefono_contacto;
    public $notas;

    // Nuevas propiedades para pagos
    public $metodo_pago = 'efectivo';
    public $numero_tarjeta;
    public $nombre_tarjeta;
    public $fecha_vencimiento;
    public $cvv;
    public $mostrarFormularioPago = false;
    public $procesando = false;

    // Reglas de validación actualizadas
    protected function rules()

    // Método actualizado
    public function confirmarPedido()

    // Nuevo método
    public function updatedMetodoPago($value)

    // Procesar pago con tarjeta
    private function procesarPagoTarjeta()

    // Crear pedido con efectivo
    private function crearPedidoEfectivo()
}
```

#### 7. **Nuevo Livewire: `ProcesoPago.php`**
```php
// Componente para página de "Procesando Pago"
class ProcesoPago extends Component
{
    public $transaccion_id;
    public $estado = 'procesando';

    public function mount($transaccionId)
    public function verificarEstado()
    public function render()
}
```

## 🎨 Implementación Frontend

### Archivos a Crear/Modificar

#### 1. **Actualizar Vista: `carrito-checkout.blade.php`**

**Sección de Método de Pago (después de Teléfono):**
```html
<!-- Método de Pago -->
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-3">
        Método de Pago
        <span class="text-red-500">*</span>
    </label>

    <div class="space-y-3">
        <!-- Efectivo -->
        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
            <input type="radio" wire:model.live="metodo_pago" value="efectivo" class="...">
            <div class="ml-3">
                <i class="fas fa-money-bill-wave text-green-600"></i>
                <span class="font-semibold">Efectivo</span>
                <p class="text-xs text-gray-500">Paga al recibir tu pedido</p>
            </div>
        </label>

        <!-- Tarjeta de Crédito -->
        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
            <input type="radio" wire:model.live="metodo_pago" value="tarjeta_credito" class="...">
            <div class="ml-3">
                <i class="fas fa-credit-card text-blue-600"></i>
                <span class="font-semibold">Tarjeta de Crédito</span>
                <p class="text-xs text-gray-500">Visa, Mastercard, American Express</p>
            </div>
        </label>

        <!-- Tarjeta de Débito -->
        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
            <input type="radio" wire:model.live="metodo_pago" value="tarjeta_debito" class="...">
            <div class="ml-3">
                <i class="fas fa-credit-card text-purple-600"></i>
                <span class="font-semibold">Tarjeta de Débito</span>
            </div>
        </label>

        <!-- Billetera Digital -->
        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
            <input type="radio" wire:model.live="metodo_pago" value="billetera_digital" class="...">
            <div class="ml-3">
                <i class="fas fa-wallet text-orange-600"></i>
                <span class="font-semibold">Billetera Digital</span>
                <p class="text-xs text-gray-500">Zimple, Tigo Money, Personal Pay</p>
            </div>
        </label>
    </div>
</div>

<!-- Formulario de Tarjeta (Condicional) -->
@if($metodo_pago !== 'efectivo')
<div class="bg-blue-50 border border-blue-200 rounded-xl p-6 space-y-4"
     x-data x-show="true" x-transition>

    <!-- Número de Tarjeta -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Número de Tarjeta
            <span class="text-red-500">*</span>
        </label>
        <input type="text"
               wire:model="numero_tarjeta"
               placeholder="1234 5678 9012 3456"
               maxlength="19"
               class="block w-full px-4 py-3 border rounded-xl"
               x-mask="9999 9999 9999 9999">
        @error('numero_tarjeta')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nombre en Tarjeta -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Nombre en la Tarjeta
            <span class="text-red-500">*</span>
        </label>
        <input type="text"
               wire:model="nombre_tarjeta"
               placeholder="JUAN PÉREZ"
               class="block w-full px-4 py-3 border rounded-xl uppercase">
        @error('nombre_tarjeta')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Fecha y CVV -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Fecha de Vencimiento
                <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   wire:model="fecha_vencimiento"
                   placeholder="MM/AA"
                   maxlength="5"
                   class="block w-full px-4 py-3 border rounded-xl"
                   x-mask="99/99">
            @error('fecha_vencimiento')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                CVV
                <span class="text-red-500">*</span>
            </label>
            <input type="password"
                   wire:model="cvv"
                   placeholder="123"
                   maxlength="4"
                   class="block w-full px-4 py-3 border rounded-xl">
            @error('cvv')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Iconos de Tarjetas -->
    <div class="flex items-center gap-2 pt-2">
        <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
        <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
        <i class="fab fa-cc-amex text-2xl text-blue-500"></i>
        <span class="ml-2 text-xs text-gray-600">
            <i class="fas fa-lock text-green-600"></i> Pago seguro
        </span>
    </div>
</div>
@endif
```

**Actualizar botón de confirmar:**
```html
<button type="submit"
        wire:click="confirmarPedido"
        wire:loading.attr="disabled"
        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold ...">
    <span wire:loading.remove wire:target="confirmarPedido">
        <i class="fas fa-check-circle mr-2"></i>
        @if($metodo_pago === 'efectivo')
            Confirmar Pedido
        @else
            Proceder al Pago
        @endif
    </span>
    <span wire:loading wire:target="confirmarPedido">
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Procesando...
    </span>
</button>
```

#### 2. **Nueva Vista: `proceso-pago.blade.php`**
```html
<!-- Página de "Procesando Pago" con animación -->
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 to-orange-100">
    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center max-w-md" wire:poll.1s="verificarEstado">

        @if($estado === 'procesando')
            <!-- Spinner animado -->
            <div class="mb-6">
                <i class="fas fa-spinner fa-spin text-6xl text-orange-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Procesando Pago</h2>
            <p class="text-gray-600">Por favor espera mientras verificamos tu pago...</p>
        @elseif($estado === 'aprobado')
            <!-- Checkmark animado -->
            <div class="mb-6">
                <i class="fas fa-check-circle text-6xl text-green-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">¡Pago Aprobado!</h2>
            <p class="text-gray-600 mb-6">Tu pedido ha sido confirmado</p>
            <a href="{{ route('cliente.pedido.confirmacion', $transaccion_id) }}"
               class="btn btn-primary">
                Ver mi Pedido
            </a>
        @else
            <!-- Error -->
            <div class="mb-6">
                <i class="fas fa-times-circle text-6xl text-red-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Pago Rechazado</h2>
            <p class="text-gray-600 mb-6">{{ $mensaje_error }}</p>
            <a href="{{ route('cliente.carrito.checkout') }}"
               class="btn btn-secondary">
                Reintentar
            </a>
        @endif
    </div>
</div>
```

#### 3. **Nueva Vista: `pedido-confirmacion.blade.php`**
```html
<!-- Página de confirmación de pedido exitoso -->
<!-- Mostrar detalles del pedido, número de transacción, tiempo estimado -->
```

## 🧪 Validaciones y Seguridad

### Validaciones del Cliente (Frontend)
- Máscara para número de tarjeta (espacios cada 4 dígitos)
- Máscara para fecha de vencimiento (MM/AA)
- Máximo de caracteres en CVV (3-4 dígitos)
- Capitalización automática para nombre en tarjeta

### Validaciones del Servidor (Backend)
```php
// En CarritoCheckout.php
protected function validarDatosTarjeta()
{
    return [
        'numero_tarjeta' => 'required|digits_between:13,19|luhn',
        'nombre_tarjeta' => 'required|string|min:3|max:100|regex:/^[a-zA-Z\s]+$/',
        'fecha_vencimiento' => [
            'required',
            'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
            function ($attribute, $value, $fail) {
                // Validar que no esté vencida
                [$mes, $año] = explode('/', $value);
                $fecha = \Carbon\Carbon::createFromDate(2000 + $año, $mes, 1)->endOfMonth();
                if ($fecha->isPast()) {
                    $fail('La tarjeta está vencida.');
                }
            },
        ],
        'cvv' => 'required|digits_between:3,4',
    ];
}
```

### Algoritmo de Luhn (Validación de Tarjeta)
```php
// Implementar como Custom Validation Rule
class LuhnRule implements Rule
{
    public function passes($attribute, $value)
    {
        $number = preg_replace('/\D/', '', $value);
        $sum = 0;
        $length = strlen($number);

        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $number[$length - $i - 1];
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return ($sum % 10 === 0);
    }
}
```

## 📝 Estados y Flujos

### Estados de Transacción
1. **pendiente**: Transacción creada, no procesada
2. **procesando**: En proceso de validación (2-3 seg simulados)
3. **aprobado**: Pago exitoso (90% de casos)
4. **rechazado**: Pago fallido (10% de casos - simulado)
5. **cancelado**: Usuario canceló el proceso

### Estados de Pago del Pedido
1. **pendiente**: Pedido creado, pago no completado
2. **pagado**: Pago confirmado
3. **fallido**: Pago rechazado

### Flujo de Estados
```
Usuario hace checkout
    ↓
Transacción: pendiente
    ↓
Usuario confirma pago
    ↓
Transacción: procesando (2-3 seg)
    ↓
[90% éxito] → Transacción: aprobado → Pedido: pagado
[10% fallo]  → Transacción: rechazado → Pedido: fallido
```

## 🧪 Casos de Prueba

### Tarjetas de Prueba

```php
// En PagoSimuladoService.php
private $tarjetasPrueba = [
    // Siempre aprobadas
    '4111111111111111' => 'aprobado',
    '5555555555554444' => 'aprobado',

    // Siempre rechazadas
    '4000000000000002' => 'rechazado',

    // Fondos insuficientes
    '4000000000009995' => 'fondos_insuficientes',

    // Tarjeta vencida
    '4000000000000069' => 'tarjeta_vencida',
];
```

### Escenarios de Testing
1. ✅ Pago con efectivo - flujo normal
2. ✅ Pago con tarjeta aprobada
3. ❌ Pago con tarjeta rechazada
4. ❌ Número de tarjeta inválido (Luhn)
5. ❌ Tarjeta vencida
6. ❌ CVV incorrecto (formato)
7. ✅ Reintentar después de fallo
8. ✅ Cancelar proceso de pago

## 📱 UX/UI Consideraciones

### Feedback Visual
- Loading states en todos los botones
- Animaciones suaves en transiciones
- Iconos contextuales (tarjetas, métodos de pago)
- Colores semánticos (verde=éxito, rojo=error, azul=info)

### Mensajes de Error Amigables
```php
private $mensajesError = [
    'rechazado' => 'El pago fue rechazado. Por favor verifica tus datos o intenta con otra tarjeta.',
    'fondos_insuficientes' => 'Fondos insuficientes. Por favor intenta con otra tarjeta.',
    'tarjeta_vencida' => 'Tu tarjeta está vencida. Por favor usa otra tarjeta.',
    'error_general' => 'Hubo un problema procesando tu pago. Por favor intenta nuevamente.',
];
```

### Responsive Design
- Formulario adaptable a móvil/tablet/desktop
- Inputs grandes para fácil uso en móvil
- Scroll suave en listado de productos

## 🚀 Rutas Adicionales

```php
// En routes/web.php - Grupo Cliente
Route::get('/carrito/checkout', CarritoCheckout::class)
    ->name('cliente.carrito.checkout');

Route::get('/pago/procesando/{transaccionId}', ProcesoPago::class)
    ->name('cliente.pago.procesando');

Route::get('/pedido/confirmacion/{transaccionId}', PedidoConfirmacion::class)
    ->name('cliente.pedido.confirmacion');
```

## 📦 Dependencias Adicionales

```bash
# Si se necesita instalar Alpine.js mask plugin
npm install @alpinejs/mask

# Para validación de tarjetas en frontend (opcional)
npm install card-validator
```

## 🎓 Valor Académico para la Tesis

### Conceptos Demostrados
1. **Arquitectura de Sistemas de Pago**: Comprensión del flujo completo
2. **Manejo de Estados**: Transiciones y persistencia
3. **Validaciones Avanzadas**: Algoritmo de Luhn, validaciones custom
4. **UX de Pagos**: Feedback, loading states, manejo de errores
5. **Seguridad Básica**: No guardar datos sensibles, cifrado
6. **Testing**: Casos de prueba, tarjetas simuladas
7. **Escalabilidad**: Estructura preparada para integración real futura

### Puntos de Defensa
- Sistema modular y fácil de migrar a pasarela real
- Considera casos edge (tarjeta vencida, fondos insuficientes)
- Implementa estándares de seguridad PCI básicos
- UX optimizada para conversión de pagos
- Estados bien definidos y trazabilidad completa

## 📅 Cronograma Estimado

1. **Día 1-2**: Migraciones + Modelos + Seeders
2. **Día 3-4**: Service de PagoSimulado + Validaciones
3. **Día 5-6**: Actualizar Livewire CarritoCheckout
4. **Día 7-8**: Frontend - Formularios de pago
5. **Día 9**: Componente ProcesoPago + Vista Confirmación
6. **Día 10**: Testing + Ajustes finales

**Total estimado**: 10 días de desarrollo

## 🔄 Migración Futura a Pasarela Real

### Pasos para Integrar Bancard/Pagopar/Mercado Pago:

1. Instalar SDK de la pasarela elegida
2. Crear nuevo Service `PagoRealService` que implemente misma interface
3. Cambiar en config: `'payment_driver' => 'simulado'` → `'payment_driver' => 'bancard'`
4. Mantener misma estructura de transacciones y estados
5. Agregar webhooks para confirmaciones asíncronas

**No se requieren cambios en:**
- Base de datos (ya preparada)
- Modelos (agnósticos al driver)
- Frontend (solo ajustar formulario si necesario)
- Flujo de usuario (mantiene misma UX)

---

## 📞 Notas Finales

Este plan cubre un sistema completo de pagos simulado que:
- ✅ Demuestra conocimientos técnicos avanzados
- ✅ Es 100% funcional para demostración
- ✅ No requiere cuentas ni costos de pasarelas
- ✅ Preparado para migración futura a sistema real
- ✅ Cumple objetivos académicos de una tesis

**Fecha de creación**: 2025-09-29
**Versión**: 1.0
**Autor**: Sistema de Tesis - Combate Mborore
# Documentación Técnica: Sistema de Pasarela de Pagos Simulada

## Tabla de Contenidos
1. [Descripción General](#descripción-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Componentes Principales](#componentes-principales)
4. [Flujo de Proceso de Pago](#flujo-de-proceso-de-pago)
5. [Tarjetas de Prueba](#tarjetas-de-prueba)
6. [Algoritmo de Validación Luhn](#algoritmo-de-validación-luhn)
7. [Estados de Transacción](#estados-de-transacción)
8. [Modelo de Datos](#modelo-de-datos)
9. [Seguridad](#seguridad)
10. [Integración con Pasarela Real](#integración-con-pasarela-real)
11. [Ejemplos de Uso](#ejemplos-de-uso)

---

## Descripción General

El sistema implementa una **pasarela de pago simulada** para el procesamiento de transacciones en el sistema de pedidos de comida rápida. Esta implementación permite realizar pruebas completas del flujo de pago sin necesidad de conectarse a una pasarela real durante el desarrollo.

### Características Principales
- ✅ Procesamiento simulado de pagos con tarjeta
- ✅ Validación de números de tarjeta usando algoritmo de Luhn
- ✅ Soporte para múltiples métodos de pago
- ✅ Tarjetas de prueba predefinidas con comportamientos específicos
- ✅ Simulación de delay de procesamiento (2 segundos)
- ✅ Gestión completa de estados de transacción
- ✅ Detección automática de tipo de tarjeta

---

## Arquitectura del Sistema

### Stack Tecnológico
- **Backend**: Laravel 12 con PHP 8.2
- **Frontend**: Livewire 3.6 + AlpineJS
- **Base de Datos**: MySQL 8.0
- **Patrón de Diseño**: Service Layer Pattern

### Estructura de Archivos
```
app/
├── Services/
│   └── PagoSimuladoService.php      # Servicio principal de procesamiento
├── Rules/
│   └── Luhn.php                      # Regla de validación de tarjetas
├── Models/
│   └── Transaccion.php               # Modelo de transacciones
└── Livewire/Cliente/
    ├── CarritoCheckout.php           # Componente de checkout
    └── ProcesoPago.php                # Componente de proceso de pago

resources/views/livewire/cliente/
├── carrito-checkout.blade.php        # Vista de checkout
└── proceso-pago.blade.php            # Vista de procesamiento
```

---

## Componentes Principales

### 1. PagoSimuladoService

**Ubicación**: `app/Services/PagoSimuladoService.php`

**Responsabilidades**:
- Procesar pagos simulados
- Validar números de tarjeta
- Gestionar transacciones
- Simular respuestas de pasarela
- Detectar tipos de tarjeta

**Métodos Públicos**:

#### `procesarPago(array $datos): array`
Procesa un pago simulado y retorna el resultado.

**Parámetros**:
```php
[
    'pedido_id' => int,              // ID del pedido (opcional)
    'metodo_pago' => string,         // 'efectivo', 'tarjeta_credito', 'tarjeta_debito', 'billetera_digital'
    'monto' => float,                // Monto de la transacción
    'numero_tarjeta' => string,      // Número de tarjeta (requerido si no es efectivo)
    'nombre_tarjeta' => string,      // Nombre en la tarjeta
    'fecha_vencimiento' => string,   // MM/AA
    'cvv' => string,                 // Código de seguridad
]
```

**Retorno**:
```php
[
    'success' => bool,                // true si el pago fue aprobado
    'transaccion' => Transaccion|null, // Objeto de la transacción
    'mensaje' => string,              // Mensaje de resultado
]
```

**Flujo Interno**:
```
1. Iniciar transacción DB
2. Crear registro de transacción en estado "pendiente"
3. Si es efectivo:
   - Aprobar directamente
   - Retornar éxito
4. Si es tarjeta:
   - Cambiar estado a "procesando"
   - Simular delay de 2 segundos (sleep)
   - Validar número de tarjeta con algoritmo Luhn
   - Si no es válida:
     * Marcar como "rechazado"
     * Retornar error
   - Simular respuesta de pasarela
   - Guardar detalles de tarjeta (últimos 4 dígitos, tipo)
   - Actualizar estado según resultado
5. Commit de transacción DB
6. Retornar resultado
```

#### `getTarjetasPrueba(): array`
Retorna el array de tarjetas de prueba con sus comportamientos.

#### `verificarEstadoTransaccion(int $transaccionId): ?Transaccion`
Consulta el estado actual de una transacción por su ID.

**Métodos Privados**:

#### `validarNumeroTarjeta(string $numero): bool`
Valida el número de tarjeta usando el algoritmo de Luhn.

#### `simularRespuestaPasarela(string $numeroTarjeta): array`
Simula la respuesta de una pasarela de pago real.

**Lógica**:
- Si el número está en tarjetas predefinidas: retorna comportamiento específico
- Si no: 90% de probabilidad de éxito, 10% de rechazo aleatorio

#### `detectarTipoTarjeta(string $numero): string`
Detecta el tipo de tarjeta según los primeros dígitos.

**Patrones de Detección**:
- `^4` → Visa
- `^5[1-5]` → Mastercard
- `^3[47]` → American Express
- `^6(?:011|5)` → Discover
- `^35` → JCB
- Otros → Desconocida

---

### 2. Modelo Transaccion

**Ubicación**: `app/Models/Transaccion.php`

**Campos de la Tabla**:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID autoincremental |
| `pedido_id` | bigint | FK al pedido |
| `metodo_pago` | enum | Método de pago usado |
| `estado` | enum | Estado actual de la transacción |
| `monto` | decimal(10,2) | Monto de la transacción |
| `numero_transaccion` | string | Identificador único |
| `detalles_tarjeta` | json | Información de la tarjeta (encriptada) |
| `mensaje_respuesta` | text | Mensaje de la pasarela |
| `fecha_procesamiento` | timestamp | Cuándo se procesó |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Última actualización |

**Casts**:
```php
'detalles_tarjeta' => 'array',
'fecha_procesamiento' => 'datetime',
'monto' => 'decimal:2',
```

**Relaciones**:
```php
public function pedido(): BelongsTo
```

**Métodos Estáticos**:

#### `generarNumeroTransaccion(): string`
Genera un número de transacción único con el formato:
```
TXN-YYYYMMDD-XXXXXXXX
Ejemplo: TXN-20250930-A7B3C9D2
```

**Scopes**:
- `aprobadas()` - Filtra transacciones aprobadas
- `rechazadas()` - Filtra transacciones rechazadas

**Métodos de Verificación**:
- `isAprobada(): bool` - Verifica si estado es 'aprobado'
- `isRechazada(): bool` - Verifica si estado es 'rechazado'
- `isProcesando(): bool` - Verifica si estado es 'procesando'

---

### 3. Regla de Validación Luhn

**Ubicación**: `app/Rules/Luhn.php`

**Implementación del Algoritmo de Luhn**:

El algoritmo de Luhn es un método de validación de suma de verificación utilizado para validar números de identificación, especialmente números de tarjetas de crédito.

**Uso**:
```php
use App\Rules\Luhn;

$request->validate([
    'numero_tarjeta' => ['required', new Luhn],
]);
```

**Proceso de Validación**:
```
1. Eliminar espacios y caracteres no numéricos
2. Validar longitud (13-19 dígitos)
3. Para cada dígito desde la derecha:
   a. Si está en posición par (desde la derecha): duplicar
   b. Si el duplicado > 9: restar 9
   c. Sumar todos los dígitos
4. Número válido si suma % 10 === 0
```

**Ejemplo**:
```
Número: 4111 1111 1111 1111
Limpio: 4111111111111111

Posiciones (←):  4 1 1 1 1 1 1 1 1 1 1 1 1 1 1 1
Duplicar par:    4 2 1 2 1 2 1 2 1 2 1 2 1 2 1 2
Ajuste >9:       4 2 1 2 1 2 1 2 1 2 1 2 1 2 1 2
Suma: 30
30 % 10 = 0 ✓ VÁLIDO
```

---

### 4. Componente CarritoCheckout

**Ubicación**: `app/Livewire/Cliente/CarritoCheckout.php`

**Responsabilidad**: Gestionar el formulario de checkout y coordinar el proceso de pago.

**Propiedades Principales**:
```php
public $direccion_entrega;
public $telefono_contacto;
public $notas;
public $metodo_pago = 'efectivo';
public $numero_tarjeta;
public $nombre_tarjeta;
public $fecha_vencimiento;
public $cvv;
```

**Reglas de Validación** (cuando no es efectivo):
```php
'numero_tarjeta' => ['required', 'string', new Luhn],
'nombre_tarjeta' => ['required', 'string', 'min:3', 'max:100'],
'fecha_vencimiento' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
'cvv' => ['required', 'digits_between:3,4'],
```

**Método Principal**:
```php
public function confirmarPedido()
{
    // 1. Validar datos
    // 2. Procesar pago con PagoSimuladoService
    // 3. Si es aprobado:
    //    - Crear/Actualizar pedido
    //    - Vaciar carrito
    //    - Redirigir a confirmación
    // 4. Si es rechazado:
    //    - Mostrar mensaje de error
}
```

---

### 5. Componente ProcesoPago

**Ubicación**: `app/Livewire/Cliente/ProcesoPago.php`

**Responsabilidad**: Mostrar el estado del procesamiento de pago en tiempo real.

**Propiedades**:
```php
public $transaccion_id;
public $estado = 'procesando';
public $mensaje_error = '';
```

**Funcionalidad**:
- Recibe el ID de transacción
- Verifica el estado periódicamente
- Muestra spinner mientras procesa
- Redirige según el resultado

**Uso Típico**:
```php
// Redirigir desde checkout
return redirect()->route('cliente.proceso-pago', [
    'transaccionId' => $transaccion->id
]);
```

---

## Flujo de Proceso de Pago

### Flujo Completo
```
┌─────────────────────┐
│  Cliente en         │
│  Checkout           │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Selecciona método   │
│ de pago             │
└──────────┬──────────┘
           │
           ▼
     ┌─────────┐
     │Efectivo?│
     └────┬────┘
          │
    ┌─────┴─────┐
    │           │
   SÍ          NO
    │           │
    │           ▼
    │    ┌─────────────────────┐
    │    │ Completa formulario │
    │    │ de tarjeta          │
    │    └──────────┬──────────┘
    │               │
    ▼               ▼
┌─────────────────────────────┐
│ Click "Confirmar Pedido" /  │
│ "Proceder al Pago"          │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────────────┐
│ CarritoCheckout             │
│ @confirmarPedido()          │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────────────┐
│ PagoSimuladoService         │
│ @procesarPago()             │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────────────┐
│ Crear transacción           │
│ Estado: "pendiente"         │
└──────────┬──────────────────┘
           │
           ▼
     ┌─────────┐
     │Efectivo?│
     └────┬────┘
          │
    ┌─────┴─────┐
    │           │
   SÍ          NO
    │           │
    ▼           ▼
┌────────┐  ┌──────────────────┐
│Aprobar │  │Estado: procesando│
│directo │  │Sleep(2)          │
└────┬───┘  └────────┬─────────┘
     │               │
     │               ▼
     │      ┌─────────────────┐
     │      │ Validar Luhn    │
     │      └────────┬────────┘
     │               │
     │          ┌────┴────┐
     │          │ Válida? │
     │          └────┬────┘
     │               │
     │         ┌─────┴─────┐
     │         │           │
     │        NO          SÍ
     │         │           │
     │         ▼           ▼
     │   ┌─────────┐ ┌──────────────┐
     │   │Rechazar │ │Simular       │
     │   └────┬────┘ │respuesta     │
     │        │      │pasarela      │
     │        │      └──────┬───────┘
     │        │             │
     │        │      ┌──────┴────────┐
     │        │      │Guardar detalles│
     │        │      │de tarjeta      │
     │        │      └──────┬────────┘
     │        │             │
     └────────┴─────────────┘
              │
              ▼
     ┌─────────────────┐
     │ Actualizar estado│
     │ de transacción   │
     └────────┬─────────┘
              │
              ▼
        ┌──────────┐
        │¿Aprobado?│
        └─────┬────┘
              │
        ┌─────┴─────┐
        │           │
       SÍ          NO
        │           │
        ▼           ▼
┌─────────────┐ ┌────────────┐
│Crear pedido │ │Mostrar     │
│Vaciar       │ │mensaje de  │
│carrito      │ │error       │
└──────┬──────┘ └────────────┘
       │
       ▼
┌────────────────┐
│Redirigir a     │
│confirmación    │
└────────────────┘
```

---

## Tarjetas de Prueba

### Tarjetas Predefinidas

#### ✅ Tarjetas Aprobadas (Siempre exitosas)

| Número | Tipo | Resultado |
|--------|------|-----------|
| `4111111111111111` | Visa | Pago aprobado exitosamente |
| `5555555555554444` | Mastercard | Pago aprobado exitosamente |
| `378282246310005` | American Express | Pago aprobado exitosamente |

#### ❌ Tarjetas Rechazadas (Escenarios de Error)

| Número | Tipo | Motivo de Rechazo |
|--------|------|-------------------|
| `4000000000000002` | Visa | Tarjeta rechazada por el banco |
| `4000000000009995` | Visa | Fondos insuficientes |
| `4000000000000069` | Visa | Tarjeta vencida |
| `4000000000000119` | Visa | Tarjeta bloqueada. Contacte a su banco |

#### 🎲 Otras Tarjetas Válidas
Cualquier número de tarjeta que pase la validación Luhn y no esté en las listas anteriores tendrá:
- **90% de probabilidad** de ser aprobado
- **10% de probabilidad** de ser rechazado con un mensaje aleatorio

**Mensajes de rechazo aleatorios**:
- "Tarjeta rechazada por el banco"
- "Fondos insuficientes"
- "Error en la transacción. Por favor intente nuevamente"

---

## Algoritmo de Validación Luhn

### Descripción Técnica

El algoritmo de Luhn (también conocido como "modulo 10" o "mod 10") es una fórmula de suma de verificación simple utilizada para validar números de identificación.

### Implementación Paso a Paso

```php
private function passesLuhnCheck($value): bool
{
    // 1. Preparación
    $numero = preg_replace('/\D/', '', $value); // Eliminar no-dígitos

    // 2. Validar longitud (13-19 dígitos)
    if (strlen($numero) < 13 || strlen($numero) > 19) {
        return false;
    }

    // 3. Proceso de validación
    $sum = 0;
    $length = strlen($numero);
    $parity = $length % 2;

    for ($i = 0; $i < $length; $i++) {
        $digit = (int) $numero[$i];

        // Duplicar cada segundo dígito desde la derecha
        if ($i % 2 === $parity) {
            $digit *= 2;
            // Si el resultado > 9, restar 9
            if ($digit > 9) {
                $digit -= 9;
            }
        }

        $sum += $digit;
    }

    // 4. Verificación final
    return ($sum % 10 === 0);
}
```

### Ejemplos de Validación

#### Ejemplo 1: Tarjeta Visa Válida
```
Número: 4111 1111 1111 1111
Limpio: 4111111111111111

Proceso:
Posición: 0  1  2  3  4  5  6  7  8  9  10 11 12 13 14 15
Dígito:   4  1  1  1  1  1  1  1  1  1  1  1  1  1  1  1
Parity:   ✓     ✓     ✓     ✓     ✓     ✓     ✓     ✓
Duplicar: 8  1  2  1  2  1  2  1  2  1  2  1  2  1  2  1
Ajuste:   8  1  2  1  2  1  2  1  2  1  2  1  2  1  2  1

Suma: 8+1+2+1+2+1+2+1+2+1+2+1+2+1+2+1 = 30
30 % 10 = 0 ✓ VÁLIDO
```

#### Ejemplo 2: Número Inválido
```
Número: 1234 5678 9012 3456
Limpio: 1234567890123456

Proceso:
Posición: 0  1  2  3  4  5  6  7  8  9  10 11 12 13 14 15
Dígito:   1  2  3  4  5  6  7  8  9  0  1  2  3  4  5  6
Parity:   ✓     ✓     ✓     ✓     ✓     ✓     ✓     ✓
Duplicar: 2  2  6  4  1  6  5  8  9  0  2  2  6  4  1  6
Ajuste:   2  2  6  4  1  6  5  8  9  0  2  2  6  4  1  6

Suma: 2+2+6+4+1+6+5+8+9+0+2+2+6+4+1+6 = 64
64 % 10 = 4 ✗ INVÁLIDO
```

### Ventajas del Algoritmo
- ✅ Detecta errores de dígitos individuales
- ✅ Detecta transposición de dígitos adyacentes
- ✅ Fácil de implementar
- ✅ Estándar de la industria

### Limitaciones
- ⚠️ No detecta transposición de 09 ↔ 90
- ⚠️ No valida que la tarjeta existe o esté activa
- ⚠️ Solo verifica integridad matemática

---

## Estados de Transacción

### Ciclo de Vida de una Transacción

```
┌──────────┐
│ PENDIENTE│ ← Estado inicial al crear la transacción
└────┬─────┘
     │
     ▼
┌───────────┐
│PROCESANDO │ ← Durante la validación (sleep 2s)
└─────┬─────┘
      │
      ▼
   ┌────┐
   │ ¿? │
   └┬──┬┘
    │  │
    ▼  ▼
┌────────┐  ┌──────────┐
│APROBADO│  │RECHAZADO │ ← Estados finales
└────────┘  └──────────┘
```

### Descripción de Estados

#### 1. `pendiente`
**Descripción**: Transacción creada pero no procesada aún.

**Cuándo ocurre**:
- Al iniciar el proceso de pago
- Justo después de `Transaccion::create()`

**Duración**: Milisegundos (inmediatamente pasa a procesando)

**Ejemplo**:
```php
$transaccion = Transaccion::create([
    'pedido_id' => $pedido->id,
    'metodo_pago' => 'tarjeta_credito',
    'estado' => 'pendiente', // ← Estado inicial
    'monto' => 15000.00,
    'numero_transaccion' => Transaccion::generarNumeroTransaccion(),
]);
```

---

#### 2. `procesando`
**Descripción**: Transacción en proceso de validación.

**Cuándo ocurre**:
- Después de cambiar de `pendiente`
- Durante el sleep(2) que simula procesamiento
- Durante la validación Luhn
- Durante la simulación de respuesta de pasarela

**Duración**: Aproximadamente 2 segundos

**Ejemplo**:
```php
$transaccion->update(['estado' => 'procesando']);
sleep(2); // Simular delay de pasarela
```

**Visualización en UI**:
```html
<i class="fas fa-spinner fa-spin"></i> Procesando pago...
```

---

#### 3. `aprobado`
**Descripción**: Pago procesado exitosamente.

**Cuándo ocurre**:
- Pago en efectivo (siempre aprobado)
- Tarjeta válida que pasa validación Luhn y simulación de pasarela retorna éxito

**Acción posterior**:
- Crear/Actualizar pedido
- Vaciar carrito
- Redirigir a página de confirmación

**Ejemplo**:
```php
$transaccion->update([
    'estado' => 'aprobado',
    'mensaje_respuesta' => 'Pago aprobado exitosamente',
    'detalles_tarjeta' => [
        'ultimos_digitos' => '1111',
        'tipo' => 'Visa',
        'nombre' => 'JUAN PÉREZ',
    ],
    'fecha_procesamiento' => now(),
]);
```

---

#### 4. `rechazado`
**Descripción**: Pago no pudo ser procesado.

**Cuándo ocurre**:
- Número de tarjeta inválido (falla Luhn)
- Tarjeta de prueba configurada para rechazar
- Simulación aleatoria retorna rechazo (10% de probabilidad)

**Motivos comunes**:
- "Número de tarjeta inválido"
- "Tarjeta rechazada por el banco"
- "Fondos insuficientes"
- "Tarjeta vencida"
- "Tarjeta bloqueada. Contacte a su banco"

**Acción posterior**:
- Mostrar mensaje de error al usuario
- Permitir reintentar con otro método de pago

**Ejemplo**:
```php
$transaccion->update([
    'estado' => 'rechazado',
    'mensaje_respuesta' => 'Fondos insuficientes',
    'fecha_procesamiento' => now(),
]);
```

---

### Transiciones de Estado

**Transiciones Válidas**:
```
pendiente → procesando → aprobado    ✓
pendiente → procesando → rechazado   ✓
pendiente → aprobado (efectivo)      ✓
```

**Transiciones Inválidas**:
```
aprobado → rechazado                 ✗
rechazado → aprobado                 ✗
procesando → pendiente               ✗
```

---

## Modelo de Datos

### Tabla: `transacciones`

```sql
CREATE TABLE `transacciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned DEFAULT NULL,
  `metodo_pago` enum('efectivo','tarjeta_credito','tarjeta_debito','billetera_digital') NOT NULL,
  `estado` enum('pendiente','procesando','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `monto` decimal(10,2) NOT NULL,
  `numero_transaccion` varchar(255) NOT NULL,
  `detalles_tarjeta` json DEFAULT NULL,
  `mensaje_respuesta` text DEFAULT NULL,
  `fecha_procesamiento` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transacciones_numero_transaccion_unique` (`numero_transaccion`),
  KEY `transacciones_pedido_id_foreign` (`pedido_id`),
  KEY `transacciones_estado_index` (`estado`),
  KEY `transacciones_metodo_pago_index` (`metodo_pago`),
  CONSTRAINT `transacciones_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Estructura del Campo `detalles_tarjeta` (JSON)

Cuando el pago es con tarjeta, este campo almacena información parcial de la tarjeta:

```json
{
  "ultimos_digitos": "1111",
  "tipo": "Visa",
  "nombre": "JUAN PÉREZ"
}
```

**Campos**:
- `ultimos_digitos` (string): Últimos 4 dígitos de la tarjeta
- `tipo` (string): Tipo de tarjeta (Visa, Mastercard, American Express, etc.)
- `nombre` (string): Nombre del titular en mayúsculas

**Seguridad**: ⚠️ NUNCA almacenar el número completo de la tarjeta

---

### Relaciones

#### Transaccion → Pedido
```php
// En Transaccion.php
public function pedido(): BelongsTo
{
    return $this->belongsTo(Pedido::class);
}

// Uso
$transaccion->pedido; // Obtener el pedido asociado
```

#### Pedido → Transacciones
```php
// En Pedido.php
public function transacciones(): HasMany
{
    return $this->hasMany(Transaccion::class);
}

// Uso
$pedido->transacciones; // Obtener todas las transacciones del pedido
```

---

### Índices

La tabla tiene los siguientes índices para optimizar consultas:

| Índice | Columna(s) | Tipo | Propósito |
|--------|-----------|------|-----------|
| PRIMARY | `id` | PRIMARY KEY | Identificador único |
| UNIQUE | `numero_transaccion` | UNIQUE | Evitar duplicados |
| INDEX | `pedido_id` | FOREIGN KEY | JOIN con pedidos |
| INDEX | `estado` | INDEX | Filtrar por estado |
| INDEX | `metodo_pago` | INDEX | Filtrar por método |

---

### Queries Comunes

#### Obtener transacciones aprobadas de hoy
```php
$transacciones = Transaccion::aprobadas()
    ->whereDate('created_at', today())
    ->get();
```

#### Obtener transacciones rechazadas de un pedido
```php
$transaccionesRechazadas = $pedido->transacciones()
    ->rechazadas()
    ->get();
```

#### Calcular total de ventas aprobadas
```php
$totalVentas = Transaccion::aprobadas()
    ->sum('monto');
```

#### Obtener transacciones por método de pago
```php
$transaccionesTarjeta = Transaccion::where('metodo_pago', 'tarjeta_credito')
    ->aprobadas()
    ->get();
```

---

## Seguridad

### Medidas de Seguridad Implementadas

#### ✅ 1. Validación de Algoritmo Luhn
- Valida integridad matemática del número de tarjeta
- Detecta errores de tipeo comunes
- Previene envío de números obviamente inválidos

#### ✅ 2. CVV como Campo Password
```html
<input type="password" wire:model="cvv">
```
- Oculta el CVV durante la entrada
- Previene shoulder surfing (mirar por encima del hombro)

#### ✅ 3. Almacenamiento Seguro de Tarjetas
```php
'detalles_tarjeta' => [
    'ultimos_digitos' => substr($numeroTarjeta, -4), // Solo últimos 4
    'tipo' => $this->detectarTipoTarjeta($numeroTarjeta),
    'nombre' => strtoupper($nombreTarjeta),
]
```
- **NUNCA** se guarda el número completo
- Solo últimos 4 dígitos para referencia
- CVV nunca se almacena

#### ✅ 4. Transacciones de Base de Datos
```php
try {
    DB::beginTransaction();
    // ... operaciones
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    // ... manejo de error
}
```
- Garantiza atomicidad
- Previene estados inconsistentes

#### ✅ 5. Validación en Backend
```php
protected function rules()
{
    if ($this->metodo_pago === 'efectivo') {
        return [ /* solo datos de entrega */ ];
    }

    return [
        'numero_tarjeta' => ['required', 'string', new Luhn],
        'nombre_tarjeta' => ['required', 'string', 'min:3', 'max:100'],
        'fecha_vencimiento' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
        'cvv' => ['required', 'digits_between:3,4'],
    ];
}
```
- Validación condicional según método de pago
- Múltiples capas de validación

#### ✅ 6. Sanitización de Entrada
```php
$numero = preg_replace('/\D/', '', $numeroTarjeta); // Eliminar no-dígitos
```
- Limpia espacios y caracteres especiales
- Previene inyección de caracteres maliciosos

---

### ⚠️ Limitaciones (Sistema Simulado)

El sistema actual es una **simulación para desarrollo** y tiene las siguientes limitaciones:

#### ❌ 1. Sin Encriptación en Tránsito
- No implementa HTTPS/SSL obligatorio
- Para producción: forzar HTTPS en todas las rutas de pago

#### ❌ 2. Sin Encriptación en Base de Datos
- `detalles_tarjeta` se almacena como JSON plano
- Para producción: usar encriptación de campo (Laravel Encrypted Cast)

```php
// Recomendación para producción
protected $casts = [
    'detalles_tarjeta' => 'encrypted:array',
];
```

#### ❌ 3. Sin Validación Real de Fecha de Vencimiento
- Solo valida formato (MM/AA)
- No verifica si la fecha ya expiró
- No compara con fecha actual

#### ❌ 4. Sin Validación Real de CVV
- Solo valida longitud (3-4 dígitos)
- No verifica con el banco emisor

#### ❌ 5. Sin Tokenización
- La tarjeta se procesa directamente (simulado)
- Para producción: usar tokens (Stripe, PayPal, etc.)

#### ❌ 6. Sin 3D Secure / SCA
- No implementa autenticación de dos factores
- Para producción: integrar 3DS cuando sea requerido

#### ❌ 7. Sin Logs de Auditoría
- No registra intentos fallidos
- No detecta patrones sospechosos
- Para producción: implementar sistema de logging completo

#### ❌ 8. Sin Rate Limiting
- No limita intentos de pago por IP/usuario
- Vulnerable a ataques de fuerza bruta
- Para producción: implementar throttling

---

### Recomendaciones para Producción

#### 🔒 Seguridad Obligatoria

1. **Usar Pasarela Real** (NO procesar tarjetas directamente)
   - Stripe, PayPal, MercadoPago, etc.
   - Certificación PCI DSS

2. **Implementar HTTPS/SSL**
   ```php
   // En AppServiceProvider
   if (config('app.env') === 'production') {
       URL::forceScheme('https');
   }
   ```

3. **Encriptar Datos Sensibles**
   ```php
   protected $casts = [
       'detalles_tarjeta' => 'encrypted:array',
   ];
   ```

4. **Implementar Logging**
   ```php
   Log::channel('payments')->info('Intento de pago', [
       'user_id' => $user->id,
       'monto' => $monto,
       'ip' => request()->ip(),
   ]);
   ```

5. **Rate Limiting**
   ```php
   // En routes/web.php
   Route::middleware(['throttle:5,1'])->group(function () {
       Route::post('/checkout', [CheckoutController::class, 'confirmar']);
   });
   ```

6. **Validar Fecha de Vencimiento**
   ```php
   protected function validateExpiry($fecha)
   {
       [$mes, $anio] = explode('/', $fecha);
       $expiry = Carbon::createFromDate("20{$anio}", $mes, 1)->endOfMonth();
       return $expiry->isFuture();
   }
   ```

7. **Webhooks para Estados Asíncronos**
   - Implementar endpoint para notificaciones de la pasarela
   - Validar firma de webhook

---

## Integración con Pasarela Real

### Migración a Stripe (Ejemplo)

#### Paso 1: Instalar SDK
```bash
composer require stripe/stripe-php
```

#### Paso 2: Crear Service Real
```php
// app/Services/PagoStripeService.php
namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Transaccion;

class PagoStripeService implements PagoServiceInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function procesarPago(array $datos): array
    {
        try {
            DB::beginTransaction();

            // Crear transacción
            $transaccion = Transaccion::create([
                'pedido_id' => $datos['pedido_id'],
                'metodo_pago' => $datos['metodo_pago'],
                'estado' => 'pendiente',
                'monto' => $datos['monto'],
                'numero_transaccion' => Transaccion::generarNumeroTransaccion(),
            ]);

            // Crear Payment Intent en Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $datos['monto'] * 100, // Convertir a centavos
                'currency' => 'pyg',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'transaccion_id' => $transaccion->id,
                    'pedido_id' => $datos['pedido_id'],
                ],
            ]);

            $transaccion->update([
                'estado' => 'procesando',
                'external_id' => $paymentIntent->id,
            ]);

            DB::commit();

            return [
                'success' => true,
                'transaccion' => $transaccion,
                'client_secret' => $paymentIntent->client_secret,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'transaccion' => null,
                'mensaje' => $e->getMessage(),
            ];
        }
    }
}
```

#### Paso 3: Implementar Interface
```php
// app/Services/PagoServiceInterface.php
namespace App\Services;

interface PagoServiceInterface
{
    public function procesarPago(array $datos): array;
    public function verificarEstadoTransaccion(int $transaccionId);
}
```

#### Paso 4: Configurar Service Provider
```php
// app/Providers/AppServiceProvider.php
use App\Services\PagoServiceInterface;
use App\Services\PagoSimuladoService;
use App\Services\PagoStripeService;

public function register()
{
    $this->app->bind(PagoServiceInterface::class, function ($app) {
        if (config('app.env') === 'production') {
            return new PagoStripeService();
        }
        return new PagoSimuladoService();
    });
}
```

#### Paso 5: Usar en Componente
```php
// app/Livewire/Cliente/CarritoCheckout.php
use App\Services\PagoServiceInterface;

public function confirmarPedido(PagoServiceInterface $pagoService)
{
    $this->validate();

    $resultado = $pagoService->procesarPago([
        'pedido_id' => $this->pedido_id,
        'metodo_pago' => $this->metodo_pago,
        'monto' => $this->total,
        // ... más datos
    ]);

    if ($resultado['success']) {
        // Éxito
    } else {
        // Error
    }
}
```

#### Paso 6: Implementar Webhook
```php
// routes/web.php
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// app/Http/Controllers/StripeWebhookController.php
public function handle(Request $request)
{
    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $webhookSecret = config('services.stripe.webhook_secret');

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $sigHeader,
            $webhookSecret
        );
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }

    // Manejar evento
    switch ($event->type) {
        case 'payment_intent.succeeded':
            $paymentIntent = $event->data->object;
            $this->handlePaymentSuccess($paymentIntent);
            break;

        case 'payment_intent.payment_failed':
            $paymentIntent = $event->data->object;
            $this->handlePaymentFailed($paymentIntent);
            break;
    }

    return response()->json(['status' => 'success']);
}

private function handlePaymentSuccess($paymentIntent)
{
    $transaccion = Transaccion::where('external_id', $paymentIntent->id)->first();

    $transaccion->update([
        'estado' => 'aprobado',
        'mensaje_respuesta' => 'Pago aprobado exitosamente',
        'fecha_procesamiento' => now(),
    ]);

    // Crear pedido si aún no existe
    // ...
}
```

---

### Ventajas de Usar Pasarela Real

| Aspecto | Simulado | Pasarela Real |
|---------|----------|---------------|
| **Seguridad** | ⚠️ Básica | ✅ PCI DSS Certificada |
| **Encriptación** | ❌ No | ✅ SSL/TLS |
| **Tokenización** | ❌ No | ✅ Sí |
| **3D Secure** | ❌ No | ✅ Sí |
| **Webhooks** | ❌ No | ✅ Sí |
| **Monitoreo** | ❌ No | ✅ Dashboard completo |
| **Disputa/Chargeback** | ❌ No | ✅ Gestión completa |
| **Múltiples Monedas** | ❌ No | ✅ Sí |
| **Costo** | ✅ Gratis | ⚠️ Comisión por transacción |

---

## Ejemplos de Uso

### Ejemplo 1: Pago en Efectivo

```php
// En CarritoCheckout.php
public function confirmarPedido()
{
    $this->validate([
        'direccion_entrega' => 'required|string|max:255',
        'telefono_contacto' => 'required|string|max:20',
        'metodo_pago' => 'required|in:efectivo',
    ]);

    $pagoService = new PagoSimuladoService();

    $resultado = $pagoService->procesarPago([
        'metodo_pago' => 'efectivo',
        'monto' => $this->total,
    ]);

    if ($resultado['success']) {
        // Crear pedido
        $pedido = Pedido::create([
            'user_id' => auth()->id(),
            'direccion_entrega' => $this->direccion_entrega,
            'telefono_contacto' => $this->telefono_contacto,
            'metodo_pago' => 'efectivo',
            'estado' => 'pendiente',
            'total' => $this->total,
        ]);

        // Asociar transacción
        $resultado['transaccion']->update([
            'pedido_id' => $pedido->id,
        ]);

        // Vaciar carrito
        CarritoItem::where('user_id', auth()->id())->delete();

        // Redirigir
        return redirect()->route('cliente.pedido-confirmacion', [
            'pedidoId' => $pedido->id,
        ]);
    }
}
```

**Resultado**:
- Transacción creada con estado `aprobado`
- Mensaje: "Pago en efectivo - Pendiente de recibir"
- Pedido creado inmediatamente
- Sin procesamiento adicional

---

### Ejemplo 2: Pago con Tarjeta Aprobada

```php
// Datos de entrada
$datos = [
    'metodo_pago' => 'tarjeta_credito',
    'monto' => 15000.00,
    'numero_tarjeta' => '4111111111111111', // Tarjeta de prueba aprobada
    'nombre_tarjeta' => 'Juan Pérez',
    'fecha_vencimiento' => '12/25',
    'cvv' => '123',
];

$pagoService = new PagoSimuladoService();
$resultado = $pagoService->procesarPago($datos);

// Resultado esperado
[
    'success' => true,
    'transaccion' => Transaccion {
        id: 1,
        estado: 'aprobado',
        monto: 15000.00,
        numero_transaccion: 'TXN-20250930-A7B3C9D2',
        detalles_tarjeta: [
            'ultimos_digitos' => '1111',
            'tipo' => 'Visa',
            'nombre' => 'JUAN PÉREZ',
        ],
        mensaje_respuesta: 'Pago aprobado exitosamente',
    ],
    'mensaje' => 'Pago aprobado exitosamente',
]
```

**Flujo**:
1. Crear transacción `pendiente`
2. Cambiar a `procesando`
3. Sleep 2 segundos (simular)
4. Validar Luhn ✓
5. Simular respuesta ✓ (tarjeta en lista aprobada)
6. Guardar detalles
7. Actualizar a `aprobado`

---

### Ejemplo 3: Pago con Tarjeta Rechazada

```php
$datos = [
    'metodo_pago' => 'tarjeta_credito',
    'monto' => 25000.00,
    'numero_tarjeta' => '4000000000009995', // Fondos insuficientes
    'nombre_tarjeta' => 'María García',
    'fecha_vencimiento' => '06/26',
    'cvv' => '456',
];

$pagoService = new PagoSimuladoService();
$resultado = $pagoService->procesarPago($datos);

// Resultado esperado
[
    'success' => false,
    'transaccion' => Transaccion {
        id: 2,
        estado: 'rechazado',
        monto: 25000.00,
        numero_transaccion: 'TXN-20250930-B8C4D3E1',
        detalles_tarjeta: [
            'ultimos_digitos' => '9995',
            'tipo' => 'Visa',
            'nombre' => 'MARÍA GARCÍA',
        ],
        mensaje_respuesta: 'Fondos insuficientes',
    ],
    'mensaje' => 'Fondos insuficientes',
]
```

**Flujo**:
1. Crear transacción `pendiente`
2. Cambiar a `procesando`
3. Sleep 2 segundos
4. Validar Luhn ✓
5. Simular respuesta ✗ (tarjeta en lista rechazada)
6. Guardar detalles
7. Actualizar a `rechazado`

---

### Ejemplo 4: Tarjeta con Número Inválido

```php
$datos = [
    'metodo_pago' => 'tarjeta_credito',
    'monto' => 10000.00,
    'numero_tarjeta' => '1234567890123456', // No pasa Luhn
    'nombre_tarjeta' => 'Pedro López',
    'fecha_vencimiento' => '03/27',
    'cvv' => '789',
];

$pagoService = new PagoSimuladoService();
$resultado = $pagoService->procesarPago($datos);

// Resultado esperado
[
    'success' => false,
    'transaccion' => Transaccion {
        id: 3,
        estado: 'rechazado',
        monto: 10000.00,
        numero_transaccion: 'TXN-20250930-C9D5E2F1',
        detalles_tarjeta: null,
        mensaje_respuesta: 'Número de tarjeta inválido',
    },
    'mensaje' => 'Número de tarjeta inválido',
]
```

**Flujo**:
1. Crear transacción `pendiente`
2. Cambiar a `procesando`
3. Sleep 2 segundos
4. Validar Luhn ✗ (falla)
5. Actualizar a `rechazado` sin simular pasarela
6. No guardar detalles de tarjeta

---

### Ejemplo 5: Consultar Estado de Transacción

```php
$pagoService = new PagoSimuladoService();
$transaccion = $pagoService->verificarEstadoTransaccion(1);

if ($transaccion->isAprobada()) {
    echo "Pago aprobado con éxito";
    echo "Número de transacción: {$transaccion->numero_transaccion}";
    echo "Monto: \${$transaccion->monto}";
} elseif ($transaccion->isRechazada()) {
    echo "Pago rechazado: {$transaccion->mensaje_respuesta}";
} elseif ($transaccion->isProcesando()) {
    echo "Pago en proceso...";
}
```

---

### Ejemplo 6: Obtener Tarjetas de Prueba

```php
$pagoService = new PagoSimuladoService();
$tarjetasPrueba = $pagoService->getTarjetasPrueba();

foreach ($tarjetasPrueba as $numero => $info) {
    echo "Tarjeta: {$numero}\n";
    echo "Estado: {$info['estado']}\n";
    echo "Mensaje: {$info['mensaje']}\n\n";
}

// Output:
// Tarjeta: 4111111111111111
// Estado: aprobado
// Mensaje: Pago aprobado exitosamente
//
// Tarjeta: 4000000000000002
// Estado: rechazado
// Mensaje: Tarjeta rechazada por el banco
// ...
```

---

### Ejemplo 7: Uso en Blade

```blade
<!-- carrito-checkout.blade.php -->
<form wire:submit="confirmarPedido">
    <!-- Método de pago -->
    <div>
        <label>
            <input type="radio" wire:model.live="metodo_pago" value="efectivo">
            Efectivo
        </label>
        <label>
            <input type="radio" wire:model.live="metodo_pago" value="tarjeta_credito">
            Tarjeta de Crédito
        </label>
    </div>

    <!-- Formulario de tarjeta (solo si no es efectivo) -->
    @if($metodo_pago !== 'efectivo')
    <div>
        <input type="text"
               wire:model="numero_tarjeta"
               placeholder="1234 5678 9012 3456"
               maxlength="19">
        @error('numero_tarjeta')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text"
               wire:model="nombre_tarjeta"
               placeholder="JUAN PÉREZ">
        @error('nombre_tarjeta')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="text"
               wire:model="fecha_vencimiento"
               placeholder="MM/AA"
               maxlength="5">
        @error('fecha_vencimiento')
            <span class="error">{{ $message }}</span>
        @enderror

        <input type="password"
               wire:model="cvv"
               placeholder="123"
               maxlength="4">
        @error('cvv')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>
    @endif

    <button type="submit" wire:loading.attr="disabled">
        <span wire:loading.remove>
            @if($metodo_pago === 'efectivo')
                Confirmar Pedido
            @else
                Proceder al Pago
            @endif
        </span>
        <span wire:loading>
            Procesando...
        </span>
    </button>
</form>
```

---

## Conclusión

Este sistema de pasarela de pagos simulada proporciona una base sólida para desarrollo y testing del flujo de checkout. Sus características principales incluyen:

✅ **Validación robusta** con algoritmo de Luhn
✅ **Múltiples métodos de pago** soportados
✅ **Tarjetas de prueba** predefinidas para diferentes escenarios
✅ **Gestión completa de estados** de transacción
✅ **Simulación realista** con delays y comportamientos variados
✅ **Arquitectura extensible** lista para migración a pasarela real

### Próximos Pasos

Para llevar este sistema a producción:

1. **Integrar pasarela real** (Stripe, PayPal, MercadoPago)
2. **Implementar HTTPS** en todo el flujo de pago
3. **Encriptar datos sensibles** en base de datos
4. **Agregar logging y monitoreo** completo
5. **Implementar rate limiting** para prevenir abusos
6. **Configurar webhooks** para actualizaciones asíncronas
7. **Realizar pruebas de seguridad** (penetration testing)
8. **Implementar 3D Secure** para autenticación adicional

---

## Referencias

- [Algoritmo de Luhn - Wikipedia](https://es.wikipedia.org/wiki/Algoritmo_de_Luhn)
- [PCI DSS Compliance](https://www.pcisecuritystandards.org/)
- [Stripe API Documentation](https://stripe.com/docs/api)
- [Laravel Payment Processing](https://laravel.com/docs/billing)
- [OWASP Payment Card Industry](https://owasp.org/www-community/vulnerabilities/Payment_Card_Industry_Data_Security_Standard)

---

**Última actualización**: 2025-09-30
**Versión**: 1.0
**Autor**: Sistema de Tesis - Combate Mborore
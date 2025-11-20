<?php

namespace App\Livewire\Cliente;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Transaccion;
use App\Rules\Luhn;
use App\Services\PagoSimuladoService;
use App\Traits\HasShoppingCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cliente')]
class CarritoCheckout extends Component
{
    use HasShoppingCart;

    // Datos de entrega
    public $direccion_entrega = '';
    public $telefono_contacto = '';
    public $notas = '';

    // Datos de pago
    public $metodo_pago = 'efectivo';
    public $numero_tarjeta = '';
    public $nombre_tarjeta = '';
    public $fecha_vencimiento = '';
    public $cvv = '';

    // Modal de error
    public $mostrarModalError = false;
    public $mensajeError = '';
    public $tipoError = ''; // rechazado, fondos, vencida, bloqueada, invalido, general

    protected function rules()
    {
        $rules = [
            'direccion_entrega' => 'required|string|min:10|max:255',
            'telefono_contacto' => 'required|string|min:9|max:20',
            'notas' => 'nullable|string|max:500',
            'metodo_pago' => 'required|in:efectivo,tarjeta_credito,tarjeta_debito,billetera_digital',
        ];

        // Si el método de pago no es efectivo, validar datos de tarjeta
        if ($this->metodo_pago !== 'efectivo') {
            $rules['numero_tarjeta'] = ['required', 'string', new Luhn()];
            $rules['nombre_tarjeta'] = 'required|string|min:3|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/';
            $rules['fecha_vencimiento'] = [
                'required',
                'string',
                'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                function ($attribute, $value, $fail) {
                    if (!$this->validarFechaVencimiento($value)) {
                        $fail('La tarjeta está vencida.');
                    }
                },
            ];
            $rules['cvv'] = 'required|digits_between:3,4';
        }

        return $rules;
    }

    protected $messages = [
        'direccion_entrega.required' => 'La dirección de entrega es obligatoria',
        'direccion_entrega.min' => 'La dirección debe tener al menos 10 caracteres',
        'telefono_contacto.required' => 'El teléfono de contacto es obligatorio',
        'telefono_contacto.min' => 'El teléfono debe tener al menos 9 dígitos',
        'metodo_pago.required' => 'Debes seleccionar un método de pago',
        'numero_tarjeta.required' => 'El número de tarjeta es obligatorio',
        'nombre_tarjeta.required' => 'El nombre en la tarjeta es obligatorio',
        'nombre_tarjeta.regex' => 'El nombre solo puede contener letras y espacios',
        'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria',
        'fecha_vencimiento.regex' => 'El formato de fecha debe ser MM/AA',
        'cvv.required' => 'El CVV es obligatorio',
        'cvv.digits_between' => 'El CVV debe tener entre 3 y 4 dígitos',
    ];

    public function mount()
    {
        // Si el carrito está vacío (productos y promociones), redirigir a la página principal
        if ($this->getCartCount() === 0 && $this->getPromocionesItems()->count() === 0) {
            return redirect()->route('cliente.bienvenida')
                ->with('error', 'Tu carrito está vacío');
        }

        // Prellenar con datos del usuario si existen
        $user = Auth::user();
        if($user) {
            $this->telefono_contacto = $user->telefono;
            $this->direccion_entrega = $user->direccion;
        }
    }

    public function cerrarModalError()
    {
        $this->mostrarModalError = false;
        $this->mensajeError = '';
        $this->tipoError = '';
    }

    public function getItemsProperty()
    {
        return $this->getCartItems();
    }

    public function getPromocionesProperty()
    {
        return $this->getPromocionesItems();
    }

    public function getCountProperty()
    {
        return $this->getCartCount();
    }

    public function getTotalProperty()
    {
        return $this->getCartTotal();
    }

    /**
     * Validar fecha de vencimiento
     */
    private function validarFechaVencimiento($valor): bool
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $valor, $matches)) {
            return false;
        }

        $mes = (int) $matches[1];
        $año = (int) $matches[2] + 2000;

        $fecha = \Carbon\Carbon::create($año, $mes, 1)->endOfMonth();

        return !$fecha->isPast();
    }

    public function confirmarPedido()
    {
        // Validar el formulario
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación, hacer scroll al inicio
            $this->dispatch('scroll-to-top');
            throw $e;
        }

        try {
            DB::beginTransaction();

            // Crear el pedido
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'numero_pedido' => Pedido::generarNumeroPedido(),
                'estado' => 'pendiente',
                'estado_pago' => 'pendiente',
                'metodo_pago_preferido' => $this->metodo_pago,
                'subtotal' => $this->total,
                'total' => $this->total,
                'direccion_entrega' => $this->direccion_entrega,
                'telefono_contacto' => $this->telefono_contacto,
                'notas' => $this->notas,
            ]);
            // Crear los detalles del pedido para productos
            foreach ($this->items as $item) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto->id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $item->subtotal,
                ]);
            }
            // Crear los detalles del pedido para promociones
            foreach ($this->getPromocionesItems() as $item) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => null,
                    'promocion_id' => $item->promocion->id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $item->subtotal,
                ]);
            }

            // Procesar el pago
            $pagoService = new PagoSimuladoService();

            $datosPago = [
                'pedido_id' => $pedido->id,
                'metodo_pago' => $this->metodo_pago,
                'monto' => $this->total,
                'numero_tarjeta' => preg_replace('/\D/', '', $this->numero_tarjeta),
                'nombre_tarjeta' => $this->nombre_tarjeta,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'cvv' => $this->cvv,
            ];

            $resultadoPago = $pagoService->procesarPago($datosPago);
            if (!$resultadoPago['success']) {
                DB::rollBack();

                Log::warning('Pago rechazado', [
                    'pedido_id' => $pedido->id,
                    'mensaje' => $resultadoPago['mensaje'],
                ]);

                // Determinar tipo de error para el modal
                $mensaje = $resultadoPago['mensaje'];
                $tipo = 'general';

                if (str_contains(strtolower($mensaje), 'rechazada')) {
                    $tipo = 'rechazado';
                } elseif (str_contains(strtolower($mensaje), 'fondos insuficientes')) {
                    $tipo = 'fondos';
                } elseif (str_contains(strtolower($mensaje), 'vencida')) {
                    $tipo = 'vencida';
                } elseif (str_contains(strtolower($mensaje), 'bloqueada')) {
                    $tipo = 'bloqueada';
                } elseif (str_contains(strtolower($mensaje), 'inválido')) {
                    $tipo = 'invalido';
                }

                $this->mensajeError = $mensaje;
                $this->tipoError = $tipo;
                $this->mostrarModalError = true;
                $this->dispatch('scroll-to-top');
                return;
            }
            $pedido->update(['estado_pago' => 'pagado']);

            DB::commit();

            Log::info('Pedido creado exitosamente', [
                'pedido_id' => $pedido->id,
                'numero_pedido' => $pedido->numero_pedido,
                'transaccion_id' => $resultadoPago['transaccion']->id,
                'user_id' => Auth::id(),
            ]);

            // Vaciar el carrito (productos y promociones)
            $this->clearAllCarts();

            // Redirigir según el método de pago
            if ($this->metodo_pago === 'efectivo') {
                return redirect()->route('cliente.pedido.confirmacion', $pedido->id)
                    ->with('success', '¡Pedido realizado con éxito!');
            } else {
                return redirect()->route('cliente.pago.procesando', $resultadoPago['transaccion']->id);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear pedido', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            $this->mensajeError = 'Hubo un error al procesar tu pedido. Por favor, intenta nuevamente.' . $e->getMessage();
            $this->tipoError = 'general';
            $this->mostrarModalError = true;
            $this->dispatch('scroll-to-top');
        }
    }

    public function render()
    {
        return view('livewire.cliente.carrito-checkout', [
            'items' => $this->items,
            'promociones' => $this->promociones,
            'count' => $this->count,
            'total' => $this->total,
        ]);
    }
}
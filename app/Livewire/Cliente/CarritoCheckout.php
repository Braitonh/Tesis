<?php

namespace App\Livewire\Cliente;

use App\Models\DetallePedido;
use App\Models\Pedido;
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

    public $direccion_entrega = '';
    public $telefono_contacto = '';
    public $notas = '';

    protected $rules = [
        'direccion_entrega' => 'required|string|min:10|max:255',
        'telefono_contacto' => 'required|string|min:9|max:20',
        'notas' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'direccion_entrega.required' => 'La dirección de entrega es obligatoria',
        'direccion_entrega.min' => 'La dirección debe tener al menos 10 caracteres',
        'telefono_contacto.required' => 'El teléfono de contacto es obligatorio',
        'telefono_contacto.min' => 'El teléfono debe tener al menos 9 dígitos',
    ];

    public function mount()
    {
        // Si el carrito está vacío, redirigir a la página principal
        if ($this->getCartCount() === 0) {
            return redirect()->route('cliente.bienvenida')
                ->with('error', 'Tu carrito está vacío');
        }

        // Prellenar con datos del usuario si existen
        $user = Auth::user();
        if ($user->telefono) {
            $this->telefono_contacto = $user->telefono;
        }
    }

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

    public function confirmarPedido()
    {
        // Validar el formulario
        $this->validate();

        try {
            DB::beginTransaction();

            // Crear el pedido
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'numero_pedido' => Pedido::generarNumeroPedido(),
                'estado' => 'pendiente',
                'subtotal' => $this->total,
                'total' => $this->total,
                'direccion_entrega' => $this->direccion_entrega,
                'telefono_contacto' => $this->telefono_contacto,
                'notas' => $this->notas,
            ]);

            // Crear los detalles del pedido
            foreach ($this->items as $item) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto->id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio,
                    'subtotal' => $item->subtotal,
                ]);
            }

            // Recalcular el total del pedido (por si acaso)
            $pedido->calcularTotal();

            DB::commit();

            Log::info('Pedido creado exitosamente', [
                'pedido_id' => $pedido->id,
                'numero_pedido' => $pedido->numero_pedido,
                'user_id' => Auth::id(),
            ]);

            // Vaciar el carrito
            $this->clearCart();

            // Redirigir a la página de confirmación
            return redirect()->route('cliente.pedido.confirmacion', $pedido->id)
                ->with('success', '¡Pedido realizado con éxito!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear pedido', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            session()->flash('error', 'Hubo un error al procesar tu pedido. Por favor, intenta nuevamente.');
        }
    }

    public function render()
    {
        return view('livewire.cliente.carrito-checkout', [
            'items' => $this->items,
            'count' => $this->count,
            'total' => $this->total,
        ]);
    }
}
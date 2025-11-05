<?php

namespace App\Livewire\Cliente;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Promocion;
use App\Traits\HasShoppingCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

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

    public function getProductosDestacados()
    {
        return Producto::with('categoria')
            ->active()
            ->destacado()
            ->disponible()
            ->ordered()
            ->limit(3)
            ->get();
    }

    public function getProductos()
    {
        $query = Producto::with('categoria')
            ->active()
            ->disponible();

        if ($this->categoriaSeleccionada) {
            $query->where('categoria_id', $this->categoriaSeleccionada);
        }

        return $query->ordered()->get();
    }

    public function getCategoriasProperty()
    {
        return Categoria::active()
            ->withCount(['productos' => function ($query) {
                $query->active()->disponible();
            }])
            ->orderBy('nombre')
            ->get();
    }

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
            Log::warning('Producto no disponible', ['producto' => $producto->nombre, 'activo' => $producto->activo, 'estado' => $producto->estado]);
            session()->flash('error', 'Este producto no está disponible');
            return;
        }

        $this->addToCart($productoId, 1);
        Log::info('Producto agregado al carrito', ['producto' => $producto->nombre, 'carrito' => $this->getCart()]);

        $this->dispatch('carrito-actualizado');
        session()->flash('message', "¡{$producto->nombre} agregado al carrito!");
    }

    public function limpiarCarrito()
    {
        $this->clearCart();
        $this->dispatch('carrito-actualizado');
        session()->flash('message', 'Carrito limpiado completamente');
    }

    public function getPromocionesDestacadas()
    {
        return Promocion::with('productos.categoria')
            ->active()
            ->destacado()
            ->ordered()
            ->limit(3)
            ->get();
    }

    #[On('agregar-promocion-al-carrito')]
    public function agregarPromocionAlCarrito($promocionId)
    {
        Log::info('Método agregarPromocionAlCarrito llamado', ['promocionId' => $promocionId]);

        $promocion = Promocion::with('productos')->find($promocionId);

        if (!$promocion) {
            Log::warning('Promoción no encontrada', ['promocionId' => $promocionId]);
            session()->flash('error', 'Promoción no encontrada');
            return;
        }

        if (!$promocion->activo) {
            Log::warning('Promoción no activa', ['promocion' => $promocion->nombre]);
            session()->flash('error', 'Esta promoción no está disponible');
            return;
        }

        if (!$promocion->verificarStock(1)) {
            Log::warning('Promoción sin stock', ['promocion' => $promocion->nombre]);
            session()->flash('error', 'No hay stock suficiente para esta promoción');
            return;
        }

        $this->addPromocionToCart($promocionId, 1);
        Log::info('Promoción agregada al carrito', ['promocion' => $promocion->nombre, 'carrito' => $this->getCart()]);

        $this->dispatch('carrito-actualizado');
        session()->flash('message', "¡{$promocion->nombre} agregada al carrito!");
    }

    public function render()
    {
        return view('livewire.cliente.cliente-bienvenida', [
            'productosDestacados' => $this->getProductosDestacados(),
            'productos' => $this->getProductos(),
            'promocionesDestacadas' => $this->getPromocionesDestacadas(),
            'categorias' => $this->categorias,
        ]);
    }
}

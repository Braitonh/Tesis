<?php

namespace App\Livewire\Cliente;

use App\Models\Pedido;
use App\Traits\HasShoppingCart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cliente')]
class MisPedidos extends Component
{
    use WithPagination, HasShoppingCart;

    public $filtroEstado = 'todos';
    public $pedidoSeleccionado = null;
    public $mostrarDetalles = false;

    protected $queryString = ['filtroEstado'];

    public function mount()
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function filtrarPor($estado)
    {
        $this->filtroEstado = $estado;
        $this->resetPage();
    }

    public function verDetalles($pedidoId)
    {
        $pedido = Pedido::with(['detalles.producto', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($pedidoId);

        $this->pedidoSeleccionado = $pedido;
        $this->mostrarDetalles = true;
    }

    public function cerrarDetalles()
    {
        $this->mostrarDetalles = false;
        $this->pedidoSeleccionado = null;
    }

    public function volverAPedir($pedidoId)
    {
        $pedido = Pedido::with('detalles.producto')
            ->where('user_id', Auth::id())
            ->findOrFail($pedidoId);

        $productosAgregados = 0;

        foreach ($pedido->detalles as $detalle) {
            // Verificar que el producto aún exista y esté activo
            if ($detalle->producto && $detalle->producto->estado === 'activo') {
                $this->addToCart($detalle->producto_id, $detalle->cantidad);
                $productosAgregados++;
            }
        }

        $this->dispatch('carrito-actualizado');
        $this->cerrarDetalles();

        if ($productosAgregados > 0) {
            session()->flash('message', "Se agregaron {$productosAgregados} productos al carrito");
            return redirect()->route('cliente.bienvenida');
        } else {
            session()->flash('error', 'No se pudieron agregar productos (no disponibles)');
        }
    }

    public function getPedidosProperty()
    {
        $query = Pedido::with(['detalles'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Aplicar filtro de estado
        switch ($this->filtroEstado) {
            case 'en_proceso':
                $query->enProceso();
                break;
            case 'entregados':
                $query->completados();
                break;
            case 'cancelados':
                $query->cancelados();
                break;
            // 'todos' no necesita filtro adicional
        }

        return $query->paginate(15);
    }

    public function getTotalPedidosProperty()
    {
        return Pedido::where('user_id', Auth::id())->count();
    }

    public function getPedidosActivosProperty()
    {
        return Pedido::where('user_id', Auth::id())
            ->enProceso()
            ->count();
    }

    public function getTotalGastadoProperty()
    {
        return Pedido::where('user_id', Auth::id())
            ->where('estado', '!=', 'cancelado')
            ->sum('total');
    }

    public function render()
    {
        return view('livewire.cliente.mis-pedidos', [
            'pedidos' => $this->pedidos,
            'totalPedidos' => $this->totalPedidos,
            'pedidosActivos' => $this->pedidosActivos,
            'totalGastado' => $this->totalGastado,
        ]);
    }
}
<?php

namespace App\Livewire\Dashboard;

use App\Models\Pedido;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.dashboard-layout')]
class AdminClientes extends Component
{
    use WithPagination;

    // Modales
    public $showStatsModal = false;
    public $clienteSeleccionado;

    // Filtros
    public $search = '';
    public $filtroBloqueado = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroBloqueado()
    {
        $this->resetPage();
    }

    public function toggleBlock($clienteId)
    {
        $cliente = User::findOrFail($clienteId);
        
        if ($cliente->role !== 'cliente') {
            session()->flash('error', 'Solo se pueden bloquear clientes.');
            return;
        }

        $cliente->update([
            'is_blocked' => !$cliente->is_blocked
        ]);

        $accion = $cliente->is_blocked ? 'bloqueado' : 'desbloqueado';
        session()->flash('message', "Cliente {$accion} correctamente.");
    }

    public function verEstadisticas($clienteId)
    {
        $this->clienteSeleccionado = User::with('pedidos')->findOrFail($clienteId);
        $this->showStatsModal = true;
    }

    public function closeStatsModal()
    {
        $this->showStatsModal = false;
        $this->clienteSeleccionado = null;
    }

    public function getEstadisticasClienteProperty()
    {
        if (!$this->clienteSeleccionado) {
            return null;
        }

        $pedidos = $this->clienteSeleccionado->pedidos;
        $pedidosPagados = $pedidos->where('estado_pago', 'pagado');
        $pedidosCompletados = $pedidos->where('estado', 'entregado');
        $pedidosCancelados = $pedidos->where('estado', 'cancelado');
        
        $totalGastado = $pedidosPagados->sum('total');
        $totalPedidos = $pedidos->count();
        $promedioPorPedido = $totalPedidos > 0 ? $totalGastado / $totalPedidos : 0;
        
        $ultimoPedido = $pedidos->sortByDesc('created_at')->first();

        return [
            'total_pedidos' => $totalPedidos,
            'pedidos_completados' => $pedidosCompletados->count(),
            'pedidos_cancelados' => $pedidosCancelados->count(),
            'total_gastado' => $totalGastado,
            'promedio_por_pedido' => $promedioPorPedido,
            'ultimo_pedido' => $ultimoPedido ? [
                'numero' => $ultimoPedido->numero_pedido,
                'fecha' => $ultimoPedido->created_at->format('d/m/Y H:i'),
                'total' => $ultimoPedido->total,
                'estado' => $ultimoPedido->estado,
            ] : null,
        ];
    }

    public function getStatsProperty()
    {
        $clientes = User::clientes();
        
        return [
            'total' => $clientes->count(),
            'activos' => $clientes->where('is_blocked', false)->count(),
            'bloqueados' => $clientes->where('is_blocked', true)->count(),
            'verificados' => $clientes->whereNotNull('email_verified_at')->count(),
        ];
    }

    public function render()
    {
        $clientes = User::clientes()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->when($this->filtroBloqueado === 'bloqueados', function ($query) {
                $query->where('is_blocked', true);
            })
            ->when($this->filtroBloqueado === 'activos', function ($query) {
                $query->where('is_blocked', false);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.dashboard.admin-clientes', [
            'clientes' => $clientes,
            'stats' => $this->stats,
            'estadisticasCliente' => $this->estadisticasCliente,
        ]);
    }
}


<?php

namespace App\Livewire\Cocina;

use App\Models\Pedido;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.dashboard-layout')]
class Cocina extends Component
{
    // Modal de detalles
    public $showDetailModal = false;
    public $pedidoSeleccionado;

    // Para auto-refresh (polling cada 30 segundos)
    public function mount()
    {
        //
    }

    /**
     * Escuchar eventos de WebSocket para actualizar en tiempo real
     */
    protected function getListeners()
    {
        return [
            "echo:cocina,.pedido.creado" => '$refresh',
            "echo:cocina,.pedido.cambio-estado" => '$refresh',
            "echo:cocina,.pedido.cancelado" => '$refresh',
        ];
    }

    /**
     * Obtener pedidos pendientes de cocinar (estado: pendiente con pago confirmado)
     */
    public function getPedidosPendientesProperty()
    {
        return Pedido::with(['user', 'detalles.producto'])
            ->where('estado', 'pendiente')
            ->where('estado_pago', 'pagado')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($pedido) {
                $pedido->minutos_transcurridos = Carbon::parse($pedido->created_at)->diffInMinutes(now());
                $pedido->es_urgente = $pedido->minutos_transcurridos > 15;
                return $pedido;
            });
    }

    /**
     * Obtener pedidos en preparación activa
     */
    public function getPedidosEnPreparacionProperty()
    {
        return Pedido::with(['user', 'detalles.producto'])
            ->where('estado', 'en_preparacion')
            ->orderBy('updated_at', 'asc')
            ->get()
            ->map(function ($pedido) {
                $pedido->minutos_transcurridos = Carbon::parse($pedido->updated_at)->diffInMinutes(now());
                return $pedido;
            });
    }

    /**
     * Obtener pedidos listos para entregar
     */
    public function getPedidosListosProperty()
    {
        return Pedido::with(['user', 'detalles.producto'])
            ->where('estado', 'listo')
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();
    }

    /**
     * Calcular estadísticas
     */
    public function getStatsProperty()
    {
        $pedidosHoy = Pedido::whereDate('created_at', today())
            ->where('estado_pago', 'pagado');

        // Calcular tiempo promedio de pedidos completados hoy
        $tiempoPromedio = 0;
        $pedidosCompletadosHoy = Pedido::whereDate('updated_at', today())
            ->where('estado', 'listo')
            ->get();

        if ($pedidosCompletadosHoy->count() > 0) {
            $totalMinutos = 0;
            foreach ($pedidosCompletadosHoy as $pedido) {
                $totalMinutos += Carbon::parse($pedido->created_at)->diffInMinutes($pedido->updated_at);
            }
            $tiempoPromedio = round($totalMinutos / $pedidosCompletadosHoy->count());
        }

        return [
            'pendientes' => Pedido::where('estado', 'pendiente')
                ->where('estado_pago', 'pagado')
                ->count(),
            'en_preparacion' => Pedido::where('estado', 'en_preparacion')->count(),
            'completados_hoy' => $pedidosCompletadosHoy->count(),
            'tiempo_promedio' => $tiempoPromedio,
        ];
    }

    /**
     * Iniciar preparación de un pedido
     */
    public function iniciarPreparacion($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);

        if ($pedido->estado === 'pendiente' && $pedido->estado_pago === 'pagado') {
            $pedido->update(['estado' => 'en_preparacion']);

            session()->flash('message', "Pedido {$pedido->numero_pedido} iniciado en cocina");
        }
    }

    /**
     * Marcar pedido como listo
     */
    public function marcarComoListo($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);

        if ($pedido->estado === 'en_preparacion') {
            $pedido->update(['estado' => 'listo']);

            session()->flash('message', "Pedido {$pedido->numero_pedido} está listo para entregar");
        }
    }

    /**
     * Ver detalles del pedido
     */
    public function verDetalles($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with(['user', 'detalles.producto', 'transaccion'])
            ->findOrFail($pedidoId);
        $this->showDetailModal = true;
    }

    /**
     * Cerrar modal de detalles
     */
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->pedidoSeleccionado = null;
    }

    public function render()
    {
        return view('livewire.cocina.cocina', [
            'pedidosPendientes' => $this->pedidosPendientes,
            'pedidosEnPreparacion' => $this->pedidosEnPreparacion,
            'pedidosListos' => $this->pedidosListos,
            'stats' => $this->stats,
        ]);
    }
}

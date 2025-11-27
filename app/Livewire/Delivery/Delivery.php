<?php

namespace App\Livewire\Delivery;

use App\Models\Pedido;
use App\Models\User;
use App\Notifications\PedidoEnCaminoNotification;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.dashboard-layout')]
class Delivery extends Component
{
    use LogsActivity;
    public $vistaActiva = 'disponibles';
    public $pedidoSeleccionado = null;
    public $mostrarDetalles = false;
    public $deliverySeleccionado = null;

    public function mount()
    {
        // Verificar que el usuario esté autenticado y tenga rol adecuado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'delivery'])) {
            abort(403, 'No tienes permisos para acceder a este módulo.');
        }
    }

    /**
     * Escuchar eventos de WebSocket para actualizar en tiempo real
     */
    protected function getListeners()
    {
        return [
            "echo:delivery,.pedido.cambio-estado" => '$refresh',
        ];
    }

    /**
     * Refrescar la vista cuando hay actualizaciones de pedidos
     */
    public function refreshPedidos()
    {
        $this->reset(['pedidoSeleccionado', 'mostrarDetalles', 'deliverySeleccionado']);
    }

    /**
     * Cambiar entre tabs
     */
    public function cambiarVista($vista)
    {
        $this->vistaActiva = $vista;
        $this->reset(['pedidoSeleccionado', 'mostrarDetalles']);
    }

    /**
     * Delivery toma un pedido disponible
     */
    public function tomarPedido($pedidoId)
    {
        $user = Auth::user();

        if ($user->role !== 'delivery') {
            session()->flash('error', 'Solo los delivery pueden tomar pedidos.');
            return;
        }

        try {
            DB::beginTransaction();

            $pedido = Pedido::where('id', $pedidoId)
                ->where('estado', 'listo')
                ->whereNull('delivery_id')
                ->lockForUpdate()
                ->first();

            if (!$pedido) {
                session()->flash('error', 'Este pedido ya no está disponible.');
                DB::rollBack();
                return;
            }

            $estadoAnterior = $pedido->estado;
            $pedido->update([
                'delivery_id' => $user->id,
                'estado' => 'en_camino'
            ]);

            DB::commit();

            // Cargar relaciones necesarias para la notificación
            $pedido->refresh();
            $pedido->load(['user', 'delivery']);

            // Registrar actividad
            self::logActivity(
                'pedido.asignado_delivery',
                "Tomó el pedido {$pedido->numero_pedido} para entrega",
                $pedido,
                ['estado' => $estadoAnterior, 'delivery_id' => null],
                ['estado' => 'en_camino', 'delivery_id' => $user->id]
            );

            // Enviar notificación al cliente de forma asíncrona
            // NOTA: Para que esto funcione correctamente, QUEUE_CONNECTION debe estar
            // configurado como 'database' (o 'redis') en el archivo .env, NO como 'sync'.
            // Si está en 'sync', la notificación se ejecutará de forma síncrona y bloqueará la respuesta.
            if ($pedido->user) {
                $user = $pedido->user;
                $pedidoData = $pedido;
                dispatch(function () use ($user, $pedidoData) {
                    $user->notify(new PedidoEnCaminoNotification($pedidoData));
                });
            }

            session()->flash('message', 'Pedido asignado exitosamente. ¡Buena entrega!');
            $this->vistaActiva = 'mis_entregas';

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al tomar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Admin asigna manualmente un delivery a un pedido
     */
    public function asignarDelivery($pedidoId)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            session()->flash('error', 'Solo los administradores pueden asignar delivery.');
            return;
        }

        if (!$this->deliverySeleccionado) {
            session()->flash('error', 'Por favor selecciona un delivery.');
            return;
        }

        try {
            $pedido = Pedido::where('id', $pedidoId)
                ->where('estado', 'listo')
                ->first();

            if (!$pedido) {
                session()->flash('error', 'Pedido no encontrado o no está listo para delivery.');
                return;
            }

            $estadoAnterior = $pedido->estado;
            $deliveryAnterior = $pedido->delivery_id;
            $pedido->update([
                'delivery_id' => $this->deliverySeleccionado,
                'estado' => 'en_camino'
            ]);

            // Cargar relaciones necesarias para la notificación
            $pedido->refresh();
            $pedido->load(['user', 'delivery']);

            // Registrar actividad
            $deliveryAsignado = User::find($this->deliverySeleccionado);
            self::logActivity(
                'pedido.asignado_delivery',
                "Asignó el pedido {$pedido->numero_pedido} al delivery {$deliveryAsignado->name}",
                $pedido,
                ['estado' => $estadoAnterior, 'delivery_id' => $deliveryAnterior],
                ['estado' => 'en_camino', 'delivery_id' => $this->deliverySeleccionado]
            );

            // Enviar notificación al cliente de forma asíncrona
            // NOTA: Para que esto funcione correctamente, QUEUE_CONNECTION debe estar
            // configurado como 'database' (o 'redis') en el archivo .env, NO como 'sync'.
            // Si está en 'sync', la notificación se ejecutará de forma síncrona y bloqueará la respuesta.
            if ($pedido->user) {
                $user = $pedido->user;
                $pedidoData = $pedido;
                dispatch(function () use ($user, $pedidoData) {
                    $user->notify(new PedidoEnCaminoNotification($pedidoData));
                });
            }

            session()->flash('message', 'Delivery asignado exitosamente.');
            $this->deliverySeleccionado = null;

        } catch (\Exception $e) {
            session()->flash('error', 'Error al asignar delivery: ' . $e->getMessage());
        }
    }

    /**
     * Marcar pedido como entregado
     */
    public function marcarComoEntregado($pedidoId)
    {
        $user = Auth::user();

        try {
            $pedido = Pedido::where('id', $pedidoId)
                ->where('estado', 'en_camino')
                ->first();

            if (!$pedido) {
                session()->flash('error', 'Pedido no encontrado o no está en camino.');
                return;
            }

            // Si es delivery, verificar que el pedido esté asignado a él
            if ($user->role === 'delivery' && $pedido->delivery_id !== $user->id) {
                session()->flash('error', 'No tienes permiso para marcar este pedido como entregado.');
                return;
            }

            $estadoAnterior = $pedido->estado;
            $pedido->update(['estado' => 'entregado']);

            // Registrar actividad
            self::logActivity(
                'pedido.estado_cambiado',
                "Marcó como entregado el pedido {$pedido->numero_pedido}",
                $pedido,
                ['estado' => $estadoAnterior],
                ['estado' => 'entregado']
            );

            session()->flash('message', 'Pedido marcado como entregado exitosamente.');
            $this->cerrarDetalles();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al marcar pedido como entregado: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalles del pedido
     */
    public function verDetalles($pedidoId)
    {
        $pedido = Pedido::with(['detalles.producto', 'user', 'delivery'])
            ->findOrFail($pedidoId);

        $this->pedidoSeleccionado = $pedido;
        $this->mostrarDetalles = true;
    }

    /**
     * Cerrar modal de detalles
     */
    public function cerrarDetalles()
    {
        $this->mostrarDetalles = false;
        $this->pedidoSeleccionado = null;
    }

    /**
     * Pedidos disponibles para tomar (estado listo, sin delivery)
     */
    public function getPedidosDisponiblesProperty()
    {
        return Pedido::with(['detalles.producto', 'user'])
            ->listo()
            ->whereNull('delivery_id')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Pedidos asignados al delivery actual (solo para delivery)
     */
    public function getMisPedidosProperty()
    {
        $user = Auth::user();

        if ($user->role !== 'delivery') {
            return collect();
        }

        return Pedido::with(['detalles.producto', 'user'])
            ->where('delivery_id', $user->id)
            ->enCamino()
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Todos los pedidos en camino con delivery asignado (solo para admin)
     */
    public function getPedidosEnCaminoProperty()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return collect();
        }

        return Pedido::with(['detalles.producto', 'user', 'delivery'])
            ->enCamino()
            ->whereNotNull('delivery_id')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Lista de usuarios con rol delivery (solo para admin)
     */
    public function getDeliverysDisponiblesProperty()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return collect();
        }

        return User::where('role', 'delivery')->get();
    }

    /**
     * Estadísticas
     */
    public function getTotalDisponiblesProperty()
    {
        return $this->pedidosDisponibles->count();
    }

    public function getTotalMisPedidosProperty()
    {
        return $this->misPedidos->count();
    }

    public function getTotalEnCaminoProperty()
    {
        return $this->pedidosEnCamino->count();
    }

    public function getEntregasHoyProperty()
    {
        $user = Auth::user();

        if ($user->role === 'delivery') {
            return Pedido::where('delivery_id', $user->id)
                ->where('estado', 'entregado')
                ->whereDate('updated_at', today())
                ->count();
        }

        // Para admin: total de entregas hoy
        return Pedido::where('estado', 'entregado')
            ->whereDate('updated_at', today())
            ->count();
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.delivery.delivery', [
            'pedidosDisponibles' => $this->pedidosDisponibles,
            'misPedidos' => $this->misPedidos,
            'pedidosEnCamino' => $this->pedidosEnCamino,
            'deliverysDisponibles' => $this->deliverysDisponibles,
            'totalDisponibles' => $this->totalDisponibles,
            'totalMisPedidos' => $this->totalMisPedidos,
            'totalEnCamino' => $this->totalEnCamino,
            'entregasHoy' => $this->entregasHoy,
            'isAdmin' => $user->role === 'admin',
            'isDelivery' => $user->role === 'delivery',
        ]);
    }
}

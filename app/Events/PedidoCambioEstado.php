<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoCambioEstado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * El pedido que cambió de estado
     */
    public Pedido $pedido;

    /**
     * Estado anterior del pedido
     */
    public string $estadoAnterior;

    /**
     * Estado nuevo del pedido
     */
    public string $estadoNuevo;

    /**
     * Create a new event instance.
     */
    public function __construct(Pedido $pedido, string $estadoAnterior, string $estadoNuevo)
    {
        $this->pedido = $pedido;
        $this->estadoAnterior = $estadoAnterior;
        $this->estadoNuevo = $estadoNuevo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            // Canal privado para el cliente dueño del pedido
            new PrivateChannel('pedidos.' . $this->pedido->id),

            // Canal público para administración
            new Channel('admin'),
        ];

        // Agregar canales específicos según el estado
        if (in_array($this->estadoNuevo, ['en_preparacion', 'listo'])) {
            $channels[] = new Channel('cocina');
        }

        if (in_array($this->estadoNuevo, ['listo', 'en_camino', 'entregado'])) {
            $channels[] = new Channel('delivery');
        }

        return $channels;
    }

    /**
     * Nombre del evento para el cliente
     */
    public function broadcastAs(): string
    {
        return 'pedido.cambio-estado';
    }

    /**
     * Datos que se enviarán con el evento
     */
    public function broadcastWith(): array
    {
        $mensajes = [
            'pendiente' => 'Tu pedido está pendiente de confirmación',
            'en_preparacion' => 'Tu pedido está siendo preparado en cocina',
            'listo' => 'Tu pedido está listo para ser entregado',
            'en_camino' => 'Tu pedido está en camino',
            'entregado' => 'Tu pedido ha sido entregado',
            'cancelado' => 'Tu pedido ha sido cancelado',
        ];

        return [
            'pedido' => [
                'id' => $this->pedido->id,
                'numero_pedido' => $this->pedido->numero_pedido,
                'estado_anterior' => $this->estadoAnterior,
                'estado_nuevo' => $this->estadoNuevo,
                'estado_texto' => $this->pedido->estado_texto,
                'total' => $this->pedido->total,
                'user' => [
                    'id' => $this->pedido->user->id,
                    'name' => $this->pedido->user->name,
                ],
            ],
            'mensaje' => $mensajes[$this->estadoNuevo] ?? "Tu pedido cambió a: {$this->estadoNuevo}",
            'tipo' => $this->getTipoNotificacion(),
        ];
    }

    /**
     * Determina el tipo de notificación según el estado
     */
    private function getTipoNotificacion(): string
    {
        return match($this->estadoNuevo) {
            'cancelado' => 'error',
            'entregado' => 'success',
            'en_preparacion', 'listo', 'en_camino' => 'info',
            default => 'info'
        };
    }
}

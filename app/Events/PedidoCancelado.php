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

class PedidoCancelado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * El pedido que fue cancelado
     */
    public Pedido $pedido;

    /**
     * Motivo de la cancelación (opcional)
     */
    public ?string $motivo;

    /**
     * Create a new event instance.
     */
    public function __construct(Pedido $pedido, ?string $motivo = null)
    {
        $this->pedido = $pedido;
        $this->motivo = $motivo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // Canal privado para el cliente dueño del pedido
            new PrivateChannel('pedidos.' . $this->pedido->id),

            // Canal público para cocina (para quitar de la lista)
            new Channel('cocina'),

            // Canal público para administración
            new Channel('admin'),

            // Canal público para delivery (por si ya estaba asignado)
            new Channel('delivery'),
        ];
    }

    /**
     * Nombre del evento para el cliente
     */
    public function broadcastAs(): string
    {
        return 'pedido.cancelado';
    }

    /**
     * Datos que se enviarán con el evento
     */
    public function broadcastWith(): array
    {
        return [
            'pedido' => [
                'id' => $this->pedido->id,
                'numero_pedido' => $this->pedido->numero_pedido,
                'estado' => 'cancelado',
                'total' => $this->pedido->total,
                'user' => [
                    'id' => $this->pedido->user->id,
                    'name' => $this->pedido->user->name,
                ],
            ],
            'motivo' => $this->motivo ?? 'El pedido ha sido cancelado',
            'mensaje' => "El pedido {$this->pedido->numero_pedido} ha sido cancelado",
            'tipo' => 'error',
        ];
    }
}

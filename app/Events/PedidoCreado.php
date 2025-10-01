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

class PedidoCreado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * El pedido que fue creado
     */
    public Pedido $pedido;

    /**
     * Create a new event instance.
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
        
        \Log::info('PedidoCreado Event: Constructor ejecutado', [
            'pedido_id' => $pedido->id,
            'numero_pedido' => $pedido->numero_pedido,
            'canales' => ['pedidos.' . $pedido->id, 'cocina', 'admin'],
        ]);
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

            // Canal público para cocina
            new Channel('cocina'),

            // Canal público para administración
            new Channel('admin'),
        ];
    }

    /**
     * Nombre del evento para el cliente
     */
    public function broadcastAs(): string
    {
        return 'pedido.creado';
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
                'estado' => $this->pedido->estado,
                'estado_pago' => $this->pedido->estado_pago,
                'total' => $this->pedido->total,
                'items_count' => $this->pedido->detalles->count(),
                'user' => [
                    'id' => $this->pedido->user->id,
                    'name' => $this->pedido->user->name,
                ],
                'detalles' => $this->pedido->detalles->map(function ($detalle) {
                    return [
                        'cantidad' => $detalle->cantidad,
                        'producto' => [
                            'id' => $detalle->producto->id,
                            'nombre' => $detalle->producto->nombre,
                        ],
                        'subtotal' => $detalle->subtotal,
                    ];
                }),
                'created_at' => $this->pedido->created_at->toIso8601String(),
            ],
            'mensaje' => "Nuevo pedido {$this->pedido->numero_pedido} recibido",
        ];
    }
}

<?php

namespace App\Observers;

use App\Events\PedidoCreado;
use App\Events\PedidoCambioEstado;
use App\Events\PedidoCancelado;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;

class PedidoObserver
{
    /**
     * Handle the Pedido "created" event.
     * Dispara cuando se crea un nuevo pedido
     */
    public function created(Pedido $pedido): void
    {
        // Solo disparar el evento si el pedido está pagado
        // (evita disparar para pedidos creados pero con pago pendiente)
        if ($pedido->estado_pago === 'pagado') {
            Log::info('PedidoObserver: Disparando evento PedidoCreado', [
                'pedido_id' => $pedido->id,
                'numero_pedido' => $pedido->numero_pedido,
            ]);

            event(new PedidoCreado($pedido));
        }
    }

    /**
     * Handle the Pedido "updated" event.
     * Dispara cuando se actualiza un pedido
     */
    public function updated(Pedido $pedido): void
    {
        // Verificar si cambió el estado
        if ($pedido->isDirty('estado')) {
            $estadoAnterior = $pedido->getOriginal('estado');
            $estadoNuevo = $pedido->estado;

            Log::info('PedidoObserver: Cambio de estado detectado', [
                'pedido_id' => $pedido->id,
                'numero_pedido' => $pedido->numero_pedido,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
            ]);

            // Si cambió a cancelado, disparar evento específico
            if ($estadoNuevo === 'cancelado') {
                event(new PedidoCancelado($pedido));
            } else {
                // Para cualquier otro cambio de estado
                event(new PedidoCambioEstado($pedido, $estadoAnterior, $estadoNuevo));
            }
        }

        // Si cambió de pago pendiente a pagado, disparar evento de creación
        if ($pedido->isDirty('estado_pago') && $pedido->estado_pago === 'pagado') {
            $estadoPagoAnterior = $pedido->getOriginal('estado_pago');

            if ($estadoPagoAnterior === 'pendiente') {
                Log::info('PedidoObserver: Pago confirmado, disparando PedidoCreado', [
                    'pedido_id' => $pedido->id,
                    'numero_pedido' => $pedido->numero_pedido,
                ]);

                event(new PedidoCreado($pedido));
            }
        }
    }

    /**
     * Handle the Pedido "deleted" event.
     */
    public function deleted(Pedido $pedido): void
    {
        // Opcional: disparar evento si se elimina un pedido
        Log::info('PedidoObserver: Pedido eliminado (soft delete)', [
            'pedido_id' => $pedido->id,
            'numero_pedido' => $pedido->numero_pedido,
        ]);
    }

    /**
     * Handle the Pedido "restored" event.
     */
    public function restored(Pedido $pedido): void
    {
        Log::info('PedidoObserver: Pedido restaurado', [
            'pedido_id' => $pedido->id,
            'numero_pedido' => $pedido->numero_pedido,
        ]);
    }

    /**
     * Handle the Pedido "force deleted" event.
     */
    public function forceDeleted(Pedido $pedido): void
    {
        Log::info('PedidoObserver: Pedido eliminado permanentemente', [
            'pedido_id' => $pedido->id,
            'numero_pedido' => $pedido->numero_pedido,
        ]);
    }
}

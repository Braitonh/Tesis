<?php

namespace App\Livewire\Cliente;

use App\Models\Transaccion;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cliente')]
class ProcesoPago extends Component
{
    public $transaccion_id;
    public $estado = 'procesando';
    public $mensaje_error = '';

    public function mount($transaccionId)
    {
        $this->transaccion_id = $transaccionId;
        $this->verificarEstado();
    }

    public function verificarEstado()
    {
        $transaccion = Transaccion::find($this->transaccion_id);

        if (!$transaccion) {
            $this->estado = 'error';
            $this->mensaje_error = 'Transacción no encontrada';
            return;
        }

        $this->estado = $transaccion->estado;

        if ($transaccion->isRechazada()) {
            $this->mensaje_error = $transaccion->mensaje_respuesta ?? 'Pago rechazado';
        }
    }

    public function render()
    {
        $transaccion = Transaccion::with('pedido')->find($this->transaccion_id);

        return view('livewire.cliente.proceso-pago', [
            'transaccion' => $transaccion,
        ]);
    }
}

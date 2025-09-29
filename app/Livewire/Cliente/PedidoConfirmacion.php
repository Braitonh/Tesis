<?php

namespace App\Livewire\Cliente;

use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cliente')]
class PedidoConfirmacion extends Component
{
    public Pedido $pedido;

    public function mount(Pedido $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        $this->pedido = $pedido->load(['detalles.producto', 'user']);
    }

    public function render()
    {
        return view('livewire.cliente.pedido-confirmacion');
    }
}
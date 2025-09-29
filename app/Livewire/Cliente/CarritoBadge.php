<?php

namespace App\Livewire\Cliente;

use App\Traits\HasShoppingCart;
use Livewire\Component;

class CarritoBadge extends Component
{
    use HasShoppingCart;

    protected $listeners = ['carrito-actualizado' => '$refresh'];

    public function getCountProperty()
    {
        return $this->getCartCount();
    }

    public function abrirCarrito()
    {
        $this->dispatch('abrir-carrito');
    }

    public function render()
    {
        return view('livewire.cliente.carrito-badge', [
            'count' => $this->count,
        ]);
    }
}
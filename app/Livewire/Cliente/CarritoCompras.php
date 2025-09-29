<?php

namespace App\Livewire\Cliente;

use App\Traits\HasShoppingCart;
use Livewire\Attributes\On;
use Livewire\Component;

class CarritoCompras extends Component
{
    use HasShoppingCart;

    public $showModal = false;
    public $showConfirmClear = false;

    protected $listeners = ['carrito-actualizado' => '$refresh'];

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }

    #[On('abrir-carrito')]
    public function abrirCarrito()
    {
        $this->showModal = true;
    }

    public function increment($productoId)
    {
        $this->incrementCartItem($productoId);
        $this->dispatch('carrito-actualizado');
    }

    public function decrement($productoId)
    {
        $this->decrementCartItem($productoId);
        $this->dispatch('carrito-actualizado');
    }

    public function remove($productoId)
    {
        $this->removeFromCart($productoId);
        $this->dispatch('carrito-actualizado');
        session()->flash('message', 'Producto eliminado del carrito');
    }

    public function confirmClear()
    {
        $this->showConfirmClear = true;
    }

    public function cancelClear()
    {
        $this->showConfirmClear = false;
    }

    public function clear()
    {
        $this->clearCart();
        $this->showConfirmClear = false;
        $this->dispatch('carrito-actualizado');
        session()->flash('message', 'Carrito vaciado correctamente');
    }

    public function getItemsProperty()
    {
        return $this->getCartItems();
    }

    public function getCountProperty()
    {
        return $this->getCartCount();
    }

    public function getTotalProperty()
    {
        return $this->getCartTotal();
    }

    public function render()
    {
        return view('livewire.cliente.carrito-compras', [
            'items' => $this->items,
            'count' => $this->count,
            'total' => $this->total,
        ]);
    }
}
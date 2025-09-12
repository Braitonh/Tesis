<?php

namespace App\Livewire\Cliente;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cliente')]
class ClienteBienvenida extends Component
{
    public $usuario;

    public function mount()
    {
        $this->usuario = Auth::user();
    }

    public function render()
    {
        return view('livewire.cliente.cliente-bienvenida');
    }
}

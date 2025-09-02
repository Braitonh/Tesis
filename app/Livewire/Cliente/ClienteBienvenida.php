<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

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

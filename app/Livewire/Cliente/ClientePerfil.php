<?php

namespace App\Livewire\Cliente;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cliente')]
class ClientePerfil extends Component
{
    public $name = '';
    public $email = '';
    public $dni = '';
    public $direccion = '';
    public $telefono = '';

    protected function rules()
    {
        $userId = Auth::id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|unique:users,email,'.$userId,
            'dni' => 'nullable|string|regex:/^\d{8}$/|unique:users,dni,'.$userId,
            'direccion' => 'nullable|string|max:500',
            'telefono' => 'nullable|string|max:20',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email debe ser válido.',
        'email.unique' => 'Este email ya está en uso.',
        'dni.regex' => 'El DNI debe tener exactamente 8 dígitos numéricos.',
        'dni.unique' => 'Este DNI ya está en uso.',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->dni = $user->dni ?? '';
        $this->direccion = $user->direccion ?? '';
        $this->telefono = $user->telefono ?? '';
    }

    public function actualizarPerfil()
    {
        $this->validate();

        $user = Auth::user();
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
        ]);

        session()->flash('message', 'Perfil actualizado correctamente.');
        
        return redirect()->route('cliente.bienvenida');
    }

    public function render()
    {
        return view('livewire.cliente.cliente-perfil');
    }
}


<?php

namespace App\Livewire\Empleados;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.dashboard-layout')]
class EmpleadoPerfil extends Component
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
            'dni' => 'required|string|regex:/^\d{8}$/|unique:users,dni,'.$userId,
            'direccion' => 'required|string|max:500',
            'telefono' => 'required|string|max:20',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email debe ser válido.',
        'email.unique' => 'Este email ya está en uso.',
        'dni.required' => 'El DNI es obligatorio.',
        'dni.regex' => 'El DNI debe tener exactamente 8 dígitos numéricos.',
        'dni.unique' => 'Este DNI ya está en uso.',
        'direccion.required' => 'La dirección es obligatoria.',
        'telefono.required' => 'El teléfono es obligatorio.',
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
        
        // Convert empty strings to null for nullable fields as a best practice
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'direccion' => trim($this->direccion) === '' ? null : $this->direccion,
            'telefono' => trim($this->telefono) === '' ? null : $this->telefono,
        ]);

        session()->flash('message', 'Perfil actualizado correctamente.');
        
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.empleados.empleado-perfil');
    }
}






<?php

namespace App\Livewire\Empleados;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.dashboard-layout')]
class Empleados extends Component
{
    use WithPagination;

    public $showModal = false;
    public $modalTitle = '';
    public $empleadoId = null;
    
    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'empleado';
    
    // Filters
    public $search = '';
    public $roleFilter = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'role' => 'required|in:admin,empleado,cocina,delivery,ventas'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function createEmpleado()
    {
        $this->reset(['empleadoId', 'name', 'email', 'password', 'role']);
        $this->modalTitle = 'Crear Nuevo Empleado';
        $this->showModal = true;
    }

    public function editEmpleado($id)
    {
        $empleado = User::findOrFail($id);
        $this->empleadoId = $id;
        $this->name = $empleado->name;
        $this->email = $empleado->email;
        $this->password = '';
        $this->role = $empleado->role ?? 'empleado';
        $this->modalTitle = 'Editar Empleado';
        $this->showModal = true;

        // Remove unique validation for edit
        $this->rules['email'] = 'required|email|unique:users,email,' . $id;
        $this->rules['password'] = 'nullable|string|min:8';
    }

    public function saveEmpleado()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->empleadoId) {
            // Update
            User::findOrFail($this->empleadoId)->update($data);
            session()->flash('message', 'Empleado actualizado correctamente.');
        } else {
            // Create
            $data['password'] = bcrypt($this->password);
            User::create($data);
            session()->flash('message', 'Empleado creado correctamente.');
        }

        $this->closeModal();
    }

    public function deleteEmpleado($id)
    {
        $empleado = User::findOrFail($id);
        
        // Prevent deletion of current user
        if ($empleado->id === Auth::user()->id) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            return;
        }

        $empleado->delete();
        session()->flash('message', 'Empleado eliminado correctamente.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['empleadoId', 'name', 'email', 'password', 'role']);
        $this->resetValidation();
    }

    public function render()
    {
        $empleados = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.empleados.empleados', compact('empleados'));
    }
}

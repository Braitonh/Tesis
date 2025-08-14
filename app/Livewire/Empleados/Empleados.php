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
    
    // Delete confirmation modal
    public $showDeleteModal = false;
    public $empleadoToDelete = null;
    
    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'empleado';
    public $dni = '';
    public $direccion = '';
    public $telefono = '';
    
    // Filters
    public $search = '';
    public $roleFilter = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,empleado,cocina,delivery,ventas',
        'dni' => 'required|string|max:20|unique:users,dni',
        'direccion' => 'required|string|max:500',
        'telefono' => 'required|string|max:20',

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
        // Simulate loading time
        usleep(800000); // 0.8 seconds
        
        $this->reset(['empleadoId', 'name', 'email', 'password', 'role', 'dni', 'direccion', 'telefono']);
        $this->modalTitle = 'Crear Nuevo Empleado';
        $this->showModal = true;
    }

    public function editEmpleado($id)
    {
        // Simulate loading time
        usleep(1000000); // 1 second
        
        $empleado = User::findOrFail($id);
        $this->empleadoId = $id;
        $this->name = $empleado->name;
        $this->email = $empleado->email;
        $this->password = '';
        $this->role = $empleado->role ?? 'empleado';
        $this->dni = $empleado->dni ?? '';
        $this->direccion = $empleado->direccion ?? '';
        $this->telefono = $empleado->telefono ?? '';
        $this->modalTitle = 'Editar Empleado';
        $this->showModal = true;
    }

    public function saveEmpleado()
    {     
        // Simulate loading time
        usleep(1500000); // 1.5 seconds
        
        // Define validation rules based on create or edit
        if ($this->empleadoId) {
            // Edit - exclude current user from unique validation
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->empleadoId,
                'password' => 'nullable|string|min:8',
                'role' => 'required|in:admin,empleado,cocina,delivery,ventas',
                'dni' => 'required|string|max:20|unique:users,dni,' . $this->empleadoId,
                'direccion' => 'required|string|max:500',
                'telefono' => 'required|string|max:20',
            ];
        } else {
            // Create - strict unique validation
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,empleado,cocina,delivery,ventas',
                'dni' => 'required|string|max:20|unique:users,dni',
                'direccion' => 'required|string|max:500',
                'telefono' => 'required|string|max:20',
            ];
        }

        $this->validate($rules);
        
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'dni' => $this->dni,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
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

    public function confirmDeleteEmpleado($id)
    {
        // Simulate loading time
        usleep(600000); // 0.6 seconds
        
        $empleado = User::findOrFail($id);
        
        // Prevent deletion of current user
        if ($empleado->id === Auth::user()->id) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            return;
        }

        $this->empleadoToDelete = $empleado;
        $this->showDeleteModal = true;
    }

    public function deleteEmpleado()
    {
        // Simulate loading time
        usleep(1200000); // 1.2 seconds
        
        if ($this->empleadoToDelete) {
            $this->empleadoToDelete->delete();
            session()->flash('message', 'Empleado eliminado correctamente.');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->empleadoToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['empleadoId', 'name', 'email', 'password', 'role', 'dni', 'direccion', 'telefono']);
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

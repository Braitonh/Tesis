<?php

namespace App\Livewire\Dashboard;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.dashboard-layout')]
class AdminRegistros extends Component
{
    use WithPagination;

    // Filtros
    public $filtroEmpleado = '';
    public $filtroAccion = '';
    public $filtroFecha = '';
    public $filtroModelo = '';
    public $busqueda = '';

    // Modal de detalles
    public $showDetailModal = false;
    public $registroSeleccionado;

    // Query string
    protected $updatesQueryString = [
        'busqueda' => ['except' => '', 'as' => 'q'],
        'filtroEmpleado' => ['except' => '', 'as' => 'empleado'],
        'filtroAccion' => ['except' => '', 'as' => 'accion'],
        'filtroFecha' => ['except' => '', 'as' => 'fecha'],
        'filtroModelo' => ['except' => '', 'as' => 'modelo'],
    ];

    public function mount()
    {
        $this->filtroEmpleado = '';
        $this->filtroAccion = '';
        $this->filtroFecha = '';
        $this->filtroModelo = '';
        $this->busqueda = '';
    }

    public function limpiarFiltros()
    {
        $this->filtroEmpleado = '';
        $this->filtroAccion = '';
        $this->filtroFecha = '';
        $this->filtroModelo = '';
        $this->busqueda = '';
        $this->resetPage();
    }

    public function updatedFiltroEmpleado()
    {
        $this->resetPage();
    }

    public function updatedFiltroAccion()
    {
        $this->resetPage();
    }

    public function updatedFiltroFecha()
    {
        $this->resetPage();
    }

    public function updatedFiltroModelo()
    {
        $this->resetPage();
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }

    public function verDetalles($registroId)
    {
        $this->registroSeleccionado = ActivityLog::with(['user', 'model'])->findOrFail($registroId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->registroSeleccionado = null;
    }

    public function getRegistros()
    {
        $query = ActivityLog::with(['user'])
            ->when($this->filtroEmpleado, function ($query) {
                $query->where('user_id', $this->filtroEmpleado);
            })
            ->when($this->filtroAccion, function ($query) {
                $query->where('action', $this->filtroAccion);
            })
            ->when($this->filtroFecha, function ($query) {
                $query->whereDate('created_at', $this->filtroFecha);
            })
            ->when($this->filtroModelo, function ($query) {
                $query->where('model_type', $this->filtroModelo);
            })
            ->when($this->busqueda, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->busqueda . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->busqueda . '%')
                                    ->orWhere('email', 'like', '%' . $this->busqueda . '%');
                      });
                });
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate(15);
    }

    public function getEmpleadosProperty()
    {
        return User::whereIn('role', ['admin', 'empleado', 'cocina', 'delivery', 'ventas'])
            ->orderBy('name')
            ->get();
    }

    public function getAccionesProperty()
    {
        return [
            'pedido.estado_cambiado' => 'Cambio de Estado',
            'pedido.asignado_delivery' => 'Asignación de Delivery',
            'pedido.actualizado' => 'Actualización de Pedido',
            'pedido.cancelado' => 'Cancelación de Pedido',
        ];
    }

    public function getModelosProperty()
    {
        return [
            'App\Models\Pedido' => 'Pedido',
        ];
    }

    public function getStatsProperty()
    {
        return [
            'total' => ActivityLog::count(),
            'hoy' => ActivityLog::whereDate('created_at', today())->count(),
            'esta_semana' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'este_mes' => ActivityLog::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.admin-registros', [
            'registros' => $this->getRegistros(),
            'empleados' => $this->empleados,
            'acciones' => $this->acciones,
            'modelos' => $this->modelos,
            'stats' => $this->stats,
        ]);
    }
}


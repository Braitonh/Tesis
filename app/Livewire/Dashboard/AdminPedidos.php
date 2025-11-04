<?php

namespace App\Livewire\Dashboard;

use App\Models\Pedido;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.dashboard-layout')]
class AdminPedidos extends Component
{
    use WithPagination;

    // Filtros
    public $filtroEstado = '';
    public $filtroEstadoPago = '';
    public $filtroFecha = '';
    public $busquedaCliente = '';

    // Modales
    public $showDetailModal = false;
    public $showEstadoModal = false;
    public $showDeleteModal = false;

    // Datos del pedido seleccionado
    public $pedidoSeleccionado;
    public $nuevoEstado = '';
    public $nuevaDireccion = '';
    public $nuevoTelefono = '';
    public $nuevasNotas = '';

    // Query string
    protected $updatesQueryString = [
        'busquedaCliente' => ['except' => '', 'as' => 'cliente'],
        'filtroEstado' => ['except' => '', 'as' => 'estado'],
        'filtroEstadoPago' => ['except' => '', 'as' => 'pago'],
        'filtroFecha' => ['except' => '', 'as' => 'fecha']
    ];

    public function mount()
    {
        $this->filtroEstado = '';
        $this->filtroEstadoPago = '';
        $this->filtroFecha = '';
        $this->busquedaCliente = '';
    }

    /**
     * Escuchar eventos de WebSocket para actualizar en tiempo real
     */
    protected function getListeners()
    {
        return [
            "echo:admin,.pedido.creado" => '$refresh',
            "echo:admin,.pedido.cambio-estado" => '$refresh',
            "echo:admin,.pedido.cancelado" => '$refresh',
        ];
    }

    public function limpiarFiltros()
    {
        $this->filtroEstado = '';
        $this->filtroEstadoPago = '';
        $this->filtroFecha = '';
        $this->busquedaCliente = '';
        $this->resetPage();
    }

    public function updatedFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatedFiltroEstadoPago()
    {
        $this->resetPage();
    }

    public function updatedFiltroFecha()
    {
        $this->resetPage();
    }

    public function updatedBusquedaCliente()
    {
        $this->resetPage();
    }

    public function verDetalles($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with(['user', 'detalles.producto', 'transaccion'])
            ->findOrFail($pedidoId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->pedidoSeleccionado = null;
    }

    public function abrirModalEstado($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with(['user', 'detalles'])
            ->findOrFail($pedidoId);
        $this->nuevoEstado = $this->pedidoSeleccionado->estado;
        $this->nuevaDireccion = $this->pedidoSeleccionado->direccion_entrega;
        $this->nuevoTelefono = $this->pedidoSeleccionado->telefono_contacto;
        $this->nuevasNotas = $this->pedidoSeleccionado->notas ?? '';
        $this->showEstadoModal = true;
    }

    public function actualizarEstado()
    {
        $this->validate([
            'nuevoEstado' => 'required|in:pendiente,en_preparacion,listo,en_camino,entregado,cancelado'
        ]);

        if ($this->pedidoSeleccionado) {
            $this->pedidoSeleccionado->update([
                'estado' => $this->nuevoEstado
            ]);

            session()->flash('message', 'Estado del pedido actualizado correctamente');
            $this->closeEstadoModal();
        }
    }

    public function actualizarPedido()
    {
        // Determinar qué campos son editables según el estado actual
        $estadosOrden = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado'];
        $estadoActualIndex = array_search($this->pedidoSeleccionado->estado, $estadosOrden);
        $estadoListoIndex = array_search('listo', $estadosOrden);
        $estadoPreparacionIndex = array_search('en_preparacion', $estadosOrden);
        
        $puedeEditarDireccion = $estadoActualIndex !== false && $estadoActualIndex <= $estadoListoIndex;
        $puedeEditarNotas = $estadoActualIndex !== false && $estadoActualIndex <= $estadoPreparacionIndex;

        // Validaciones dinámicas
        $rules = [
            'nuevoEstado' => 'required|in:pendiente,en_preparacion,listo,en_camino,entregado,cancelado',
        ];

        if ($puedeEditarDireccion) {
            $rules['nuevaDireccion'] = 'required|string|min:10|max:500';
            $rules['nuevoTelefono'] = 'required|string|min:6|max:20';
        }

        if ($puedeEditarNotas) {
            $rules['nuevasNotas'] = 'nullable|string|max:1000';
        }

        $this->validate($rules, [
            'nuevoEstado.required' => 'El estado es obligatorio',
            'nuevaDireccion.required' => 'La dirección de entrega es obligatoria',
            'nuevaDireccion.min' => 'La dirección debe tener al menos 10 caracteres',
            'nuevaDireccion.max' => 'La dirección no puede exceder 500 caracteres',
            'nuevoTelefono.required' => 'El teléfono de contacto es obligatorio',
            'nuevoTelefono.min' => 'El teléfono debe tener al menos 6 caracteres',
            'nuevoTelefono.max' => 'El teléfono no puede exceder 20 caracteres',
            'nuevasNotas.max' => 'Las notas no pueden exceder 1000 caracteres',
        ]);

        if ($this->pedidoSeleccionado) {
            $datosActualizar = [
                'estado' => $this->nuevoEstado,
            ];

            // Solo actualizar campos editables
            if ($puedeEditarDireccion) {
                $datosActualizar['direccion_entrega'] = $this->nuevaDireccion;
                $datosActualizar['telefono_contacto'] = $this->nuevoTelefono;
            }

            if ($puedeEditarNotas) {
                $datosActualizar['notas'] = $this->nuevasNotas;
            }

            $this->pedidoSeleccionado->update($datosActualizar);

            session()->flash('message', 'Pedido actualizado correctamente');
            $this->closeEstadoModal();
        }
    }

    public function closeEstadoModal()
    {
        $this->showEstadoModal = false;
        $this->pedidoSeleccionado = null;
        $this->nuevoEstado = '';
        $this->nuevaDireccion = '';
        $this->nuevoTelefono = '';
        $this->nuevasNotas = '';
        $this->resetValidation();
    }

    public function confirmarCancelar($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with('user')->findOrFail($pedidoId);
        $this->showDeleteModal = true;
    }

    public function cancelarPedido()
    {
        if ($this->pedidoSeleccionado) {
            $this->pedidoSeleccionado->update([
                'estado' => 'cancelado'
            ]);

            session()->flash('message', 'Pedido cancelado correctamente');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->pedidoSeleccionado = null;
    }

    public function getPedidos()
    {
        $query = Pedido::with(['user', 'detalles'])
            ->when($this->busquedaCliente, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->busquedaCliente . '%')
                      ->orWhere('email', 'like', '%' . $this->busquedaCliente . '%');
                });
            })
            ->when($this->filtroEstado, function ($query) {
                $query->where('estado', $this->filtroEstado);
            })
            ->when($this->filtroEstadoPago, function ($query) {
                $query->where('estado_pago', $this->filtroEstadoPago);
            })
            ->when($this->filtroFecha, function ($query) {
                $query->whereDate('created_at', $this->filtroFecha);
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate(10);
    }

    public function getStatsProperty()
    {
        return [
            'total' => Pedido::count(),
            'pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'en_proceso' => Pedido::whereIn('estado', ['en_preparacion'])->count(),
            'en_delivery' => Pedido::whereIn('estado', ['en_camino'])->count(),
            'entregados' => Pedido::where('estado', 'entregado')->count(),
            'cancelados' => Pedido::where('estado', 'cancelado')->count(),
            'pagados' => Pedido::where('estado_pago', 'pagado')->count(),
            'pago_pendiente' => Pedido::where('estado_pago', 'pendiente')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.admin-pedidos', [
            'pedidos' => $this->getPedidos(),
            'stats' => $this->stats,
        ]);
    }
}
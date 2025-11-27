<?php

namespace App\Livewire\Dashboard;

use App\Models\Pedido;
use App\Models\User;
use App\Notifications\PedidoEnCaminoNotification;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.dashboard-layout')]
class AdminPedidos extends Component
{
    use WithPagination;
    use LogsActivity;

    // Filtros
    public $filtroEstado = '';
    public $filtroEstadoPago = '';
    public $filtroFecha = '';
    public $busquedaCliente = '';

    // Modales
    public $showDetailModal = false;
    public $showEstadoModal = false;
    public $showDeleteModal = false;
    public $showDeliveryModal = false;

    // Datos del pedido seleccionado
    public $pedidoSeleccionado;
    public $nuevoEstado = '';
    public $nuevaDireccion = '';
    public $nuevoTelefono = '';
    public $nuevasNotas = '';
    public $deliverySeleccionado = null;

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
        $this->pedidoSeleccionado = Pedido::with(['user', 'detalles.producto', 'detalles.promocion.productos', 'transaccion'])
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
            $estadoAnterior = $this->pedidoSeleccionado->estado;
            $this->pedidoSeleccionado->update([
                'estado' => $this->nuevoEstado
            ]);

            // Registrar actividad
            self::logActivity(
                'pedido.estado_cambiado',
                "Cambió el estado del pedido {$this->pedidoSeleccionado->numero_pedido} de {$estadoAnterior} a {$this->nuevoEstado}",
                $this->pedidoSeleccionado,
                ['estado' => $estadoAnterior],
                ['estado' => $this->nuevoEstado]
            );

            session()->flash('message', 'Estado del pedido actualizado correctamente');
            $this->closeEstadoModal();
        }
    }

    public function actualizarPedido()
    {
        $userRole = Auth::user()->role;
        $esVentas = $userRole === 'ventas';
        $esAdmin = $userRole === 'admin';
        
        // Determinar qué campos son editables según el estado actual
        // El admin siempre puede editar todo sin restricciones
        $estadosOrden = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado'];
        $estadoActualIndex = array_search($this->pedidoSeleccionado->estado, $estadosOrden);
        $estadoListoIndex = array_search('listo', $estadosOrden);
        $estadoPreparacionIndex = array_search('en_preparacion', $estadosOrden);
        
        // Si es admin, siempre puede editar todo
        $puedeEditarDireccion = $esAdmin || ($estadoActualIndex !== false && $estadoActualIndex <= $estadoListoIndex);
        $puedeEditarNotas = $esAdmin || ($estadoActualIndex !== false && $estadoActualIndex <= $estadoPreparacionIndex);

        // Validaciones dinámicas
        $rules = [];
        
        // Solo validar nuevoEstado si el usuario NO es ventas
        if (!$esVentas) {
            $rules['nuevoEstado'] = 'required|in:pendiente,en_preparacion,listo,en_camino,entregado,cancelado';
        }

        // Si es admin, siempre validar dirección y teléfono
        // Si no es admin, validar solo si puede editar
        if ($esAdmin || $puedeEditarDireccion) {
            $rules['nuevaDireccion'] = 'required|string|min:10|max:500';
            $rules['nuevoTelefono'] = 'required|string|min:6|max:20';
        }

        // Si es admin, siempre validar notas
        // Si no es admin, validar solo si puede editar
        if ($esAdmin || $puedeEditarNotas) {
            $rules['nuevasNotas'] = 'nullable|string|max:1000';
        }

        $messages = [];
        if (!$esVentas) {
            $messages['nuevoEstado.required'] = 'El estado es obligatorio';
        }
        $messages = array_merge($messages, [
            'nuevaDireccion.required' => 'La dirección de entrega es obligatoria',
            'nuevaDireccion.min' => 'La dirección debe tener al menos 10 caracteres',
            'nuevaDireccion.max' => 'La dirección no puede exceder 500 caracteres',
            'nuevoTelefono.required' => 'El teléfono de contacto es obligatorio',
            'nuevoTelefono.min' => 'El teléfono debe tener al menos 6 caracteres',
            'nuevoTelefono.max' => 'El teléfono no puede exceder 20 caracteres',
            'nuevasNotas.max' => 'Las notas no pueden exceder 1000 caracteres',
        ]);

        $this->validate($rules, $messages);

        if ($this->pedidoSeleccionado) {
            $datosActualizar = [];
            
            // Solo actualizar estado si el usuario NO es ventas
            if (!$esVentas) {
                $datosActualizar['estado'] = $this->nuevoEstado;
            }

            $oldValues = [];
            $newValues = [];
            
            // Solo registrar cambios de estado si NO es ventas
            if (!$esVentas) {
                $oldValues['estado'] = $this->pedidoSeleccionado->estado;
                $newValues['estado'] = $this->nuevoEstado;
            }

            // Actualizar campos editables
            // Si es admin, siempre actualizar dirección, teléfono y notas
            // Si no es admin, solo actualizar si puede editar según el estado
            if ($puedeEditarDireccion) {
                $oldValues['direccion_entrega'] = $this->pedidoSeleccionado->direccion_entrega;
                $oldValues['telefono_contacto'] = $this->pedidoSeleccionado->telefono_contacto;
                $newValues['direccion_entrega'] = $this->nuevaDireccion;
                $newValues['telefono_contacto'] = $this->nuevoTelefono;
                $datosActualizar['direccion_entrega'] = $this->nuevaDireccion;
                $datosActualizar['telefono_contacto'] = $this->nuevoTelefono;
            }

            if ($puedeEditarNotas) {
                $oldValues['notas'] = $this->pedidoSeleccionado->notas;
                $newValues['notas'] = $this->nuevasNotas;
                $datosActualizar['notas'] = $this->nuevasNotas;
            }

            $this->pedidoSeleccionado->update($datosActualizar);

            // Registrar actividad
            $cambios = [];
            if (!$esVentas && isset($oldValues['estado']) && isset($newValues['estado']) && $oldValues['estado'] !== $newValues['estado']) {
                $cambios[] = "estado: {$oldValues['estado']} → {$newValues['estado']}";
            }
            if (isset($oldValues['direccion_entrega']) && $oldValues['direccion_entrega'] !== $newValues['direccion_entrega']) {
                $cambios[] = "dirección actualizada";
            }
            if (isset($oldValues['telefono_contacto']) && $oldValues['telefono_contacto'] !== $newValues['telefono_contacto']) {
                $cambios[] = "teléfono actualizado";
            }
            if (isset($oldValues['notas']) && $oldValues['notas'] !== $newValues['notas']) {
                $cambios[] = "notas actualizadas";
            }

            self::logActivity(
                'pedido.actualizado',
                "Actualizó el pedido {$this->pedidoSeleccionado->numero_pedido}: " . implode(', ', $cambios),
                $this->pedidoSeleccionado,
                $oldValues,
                $newValues
            );

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
            $estadoAnterior = $this->pedidoSeleccionado->estado;
            $this->pedidoSeleccionado->update([
                'estado' => 'cancelado'
            ]);

            // Registrar actividad
            self::logActivity(
                'pedido.cancelado',
                "Canceló el pedido {$this->pedidoSeleccionado->numero_pedido}",
                $this->pedidoSeleccionado,
                ['estado' => $estadoAnterior],
                ['estado' => 'cancelado']
            );

            session()->flash('message', 'Pedido cancelado correctamente');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->pedidoSeleccionado = null;
    }

    /**
     * Avanzar al siguiente estado del pedido
     */
    public function avanzarEstado($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);
        $estadoAnterior = $pedido->estado;
        $nuevoEstado = null;
        $mensaje = '';

        switch ($pedido->estado) {
            case 'pendiente':
                $nuevoEstado = 'en_preparacion';
                $mensaje = 'Pedido movido a preparación';
                break;
            case 'en_preparacion':
                $nuevoEstado = 'listo';
                $mensaje = 'Pedido marcado como listo';
                break;
            case 'listo':
                // Para "listo" necesitamos abrir el modal de delivery
                $this->abrirModalDelivery($pedidoId);
                return;
            case 'en_camino':
                // Para "en_camino" llamamos al método de marcar como entregado
                $this->marcarComoEntregado($pedidoId);
                return;
            default:
                session()->flash('error', 'No se puede avanzar el estado desde ' . $estadoAnterior);
                return;
        }

        if ($nuevoEstado) {
            $pedido->update(['estado' => $nuevoEstado]);

            // Registrar actividad
            self::logActivity(
                'pedido.estado_cambiado',
                "Avanzó el estado del pedido {$pedido->numero_pedido} de {$estadoAnterior} a {$nuevoEstado}",
                $pedido,
                ['estado' => $estadoAnterior],
                ['estado' => $nuevoEstado]
            );

            session()->flash('message', $mensaje);
        }
    }

    /**
     * Abrir modal para seleccionar delivery
     */
    public function abrirModalDelivery($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with('user')->findOrFail($pedidoId);
        
        if ($this->pedidoSeleccionado->estado !== 'listo') {
            session()->flash('error', 'Solo se puede asignar delivery a pedidos en estado "Listo"');
            return;
        }

        $this->deliverySeleccionado = null;
        $this->showDeliveryModal = true;
    }

    /**
     * Asignar delivery y avanzar a en_camino
     */
    public function asignarDeliveryYAvanzar()
    {
        $this->validate([
            'deliverySeleccionado' => 'required|exists:users,id'
        ], [
            'deliverySeleccionado.required' => 'Debes seleccionar un delivery',
            'deliverySeleccionado.exists' => 'El delivery seleccionado no existe'
        ]);

        if (!$this->pedidoSeleccionado) {
            session()->flash('error', 'Pedido no encontrado');
            return;
        }

        $pedido = $this->pedidoSeleccionado;
        
        if ($pedido->estado !== 'listo') {
            session()->flash('error', 'Solo se puede asignar delivery a pedidos en estado "Listo"');
            $this->cerrarModalDelivery();
            return;
        }

        $delivery = User::findOrFail($this->deliverySeleccionado);
        
        if ($delivery->role !== 'delivery') {
            session()->flash('error', 'El usuario seleccionado no es un delivery');
            return;
        }

        $estadoAnterior = $pedido->estado;
        $deliveryAnterior = $pedido->delivery_id;

        $pedido->update([
            'delivery_id' => $this->deliverySeleccionado,
            'estado' => 'en_camino'
        ]);

        // Cargar relaciones para notificación si es necesario
        $pedido->refresh();
        $pedido->load(['user', 'delivery']);

        // Enviar notificación al cliente de forma asíncrona
        // NOTA: Para que esto funcione correctamente, QUEUE_CONNECTION debe estar
        // configurado como 'database' (o 'redis') en el archivo .env, NO como 'sync'.
        // Si está en 'sync', la notificación se ejecutará de forma síncrona y bloqueará la respuesta.
        if ($pedido->user) {
            $user = $pedido->user;
            $pedidoData = $pedido;
            dispatch(function () use ($user, $pedidoData) {
                $user->notify(new PedidoEnCaminoNotification($pedidoData));
            });
        }

        // Registrar actividad
        self::logActivity(
            'pedido.asignado_delivery',
            "Asignó el pedido {$pedido->numero_pedido} al delivery {$delivery->name} y cambió a en_camino",
            $pedido,
            ['estado' => $estadoAnterior, 'delivery_id' => $deliveryAnterior],
            ['estado' => 'en_camino', 'delivery_id' => $this->deliverySeleccionado]
        );

        session()->flash('message', "Delivery {$delivery->name} asignado y pedido en camino");
        $this->cerrarModalDelivery();
    }

    /**
     * Marcar pedido como entregado
     */
    public function marcarComoEntregado($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);

        if ($pedido->estado !== 'en_camino') {
            session()->flash('error', 'Solo se pueden marcar como entregados los pedidos en camino');
            return;
        }

        $estadoAnterior = $pedido->estado;
        $pedido->update(['estado' => 'entregado']);

        // Registrar actividad
        self::logActivity(
            'pedido.estado_cambiado',
            "Marcó como entregado el pedido {$pedido->numero_pedido}",
            $pedido,
            ['estado' => $estadoAnterior],
            ['estado' => 'entregado']
        );

        session()->flash('message', 'Pedido marcado como entregado exitosamente');
    }

    /**
     * Cerrar modal de delivery
     */
    public function cerrarModalDelivery()
    {
        $this->showDeliveryModal = false;
        $this->pedidoSeleccionado = null;
        $this->deliverySeleccionado = null;
        $this->resetValidation();
    }

    /**
     * Obtener lista de deliverys disponibles
     */
    public function getDeliverysDisponiblesProperty()
    {
        return User::where('role', 'delivery')->orderBy('name')->get();
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
        $userRole = Auth::user()->role ?? 'cliente';
        
        return view('livewire.dashboard.admin-pedidos', [
            'pedidos' => $this->getPedidos(),
            'stats' => $this->stats,
            'userRole' => $userRole,
        ]);
    }
}
<div class="p-6 space-y-6 bg-white/80 rounded-3xl w-full max-w-none">
    <!-- Header -->
    <x-pedidos.header />

    <!-- Stats Cards -->
    <x-pedidos.stats-grid :stats="$stats" />

    <!-- Filtros -->
    <x-pedidos.filtros
        :filtroEstado="$filtroEstado"
        :filtroEstadoPago="$filtroEstadoPago"
        :filtroFecha="$filtroFecha"
        :busquedaCliente="$busquedaCliente" />

    <!-- Mensajes de estado -->
    <x-pedidos.flash-messages />

    <!-- Tabla de Pedidos -->
    <div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Lista de Pedidos</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pedidos as $pedido)
                        <tr wire:key="pedido-row-{{ $pedido->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pedido->numero_pedido }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pedido->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $pedido->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach($pedido->detalles->take(2) as $detalle)
                                    <div class="text-sm text-gray-900">{{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}</div>
                                @endforeach
                                @if($pedido->detalles->count() > 2)
                                    <div class="text-sm text-gray-500">+{{ $pedido->detalles->count() - 2 }} más</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">Gs. {{ number_format($pedido->total, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($pedido->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($pedido->estado === 'en_preparacion') bg-blue-100 text-blue-800
                                    @elseif($pedido->estado === 'listo') bg-purple-100 text-purple-800
                                    @elseif($pedido->estado === 'en_camino') bg-indigo-100 text-indigo-800
                                    @elseif($pedido->estado === 'entregado') bg-green-100 text-green-800
                                    @elseif($pedido->estado === 'cancelado') bg-red-100 text-red-800
                                    @endif">
                                    {{ $pedido->estado_texto }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($pedido->estado_pago === 'pagado') bg-emerald-100 text-emerald-800
                                    @elseif($pedido->estado_pago === 'pendiente') bg-amber-100 text-amber-800
                                    @elseif($pedido->estado_pago === 'fallido') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($pedido->estado_pago) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="verDetalles({{ $pedido->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                            title="Ver detalles">
                                        <span wire:loading.remove wire:target="verDetalles({{ $pedido->id }})">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <span wire:loading wire:target="verDetalles({{ $pedido->id }})">
                                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>

                                    <button wire:click="abrirModalEstado({{ $pedido->id }})"
                                            class="text-orange-600 hover:text-orange-900 p-2 rounded-lg hover:bg-orange-50 transition-colors"
                                            title="Editar pedido">
                                        <span wire:loading.remove wire:target="abrirModalEstado({{ $pedido->id }})">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span wire:loading wire:target="abrirModalEstado({{ $pedido->id }})">
                                            <svg class="animate-spin h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>

                                    @if($pedido->estado !== 'cancelado' && $pedido->estado !== 'entregado')
                                        <button wire:click="confirmarCancelar({{ $pedido->id }})"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                title="Cancelar pedido">
                                            <span wire:loading.remove wire:target="confirmarCancelar({{ $pedido->id }})">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span wire:loading wire:target="confirmarCancelar({{ $pedido->id }})">
                                                <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="max-w-md mx-auto">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron pedidos</h3>
                                    <p class="text-gray-500 mb-6">
                                        @if($filtroEstado || $filtroEstadoPago || $filtroFecha || $busquedaCliente)
                                            No hay pedidos que coincidan con los filtros aplicados.
                                        @else
                                            Aún no hay pedidos registrados en el sistema.
                                        @endif
                                    </p>
                                    @if($filtroEstado || $filtroEstadoPago || $filtroFecha || $busquedaCliente)
                                        <button
                                            wire:click="limpiarFiltros"
                                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                        >
                                            <i class="fas fa-times mr-2"></i>
                                            Limpiar Filtros
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pedidos->links() }}
        </div>
    </div>

    <!-- Modal Ver Detalles -->
    <x-modals.pedidos.detalle-pedido
        :show="$showDetailModal"
        :pedido="$pedidoSeleccionado" />

    <!-- Modal Editar Pedido -->
    <x-modals.pedidos.cambiar-estado
        :show="$showEstadoModal"
        :pedido="$pedidoSeleccionado"
        :nuevoEstado="$nuevoEstado"
        :nuevaDireccion="$nuevaDireccion"
        :nuevoTelefono="$nuevoTelefono"
        :nuevasNotas="$nuevasNotas" />

    <!-- Modal Cancelar Pedido -->
    @if($showDeleteModal && $pedidoSeleccionado)
        <!-- Backdrop -->
        <div wire:click="closeDeleteModal"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
        </div>

        <!-- Modal Centrado -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col animate-scale-in pointer-events-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-3 rounded-full">
                                <i class="fas fa-exclamation-triangle text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Cancelar Pedido</h2>
                                <p class="text-red-100 text-sm">Confirmación requerida</p>
                            </div>
                        </div>
                        <button wire:click="closeDeleteModal" class="text-white hover:text-red-100 transition-colors">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6 space-y-6">
                    <!-- Información del Pedido -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border-l-4 border-red-500">
                        <h4 class="text-sm font-semibold text-red-800 mb-3 flex items-center">
                            <i class="fas fa-receipt mr-2"></i>
                            Detalles del Pedido
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Número:</span>
                                <span class="font-bold text-gray-800">{{ $pedidoSeleccionado->numero_pedido }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Cliente:</span>
                                <span class="font-semibold text-gray-800">{{ $pedidoSeleccionado->user->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total:</span>
                                <span class="font-bold text-orange-600">${{ number_format($pedidoSeleccionado->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Estado:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($pedidoSeleccionado->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                    @elseif($pedidoSeleccionado->estado === 'en_preparacion') bg-blue-100 text-blue-800
                                    @elseif($pedidoSeleccionado->estado === 'listo') bg-purple-100 text-purple-800
                                    @elseif($pedidoSeleccionado->estado === 'en_camino') bg-indigo-100 text-indigo-800
                                    @elseif($pedidoSeleccionado->estado === 'entregado') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $pedidoSeleccionado->estado_texto }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Advertencia -->
                    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-amber-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-amber-700">
                                <p class="font-semibold mb-2">¿Estás seguro de cancelar este pedido?</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>El pedido será marcado como <strong>cancelado</strong></li>
                                    <li>El cliente no será notificado automáticamente</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje Principal -->
                    <div class="text-center py-2">
                        <p class="text-gray-700">
                            ¿Deseas continuar con la <span class="font-bold text-red-600">cancelación</span>?
                        </p>
                    </div>
                </div>

                <!-- Footer con Acciones -->
                <div class="border-t border-gray-200 p-6 bg-gradient-to-br from-gray-50 to-gray-100">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="closeDeleteModal"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </button>
                        <button wire:click="cancelarPedido"
                                class="flex-1 bg-gradient-to-r from-red-500 to-red-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            Cancelar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlays -->
    <x-loading-overlay target="verDetalles" message="Cargando detalles del pedido..." />
    <x-loading-overlay target="closeDetailModal" message="Cerrando..." />
    <x-loading-overlay target="abrirModalEstado" message="Cargando formulario de edición..." />
    <x-loading-overlay target="actualizarPedido" message="Guardando cambios..." />
    <x-loading-overlay target="closeEstadoModal" message="Cerrando..." />
    <x-loading-overlay target="confirmarCancelar" message="Preparando cancelación..." />
    <x-loading-overlay target="cancelarPedido" message="Cancelando pedido..." />
    <x-loading-overlay target="closeDeleteModal" message="Cerrando..." />
</div>

@push('scripts')
<script>    
    // Función para inicializar los listeners de Echo
    function inicializarEchoListeners() {
        if (!window.Echo) {
            return false;
        }
        
        
        const adminChannel = window.Echo.channel('admin');
        adminChannel
            .listen('.pedido.creado', (e) => {
                
                // Mostrar toast notification
                if (window.showToast) {
                    const total = e.pedido.total ? parseFloat(e.pedido.total).toLocaleString('es-PY') : '0';
                    window.showToast(
                        `Nuevo pedido ${e.pedido.numero_pedido} - $${total}`,
                        'success',
                        5000
                    );
                    
                    // Incrementar badge
                    if (window.incrementBadge) {
                        window.incrementBadge();
                    }
                }
            })
            .listen('.pedido.cambio-estado', (e) => {
                
                // Mostrar notificación de cambio de estado
                if (window.showToast && e.pedido && e.pedido.estado_nuevo) {
                    const estadosTexto = {
                        'pendiente': 'Pendiente',
                        'en_preparacion': 'En Preparación',
                        'listo': 'Listo',
                        'en_camino': 'En Camino',
                        'entregado': 'Entregado',
                        'cancelado': 'Cancelado'
                    };
                    
                    const tipo = e.tipo || 'info'; // Usar el tipo del evento
                    
                    window.showToast(
                        `Pedido ${e.pedido.numero_pedido}: ${estadosTexto[e.pedido.estado_nuevo] || e.pedido.estado_nuevo}`,
                        tipo,
                        4000
                    );
                }
            })
            .listen('.pedido.cancelado', (e) => {
                
                // Mostrar notificación de cancelación
                if (window.showToast) {
                    window.showToast(
                        `Pedido ${e.pedido.numero_pedido} cancelado`,
                        'error',
                        4000
                    );
                }
            });
            
        return true;
    }
    
    // Intentar inicializar inmediatamente
    if (!inicializarEchoListeners()) {
        // Si no está disponible, esperar con un intervalo
        
        let intentos = 0;
        const maxIntentos = 50; // Máximo 5 segundos (50 * 100ms)
        
        const intervalo = setInterval(() => {
            intentos++;
            
            if (inicializarEchoListeners()) {
                clearInterval(intervalo);
            } else if (intentos >= maxIntentos) {
                clearInterval(intervalo);
            }
        }, 100); // Verificar cada 100ms
    }
</script>
@endpush
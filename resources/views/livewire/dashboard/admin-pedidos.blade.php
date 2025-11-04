<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Gestión de Pedidos</h1>
                <p class="text-gray-500">Administra todos los pedidos del sistema</p>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-8">
                <!-- Total Pedidos -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-list text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- En Proceso -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">En Cocina</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['en_proceso'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- En Delivery -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">En Delivery</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['en_proceso'] }}</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-full">
                            <i class="fas fa-motorcycle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pagados -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Pagados</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['pagados'] }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-money-check-alt text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select wire:model.live="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_preparacion">En preparación</option>
                            <option value="listo">Listo</option>
                            <option value="en_camino">En camino</option>
                            <option value="entregado">Entregado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago</label>
                        <select wire:model.live="filtroEstadoPago" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="pagado">Pagado</option>
                            <option value="fallido">Fallido</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input type="date" wire:model.live="filtroFecha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="busquedaCliente"
                            placeholder="Buscar cliente..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                        >
                    </div>
                    <div class="flex items-end">
                        <button
                            wire:click="limpiarFiltros"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Limpiar
                        </button>
                    </div>
                </div>

                <!-- Filtros activos -->
                @if($filtroEstado || $filtroEstadoPago || $filtroFecha || $busquedaCliente)
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                        <span class="text-sm text-gray-600 font-semibold">Filtros activos:</span>

                        @if($filtroEstado)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Estado: {{ ucfirst(str_replace('_', ' ', $filtroEstado)) }}
                                <button wire:click="$set('filtroEstado', '')" class="ml-1 text-orange-600 hover:text-orange-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($filtroEstadoPago)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Pago: {{ ucfirst($filtroEstadoPago) }}
                                <button wire:click="$set('filtroEstadoPago', '')" class="ml-1 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($filtroFecha)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Fecha: {{ \Carbon\Carbon::parse($filtroFecha)->format('d/m/Y') }}
                                <button wire:click="$set('filtroFecha', '')" class="ml-1 text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($busquedaCliente)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                "{{ $busquedaCliente }}"
                                <button wire:click="$set('busquedaCliente', '')" class="ml-1 text-green-600 hover:text-green-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Mensajes de estado -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Lista de Pedidos -->
            <div class="space-y-4 bg-white rounded-2xl shadow-lg p-6">
                @forelse($pedidos as $pedido)
                    <div class="bg-gray-100 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300" wire:key="pedido-{{ $pedido->id }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Información del Pedido -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $pedido->numero_pedido }}</h3>
                                        <p class="text-sm text-gray-500 mb-2">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $pedido->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        <div class="text-sm text-gray-600 mb-1">
                                            <i class="fas fa-user text-orange-600 mr-1"></i>
                                            <span class="font-semibold">{{ $pedido->user->name }}</span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-envelope text-orange-600 mr-1"></i>
                                            {{ $pedido->user->email }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                                            @if($pedido->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                            @elseif($pedido->estado === 'en_preparacion') bg-blue-100 text-blue-800
                                            @elseif($pedido->estado === 'listo') bg-purple-100 text-purple-800
                                            @elseif($pedido->estado === 'en_camino') bg-indigo-100 text-indigo-800
                                            @elseif($pedido->estado === 'entregado') bg-green-100 text-green-800
                                            @elseif($pedido->estado === 'cancelado') bg-red-100 text-red-800
                                            @endif">
                                            {{ $pedido->estado_texto }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($pedido->estado_pago === 'pagado') bg-emerald-100 text-emerald-800
                                            @elseif($pedido->estado_pago === 'pendiente') bg-amber-100 text-amber-800
                                            @elseif($pedido->estado_pago === 'fallido') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($pedido->estado_pago) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm mb-3">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-shopping-bag text-orange-600 mr-2"></i>
                                        <span>
                                            @foreach($pedido->detalles->take(2) as $detalle)
                                                {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}@if(!$loop->last), @endif
                                            @endforeach
                                            @if($pedido->detalles->count() > 2)
                                                +{{ $pedido->detalles->count() - 2 }} más
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>
                                        <span class="truncate">{{ Str::limit($pedido->direccion_entrega, 40) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total y Acciones -->
                            <div class="flex items-center gap-4 lg:flex-col lg:items-end">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total</p>
                                    <p class="text-2xl font-bold text-orange-600">Gs. {{ number_format($pedido->total, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button wire:click="verDetalles({{ $pedido->id }})"
                                            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                            title="Ver detalles">
                                        <span wire:loading.remove wire:target="verDetalles({{ $pedido->id }})">
                                            <i class="fas fa-eye mr-2"></i>
                                            Ver
                                        </span>
                                        <span wire:loading wire:target="verDetalles({{ $pedido->id }})">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>

                                    <button wire:click="abrirModalEstado({{ $pedido->id }})"
                                            class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                            title="Editar pedido">
                                        <span wire:loading.remove wire:target="abrirModalEstado({{ $pedido->id }})">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar
                                        </span>
                                        <span wire:loading wire:target="abrirModalEstado({{ $pedido->id }})">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>

                                    @if($pedido->estado !== 'cancelado' && $pedido->estado !== 'entregado')
                                        <button wire:click="confirmarCancelar({{ $pedido->id }})"
                                                class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                                title="Cancelar pedido">
                                            <span wire:loading.remove wire:target="confirmarCancelar({{ $pedido->id }})">
                                                <i class="fas fa-trash mr-2"></i>
                                                Cancelar
                                            </span>
                                            <span wire:loading wire:target="confirmarCancelar({{ $pedido->id }})">
                                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-inbox text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">No se encontraron pedidos</h3>
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
                                class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-times mr-2"></i>
                                Limpiar Filtros
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($pedidos->hasPages())
                <div class="mt-8">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </main>
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
                                <span class="font-bold text-orange-600">Gs. {{ number_format($pedidoSeleccionado->total, 0, ',', '.') }}</span>
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

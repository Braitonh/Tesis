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
    <x-pedidos.tabla-pedidos
        :pedidos="$pedidos"
        :filtroEstado="$filtroEstado"
        :filtroEstadoPago="$filtroEstadoPago"
        :filtroFecha="$filtroFecha"
        :busquedaCliente="$busquedaCliente" />

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
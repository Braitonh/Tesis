<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('cliente.bienvenida') }}"
                   class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al menú
                </a>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Mis Pedidos</h1>
                <p class="text-gray-600">Revisa el estado y historial de tus pedidos</p>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Pedidos -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total Pedidos</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalPedidos }}</p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-receipt text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pedidos Activos -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Pedidos Activos</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $pedidosActivos }}</p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-clock text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Gastado -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total Gastado</p>
                            <p class="text-3xl font-bold text-gray-800">${{ number_format($totalGastado, 2) }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center flex-wrap gap-3">
                    <span class="text-gray-700 font-semibold mr-2">Filtrar por:</span>

                    <button wire:click="filtrarPor('todos')"
                            class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $filtroEstado === 'todos' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-list mr-2"></i>
                        Todos
                    </button>

                    <button wire:click="filtrarPor('en_proceso')"
                            class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $filtroEstado === 'en_proceso' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-spinner mr-2"></i>
                        En Proceso
                    </button>

                    <button wire:click="filtrarPor('entregados')"
                            class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $filtroEstado === 'entregados' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-check-circle mr-2"></i>
                        Entregados
                    </button>

                    <button wire:click="filtrarPor('cancelados')"
                            class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $filtroEstado === 'cancelados' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-times-circle mr-2"></i>
                        Cancelados
                    </button>
                </div>
            </div>

            <!-- Lista de Pedidos -->
            <div class="space-y-4">
                @forelse($pedidos as $pedido)
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300" wire:key="pedido-{{ $pedido->id }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Información del Pedido -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $pedido->numero_pedido }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $pedido->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                                        @if($pedido->estado_color === 'yellow') bg-yellow-100 text-yellow-800
                                        @elseif($pedido->estado_color === 'blue') bg-blue-100 text-blue-800
                                        @elseif($pedido->estado_color === 'purple') bg-purple-100 text-purple-800
                                        @elseif($pedido->estado_color === 'indigo') bg-indigo-100 text-indigo-800
                                        @elseif($pedido->estado_color === 'green') bg-green-100 text-green-800
                                        @elseif($pedido->estado_color === 'red') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $pedido->estado_texto }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>
                                        <span class="truncate">{{ Str::limit($pedido->direccion_entrega, 40) }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-shopping-bag text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->detalles->count() }} {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total y Acción -->
                            <div class="flex items-center gap-4 lg:flex-col lg:items-end">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total</p>
                                    <p class="text-2xl font-bold text-orange-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>
                                <button wire:click="verDetalles({{ $pedido->id }})"
                                        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-receipt text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">
                            @if($filtroEstado === 'todos')
                                No tienes pedidos aún
                            @else
                                No hay pedidos {{ $filtroEstado === 'en_proceso' ? 'en proceso' : $filtroEstado }}
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-6">
                            @if($filtroEstado === 'todos')
                                ¡Haz tu primer pedido y disfruta de nuestra deliciosa comida!
                            @else
                                Prueba cambiar el filtro para ver otros pedidos
                            @endif
                        </p>
                        <a href="{{ route('cliente.bienvenida') }}"
                           class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-utensils mr-2"></i>
                            Ver Menú
                        </a>
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

    <!-- Modal de Detalles del Pedido -->
    @if($mostrarDetalles && $pedidoSeleccionado)
        <!-- Backdrop -->
        <div wire:click="cerrarDetalles"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
        </div>

        <!-- Modal Centrado -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col animate-scale-in">
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">Detalles del Pedido</h2>
                            <p class="text-orange-100 text-sm mt-1">{{ $pedidoSeleccionado->numero_pedido }}</p>
                        </div>
                        <button wire:click="cerrarDetalles" class="text-white hover:text-orange-100 transition-colors">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Contenido Scrolleable -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Timeline del Estado -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-orange-100 text-sm mb-1">Estado Actual</p>
                                <p class="text-2xl font-bold">{{ $pedidoSeleccionado->estado_texto }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-full">
                                <i class="fas
                                    @if($pedidoSeleccionado->estado === 'pendiente') fa-clock
                                    @elseif($pedidoSeleccionado->estado === 'en_preparacion') fa-fire
                                    @elseif($pedidoSeleccionado->estado === 'listo') fa-check
                                    @elseif($pedidoSeleccionado->estado === 'en_camino') fa-motorcycle
                                    @elseif($pedidoSeleccionado->estado === 'entregado') fa-check-circle
                                    @else fa-times-circle
                                    @endif text-3xl"></i>
                            </div>
                        </div>

                        <!-- Progress Timeline -->
                        <div class="flex items-center justify-between relative">
                            @php
                                $estados = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado'];
                                $estadoActualIndex = array_search($pedidoSeleccionado->estado, $estados);
                                $cancelado = $pedidoSeleccionado->estado === 'cancelado';
                            @endphp

                            @if(!$cancelado)
                                <!-- Línea de progreso -->
                                <div class="absolute top-4 left-0 right-0 h-1 bg-white/30"></div>
                                <div class="absolute top-4 left-0 h-1 bg-white transition-all duration-500"
                                     style="width: {{ $estadoActualIndex >= 0 ? ($estadoActualIndex / (count($estados) - 1)) * 100 : 0 }}%"></div>

                                @foreach($estados as $index => $estado)
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $index <= $estadoActualIndex ? 'bg-white text-orange-600' : 'bg-white/30 text-white' }}">
                                            <i class="fas fa-check text-sm"></i>
                                        </div>
                                        <span class="text-xs mt-2 text-center hidden sm:block">
                                            @if($estado === 'pendiente') Pendiente
                                            @elseif($estado === 'en_preparacion') Preparando
                                            @elseif($estado === 'listo') Listo
                                            @elseif($estado === 'en_camino') En Camino
                                            @elseif($estado === 'entregado') Entregado
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center w-full"><i class="fas fa-ban mr-2"></i>Pedido Cancelado</p>
                            @endif
                        </div>
                    </div>

                    <!-- Información de Entrega -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Dirección de Entrega</h4>
                            <p class="text-gray-800 flex items-start">
                                <i class="fas fa-map-marker-alt text-orange-600 mr-2 mt-1"></i>
                                <span>{{ $pedidoSeleccionado->direccion_entrega }}</span>
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Teléfono de Contacto</h4>
                            <p class="text-gray-800 flex items-center">
                                <i class="fas fa-phone text-orange-600 mr-2"></i>
                                {{ $pedidoSeleccionado->telefono_contacto }}
                            </p>
                        </div>
                    </div>

                    @if($pedidoSeleccionado->notas)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Notas del Pedido</h4>
                            <p class="text-gray-700 flex items-start">
                                <i class="fas fa-comment-alt text-gray-400 mr-2 mt-1"></i>
                                <span>{{ $pedidoSeleccionado->notas }}</span>
                            </p>
                        </div>
                    @endif

                    <!-- Productos del Pedido -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Productos</h3>
                        <div class="space-y-3">
                            @foreach($pedidoSeleccionado->detalles as $detalle)
                                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
                                        <img src="{{ $detalle->producto->image_url }}"
                                             alt="{{ $detalle->producto->nombre }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                            <i class="fas fa-utensils text-gray-400 text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800">{{ $detalle->producto->nombre }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2) }}
                                        </p>
                                    </div>
                                    <p class="font-bold text-gray-800">
                                        ${{ number_format($detalle->subtotal, 2) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center text-2xl font-bold">
                            <span class="text-gray-800">Total</span>
                            <span class="text-orange-600">${{ number_format($pedidoSeleccionado->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer con Acciones -->
                <div class="border-t border-gray-200 p-6 bg-gray-50">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="cerrarDetalles"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cerrar
                        </button>
                        <button wire:click="volverAPedir({{ $pedidoSeleccionado->id }})"
                                class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-redo mr-2"></i>
                            Volver a Pedir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
             style="display: none;">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
             style="display: none;">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Escuchar actualizaciones de pedidos del usuario actual
    if (window.Echo) {
        @foreach($pedidos as $pedido)
            window.Echo.private('pedidos.{{ $pedido->id }}')
                .listen('.pedido.cambio-estado', (e) => {
                    console.log('Cliente - Estado de mi pedido cambió:', e);
                    @this.call('refreshPedidos');
                });
        @endforeach
    }
</script>
@endpush
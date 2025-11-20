<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">
                    @if($isAdmin)
                        Gestión de Delivery
                    @else
                        Mis Entregas
                    @endif
                </h1>
                <p class="text-gray-500">Panel de {{ $isAdmin ? 'administración de entregas' : 'delivery' }}</p>
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

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Disponibles / En Camino -->
                @if($isAdmin)
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase mb-1">En Camino</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalEnCamino }}</p>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-full">
                                <i class="fas fa-motorcycle text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Mis Entregas Activas</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalMisPedidos }}</p>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-full">
                                <i class="fas fa-motorcycle text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Completados Hoy -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Entregas Hoy</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $entregasHoy }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs de Navegación -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex items-center flex-wrap gap-3">
                    <span class="text-gray-700 font-semibold mr-2">Filtrar por:</span>

                    <button wire:click="cambiarVista('disponibles')"
                            class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $vistaActiva === 'disponibles' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-box mr-2"></i>
                        Pedidos Disponibles
                        @if($totalDisponibles > 0)
                            <span class="ml-2 {{ $vistaActiva === 'disponibles' ? 'bg-white/20 text-white' : 'bg-orange-500 text-white' }} px-2 py-1 rounded-full text-xs font-bold">
                                {{ $totalDisponibles }}
                            </span>
                        @endif
                    </button>

                    @if($isAdmin)
                        <button wire:click="cambiarVista('en_camino')"
                                class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $vistaActiva === 'en_camino' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-motorcycle mr-2"></i>
                            En Camino
                            @if($totalEnCamino > 0)
                                <span class="ml-2 {{ $vistaActiva === 'en_camino' ? 'bg-white/20 text-white' : 'bg-orange-500 text-white' }} px-2 py-1 rounded-full text-xs font-bold">
                                    {{ $totalEnCamino }}
                                </span>
                            @endif
                        </button>
                    @else
                        <button wire:click="cambiarVista('mis_entregas')"
                                class="px-6 py-2 rounded-lg font-medium transition-all duration-300 {{ $vistaActiva === 'mis_entregas' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-motorcycle mr-2"></i>
                            Mis Entregas
                            @if($totalMisPedidos > 0)
                                <span class="ml-2 {{ $vistaActiva === 'mis_entregas' ? 'bg-white/20 text-white' : 'bg-blue-500 text-white' }} px-2 py-1 rounded-full text-xs font-bold">
                                    {{ $totalMisPedidos }}
                                </span>
                            @endif
                        </button>
                    @endif
                </div>
            </div>

            <!-- Empty State -->
            @if($vistaActiva === 'disponibles' && $pedidosDisponibles->count() == 0)
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-100 rounded-full mb-6">
                        <i class="fas fa-box-open text-orange-600 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No hay pedidos disponibles</h3>
                    <p class="text-gray-500 mb-6">
                        Todos los pedidos están asignados o no hay pedidos listos para entregar.
                    </p>
                    <div class="inline-flex items-center text-sm text-gray-500">
                        <i class="fas fa-sync-alt animate-spin mr-2"></i>
                        Actualizando en tiempo real
                    </div>
                </div>
            @endif

            @if(($vistaActiva === 'en_camino' && $pedidosEnCamino->count() == 0) || ($vistaActiva === 'mis_entregas' && $misPedidos->count() == 0))
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center mb-8">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-t-2xl -m-6 mb-6">
                        <i class="fas fa-motorcycle text-orange-600 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">
                        @if($vistaActiva === 'en_camino')
                            No hay pedidos en camino
                        @else
                            No tienes entregas activas
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-6">
                        @if($vistaActiva === 'en_camino')
                            Actualmente no hay entregas en curso.
                        @else
                            Toma un pedido de la sección "Pedidos Disponibles" para comenzar.
                        @endif
                    </p>
                    <div class="inline-flex items-center text-sm text-gray-500">
                        <i class="fas fa-sync-alt animate-spin mr-2"></i>
                        Actualizando en tiempo real
                    </div>
                </div>
            @endif

            <!-- Pedidos Disponibles -->
            @if($vistaActiva === 'disponibles' && $pedidosDisponibles->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-t-2xl -m-6 mb-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-box mr-3"></i>
                                Pedidos Disponibles
                            </h3>
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-bold">
                                {{ $pedidosDisponibles->count() }} pedidos
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pedidosDisponibles as $pedido)
                            <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-4 shadow-lg hover:shadow-xl transition-shadow duration-300" wire:key="disponible-{{ $pedido->id }}">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-bold text-orange-800">
                                        {{ $pedido->numero_pedido }}
                                    </span>
                                    <span class="text-xs text-orange-600 font-medium">
                                        {{ $pedido->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    {{ $pedido->user->name }}
                                </div>

                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    {{ $pedido->telefono_contacto }}
                                </div>

                                <div class="mb-3 text-xs text-gray-600">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    {{ Str::limit($pedido->direccion_entrega, 35) }}
                                </div>

                                <div class="mb-3 text-xs text-gray-600">
                                    <i class="fas fa-shopping-bag text-gray-400 mr-1"></i>
                                    {{ $pedido->detalles->count() }} {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}
                                </div>

                                @if($pedido->notas)
                                    <div class="mb-3 bg-amber-50 border border-amber-200 rounded-lg p-2">
                                        <p class="text-xs text-amber-800 font-medium">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            {{ Str::limit($pedido->notas, 50) }}
                                        </p>
                                    </div>
                                @endif

                                <div class="mb-3 text-right">
                                    <p class="text-xs text-gray-500">Total</p>
                                    <p class="text-lg font-bold text-orange-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>

                                @if($isAdmin)
                                    <div class="mb-2">
                                        <select wire:model="deliverySeleccionado"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-orange-500">
                                            <option value="">Asignar delivery...</option>
                                            @foreach($deliverysDisponibles as $delivery)
                                                <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="asignarDelivery({{ $pedido->id }})"
                                                class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                            <i class="fas fa-user-plus mr-1"></i>Asignar
                                        </button>
                                        <button wire:click="verDetalles({{ $pedido->id }})"
                                                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                                title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex gap-2">
                                        <button wire:click="tomarPedido({{ $pedido->id }})"
                                                class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                            <i class="fas fa-hand-paper mr-1"></i>Tomar
                                        </button>
                                        <button wire:click="verDetalles({{ $pedido->id }})"
                                                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                                title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pedidos En Camino (Solo Admin) -->
            @if($vistaActiva === 'en_camino' && $pedidosEnCamino->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-t-2xl -m-6 mb-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-motorcycle mr-3"></i>
                                Pedidos En Camino
                            </h3>
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-bold">
                                {{ $pedidosEnCamino->count() }} pedidos
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pedidosEnCamino as $pedido)
                            <div class="border-l-4 border-orange-500 rounded-lg p-4 shadow-lg hover:shadow-xl transition-shadow duration-300" wire:key="encamino-{{ $pedido->id }}">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-bold text-orange-800">
                                        {{ $pedido->numero_pedido }}
                                    </span>
                                    <span class="text-xs bg-orange-500 text-white px-3 py-1 rounded-full font-bold">
                                        EN CAMINO
                                    </span>
                                </div>

                                <!-- Delivery Asignado -->
                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-motorcycle text-gray-400 mr-1"></i>
                                    {{ $pedido->delivery->name }}
                                </div>

                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    {{ $pedido->user->name }}
                                </div>

                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    {{ $pedido->telefono_contacto }}
                                </div>

                                <div class="mb-3 text-xs text-gray-600">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    {{ Str::limit($pedido->direccion_entrega, 35) }}
                                </div>

                                <div class="mb-3 text-xs text-gray-600">
                                    <i class="fas fa-shopping-bag text-gray-400 mr-1"></i>
                                    {{ $pedido->detalles->count() }} {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}
                                </div>

                                @if($pedido->notas)
                                    <div class="mb-3 bg-amber-50 border border-amber-200 rounded-lg p-2">
                                        <p class="text-xs text-amber-800 font-medium">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            {{ Str::limit($pedido->notas, 50) }}
                                        </p>
                                    </div>
                                @endif

                                <div class="mb-3 text-right">
                                    <p class="text-xs text-gray-500">Total</p>
                                    <p class="text-lg font-bold text-orange-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>

                                <button wire:click="verDetalles({{ $pedido->id }})"
                                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Ver Detalles
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mis Entregas (Solo Delivery) -->
            @if($vistaActiva === 'mis_entregas' && $misPedidos->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-2xl -m-6 mb-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-motorcycle mr-3"></i>
                                Mis Entregas Activas
                            </h3>
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-bold">
                                {{ $misPedidos->count() }} pedidos
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($misPedidos as $pedido)
                            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-lg hover:shadow-xl transition-shadow duration-300" wire:key="misped-{{ $pedido->id }}">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-bold text-green-800">
                                        {{ $pedido->numero_pedido }}
                                    </span>
                                    <span class="text-xs bg-green-500 text-white px-3 py-1 rounded-full font-bold">
                                        EN CAMINO
                                    </span>
                                </div>

                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    {{ $pedido->user->name }}
                                </div>

                                <div class="mb-2 text-xs text-gray-600">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    {{ $pedido->telefono_contacto }}
                                </div>

                                <div class="mb-3 text-xs text-gray-600">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    {{ Str::limit($pedido->direccion_entrega, 35) }}
                                </div>

                                <div class="mb-3 text-xs text-gray-600">
                                    <i class="fas fa-shopping-bag text-gray-400 mr-1"></i>
                                    {{ $pedido->detalles->count() }} {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}
                                </div>

                                @if($pedido->notas)
                                    <div class="mb-3 bg-amber-50 border border-amber-200 rounded-lg p-2">
                                        <p class="text-xs text-amber-800 font-medium">
                                            <i class="fas fa-sticky-note mr-1"></i>
                                            {{ Str::limit($pedido->notas, 50) }}
                                        </p>
                                    </div>
                                @endif

                                <div class="mb-3 text-right">
                                    <p class="text-xs text-gray-500">Total</p>
                                    <p class="text-lg font-bold text-green-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>

                                <div class="flex gap-2">
                                    <button wire:click="marcarComoEntregado({{ $pedido->id }})"
                                            class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-2 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                        <i class="fas fa-check mr-1"></i>Entregado
                                    </button>
                                    <button wire:click="verDetalles({{ $pedido->id }})"
                                            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                            title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                    <!-- Estado del Pedido -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm mb-1">Estado Actual</p>
                                <p class="text-2xl font-bold">{{ $pedidoSeleccionado->estado_texto }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-full">
                                <i class="fas
                                    @if($pedidoSeleccionado->estado === 'listo') fa-box
                                    @elseif($pedidoSeleccionado->estado === 'en_camino') fa-motorcycle
                                    @elseif($pedidoSeleccionado->estado === 'entregado') fa-check-circle
                                    @else fa-question-circle
                                    @endif text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Cliente -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Información del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Cliente</h4>
                                <p class="text-gray-800 flex items-center">
                                    <i class="fas fa-user text-orange-600 mr-2"></i>
                                    {{ $pedidoSeleccionado->user->name }}
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
                    </div>

                    <!-- Información de Entrega -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Dirección de Entrega</h4>
                        <p class="text-gray-800 flex items-start">
                            <i class="fas fa-map-marker-alt text-orange-600 mr-2 mt-1"></i>
                            <span>{{ $pedidoSeleccionado->direccion_entrega }}</span>
                        </p>
                    </div>

                    @if($pedidoSeleccionado->delivery)
                        <!-- Información del Delivery -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Delivery Asignado</h4>
                            <p class="text-gray-800 flex items-center">
                                <i class="fas fa-motorcycle text-indigo-600 mr-2"></i>
                                <span class="font-medium">{{ $pedidoSeleccionado->delivery->name }}</span>
                            </p>
                        </div>
                    @endif

                    @if($pedidoSeleccionado->notas)
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Notas del Pedido</h4>
                            <p class="text-gray-700 flex items-start">
                                <i class="fas fa-sticky-note text-yellow-600 mr-2 mt-1"></i>
                                <span>{{ $pedidoSeleccionado->notas }}</span>
                            </p>
                        </div>
                    @endif

                    <!-- Productos del Pedido -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Productos</h3>
                        <div class="space-y-3">
                            @foreach($pedidoSeleccionado->detalles as $detalle)
                                @if($detalle->promocion_id)
                                    {{-- Es una promoción --}}
                                    <div class="flex items-center gap-4 p-3 bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg border border-orange-200">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-orange-100">
                                            <img src="{{ $detalle->promocion->image_url }}"
                                                 alt="{{ $detalle->promocion->nombre }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                                <i class="fas fa-gift text-orange-400 text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-800">
                                                <i class="fas fa-gift text-orange-500 mr-1"></i>
                                                {{ $detalle->promocion->nombre }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <p class="font-bold text-orange-600">
                                            ${{ number_format($detalle->subtotal, 2, ',', '.') }}
                                        </p>
                                    </div>
                                @else
                                    {{-- Es un producto --}}
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
                                                {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_unitario, 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <p class="font-bold text-gray-800">
                                            ${{ number_format($detalle->subtotal, 2, ',', '.') }}
                                        </p>
                                    </div>
                                @endif
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
                        @if($isDelivery && $pedidoSeleccionado->estado === 'en_camino' && $pedidoSeleccionado->delivery_id === auth()->id())
                            <button wire:click="marcarComoEntregado({{ $pedidoSeleccionado->id }})"
                                    class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-check-circle mr-2"></i>
                                Marcar como Entregado
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Función para inicializar los listeners de Echo para delivery
    function inicializarEchoListeners() {
        if (!window.Echo) {
            return false;
        }
        
        const deliveryChannel = window.Echo.channel('delivery');
        
        deliveryChannel
            .listen('.pedido.cambio-estado', (e) => {
                console.log('Delivery - Cambio de estado de pedido:', e);

                // Mostrar notificación toast según el nuevo estado
                if (window.showToast && e.pedido) {
                    const numeroPedido = e.pedido.numero_pedido || 'N/A';
                    const total = e.pedido.total ? parseFloat(e.pedido.total).toLocaleString('es-PY') : '0';
                    const estadoNuevo = e.pedido.estado_nuevo;

                    // Pedido listo para delivery
                    if (estadoNuevo === 'listo') {
                        window.showToast(
                            `¡Pedido listo para entregar! ${numeroPedido} - $. ${total}`,
                            'success',
                            6000
                        );

                        // Reproducir sonido de notificación
                        if (window.playNotificationSound) {
                            window.playNotificationSound();
                        }

                        // Incrementar badge
                        if (window.incrementBadge) {
                            window.incrementBadge();
                        }
                    }
                    // Pedido en camino
                    else if (estadoNuevo === 'en_camino') {
                        window.showToast(
                            `Pedido ${numeroPedido} en camino al cliente`,
                            'info',
                            4000
                        );
                    }
                    // Pedido entregado
                    else if (estadoNuevo === 'entregado') {
                        window.showToast(
                            `✅ Pedido ${numeroPedido} entregado con éxito`,
                            'success',
                            5000
                        );
                        
                        // Reproducir sonido de confirmación
                        if (window.playNotificationSound) {
                            window.playNotificationSound();
                        }
                    }
                }
            });
            
        return true;
    }
    
    // Intentar inicializar inmediatamente
    if (!inicializarEchoListeners()) {
        // Si no está disponible, esperar con un intervalo
        console.log('⏳ Esperando a que Echo esté disponible (Delivery)...');
        
        let intentos = 0;
        const maxIntentos = 50; // Máximo 5 segundos (50 * 100ms)
        
        const intervalo = setInterval(() => {
            intentos++;
            
            if (inicializarEchoListeners()) {
                console.log('✅ Echo listeners de delivery inicializados correctamente');
                clearInterval(intervalo);
            } else if (intentos >= maxIntentos) {
                console.error('❌ No se pudo inicializar Echo después de ' + maxIntentos + ' intentos');
                clearInterval(intervalo);
            }
        }, 100); // Verificar cada 100ms
    } else {
        console.log('✅ Echo listeners de delivery inicializados inmediatamente');
    }
</script>
@endpush

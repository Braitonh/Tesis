<div class="p-6 space-y-6 bg-white/80 rounded-3xl w-full max-w-none">
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-amber-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">
                        <i class="fas fa-motorcycle mr-3"></i>
                        @if($isAdmin)
                            Gestión de Delivery
                        @else
                            Mis Entregas
                        @endif
                    </h1>
                    <p class="text-orange-100">
                        @if($isAdmin)
                            Panel de administración de entregas - {{ auth()->user()->name }}
                        @else
                            Panel de delivery - {{ auth()->user()->name }}
                        @endif
                    </p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <i class="fas fa-sync-alt text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Disponibles -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 shadow-sm border border-orange-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-orange-500 p-3 rounded-xl shadow-sm">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-orange-600 text-sm font-medium">Pedidos Disponibles</p>
                    <p class="text-3xl font-bold text-orange-700">{{ $totalDisponibles }}</p>
                </div>
            </div>
        </div>

        @if($isAdmin)
            <!-- Total En Camino (Admin) -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="bg-blue-500 p-3 rounded-xl shadow-sm">
                        <i class="fas fa-motorcycle text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-600 text-sm font-medium">En Camino</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $totalEnCamino }}</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Mis Entregas Activas (Delivery) -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="bg-blue-500 p-3 rounded-xl shadow-sm">
                        <i class="fas fa-motorcycle text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-600 text-sm font-medium">Mis Entregas Activas</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $totalMisPedidos }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Entregas Hoy -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-500 p-3 rounded-xl shadow-sm">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-green-600 text-sm font-medium">Entregas Hoy</p>
                    <p class="text-3xl font-bold text-green-700">{{ $entregasHoy }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Navegación -->
    <div class="bg-white rounded-2xl shadow-sm border border-orange-100">
            <div class="border-b border-gray-200">
                <div class="flex flex-wrap -mb-px">
                    <button
                        wire:click="cambiarVista('disponibles')"
                        class="px-6 py-4 text-sm font-medium transition-all duration-300 border-b-2
                            {{ $vistaActiva === 'disponibles'
                                ? 'border-orange-500 text-orange-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-box mr-2"></i>
                        Pedidos Disponibles
                        @if($totalDisponibles > 0)
                            <span class="ml-2 bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $totalDisponibles }}
                            </span>
                        @endif
                    </button>

                    @if($isAdmin)
                        <button
                            wire:click="cambiarVista('en_camino')"
                            class="px-6 py-4 text-sm font-medium transition-all duration-300 border-b-2
                                {{ $vistaActiva === 'en_camino'
                                    ? 'border-orange-500 text-orange-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-motorcycle mr-2"></i>
                            Pedidos En Camino
                            @if($totalEnCamino > 0)
                                <span class="ml-2 bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $totalEnCamino }}
                                </span>
                            @endif
                        </button>
                    @else
                        <button
                            wire:click="cambiarVista('mis_entregas')"
                            class="px-6 py-4 text-sm font-medium transition-all duration-300 border-b-2
                                {{ $vistaActiva === 'mis_entregas'
                                    ? 'border-orange-500 text-orange-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-motorcycle mr-2"></i>
                            Mis Entregas
                            @if($totalMisPedidos > 0)
                                <span class="ml-2 bg-orange-100 text-orange-600 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $totalMisPedidos }}
                                </span>
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenido de los Tabs -->
        <div class="space-y-4">
            @if($vistaActiva === 'disponibles')
                <!-- Pedidos Disponibles -->
                @forelse($pedidosDisponibles as $pedido)
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300" wire:key="disponible-{{ $pedido->id }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Información del Pedido -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $pedido->numero_pedido }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $pedido->created_at->format('d/m/Y H:i') }}
                                            <span class="ml-2 text-xs text-gray-400">
                                                ({{ $pedido->created_at->diffForHumans() }})
                                            </span>
                                        </p>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                        {{ $pedido->estado_texto }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->user->name }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-phone text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->telefono_contacto }}</span>
                                    </div>
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
                            <div class="flex flex-col items-end gap-3">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total</p>
                                    <p class="text-2xl font-bold text-orange-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>

                                @if($isAdmin)
                                    <!-- Admin: Asignar Delivery -->
                                    <div class="flex items-center gap-2">
                                        <select wire:model="deliverySeleccionado"
                                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            <option value="">Seleccionar delivery...</option>
                                            @foreach($deliverysDisponibles as $delivery)
                                                <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                            @endforeach
                                        </select>
                                        <button wire:click="asignarDelivery({{ $pedido->id }})"
                                                class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                            <i class="fas fa-user-plus mr-1"></i>
                                            Asignar
                                        </button>
                                    </div>
                                @else
                                    <!-- Delivery: Tomar Pedido -->
                                    <button wire:click="tomarPedido({{ $pedido->id }})"
                                            class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <i class="fas fa-hand-paper mr-2"></i>
                                        Tomar Pedido
                                    </button>
                                @endif

                                <button wire:click="verDetalles({{ $pedido->id }})"
                                        class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="bg-gradient-to-br from-orange-100 to-amber-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-box-open text-4xl text-orange-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">No hay pedidos disponibles</h3>
                            <p class="text-gray-600 mb-6">Todos los pedidos están asignados o no hay pedidos listos para entregar.</p>
                            <div class="inline-flex items-center text-sm text-gray-500">
                                <i class="fas fa-sync-alt animate-spin mr-2"></i>
                                Actualizando en tiempo real
                            </div>
                        </div>
                    </div>
                @endforelse

            @elseif($vistaActiva === 'en_camino')
                <!-- Pedidos En Camino (Solo Admin) -->
                @forelse($pedidosEnCamino as $pedido)
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300" wire:key="encamino-{{ $pedido->id }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Información del Pedido -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $pedido->numero_pedido }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $pedido->created_at->format('d/m/Y H:i') }}
                                            <span class="ml-2 text-xs text-gray-400">
                                                ({{ $pedido->created_at->diffForHumans() }})
                                            </span>
                                        </p>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                        {{ $pedido->estado_texto }}
                                    </span>
                                </div>

                                <!-- Delivery Asignado -->
                                <div class="mb-3 p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-motorcycle text-indigo-600"></i>
                                        <span class="text-sm font-semibold text-gray-700">Delivery:</span>
                                        <span class="text-sm text-gray-800 font-medium">{{ $pedido->delivery->name }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->user->name }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-phone text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->telefono_contacto }}</span>
                                    </div>
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
                            <div class="flex flex-col items-end gap-3">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total</p>
                                    <p class="text-2xl font-bold text-orange-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>

                                <button wire:click="verDetalles({{ $pedido->id }})"
                                        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="bg-gradient-to-br from-indigo-100 to-purple-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-motorcycle text-4xl text-indigo-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">No hay pedidos en camino</h3>
                            <p class="text-gray-600 mb-6">Actualmente no hay entregas en curso.</p>
                            <div class="inline-flex items-center text-sm text-gray-500">
                                <i class="fas fa-sync-alt animate-spin mr-2"></i>
                                Actualizando en tiempo real
                            </div>
                        </div>
                    </div>
                @endforelse

            @elseif($vistaActiva === 'mis_entregas')
                <!-- Mis Entregas (Solo Delivery) -->
                @forelse($misPedidos as $pedido)
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300" wire:key="misped-{{ $pedido->id }}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Información del Pedido -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $pedido->numero_pedido }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $pedido->created_at->format('d/m/Y H:i') }}
                                            <span class="ml-2 text-xs text-gray-400">
                                                ({{ $pedido->created_at->diffForHumans() }})
                                            </span>
                                        </p>
                                    </div>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                        {{ $pedido->estado_texto }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm mb-3">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->user->name }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-phone text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->telefono_contacto }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600 md:col-span-2">
                                        <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->direccion_entrega }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-shopping-bag text-orange-600 mr-2"></i>
                                        <span>{{ $pedido->detalles->count() }} {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}</span>
                                    </div>
                                </div>

                                @if($pedido->notas)
                                    <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <p class="text-sm text-gray-700">
                                            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                                            <span class="font-semibold">Nota:</span> {{ $pedido->notas }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Total y Acción -->
                            <div class="flex flex-col items-end gap-3">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total</p>
                                    <p class="text-2xl font-bold text-orange-600">${{ number_format($pedido->total, 2) }}</p>
                                </div>

                                <button wire:click="marcarComoEntregado({{ $pedido->id }})"
                                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Marcar como Entregado
                                </button>

                                <button wire:click="verDetalles({{ $pedido->id }})"
                                        class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="bg-gradient-to-br from-green-100 to-emerald-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-motorcycle text-4xl text-green-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">No tienes entregas activas</h3>
                            <p class="text-gray-600 mb-6">Toma un pedido de la sección "Pedidos Disponibles" para comenzar.</p>
                            <button wire:click="cambiarVista('disponibles')"
                                    class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-box mr-2"></i>
                                Ver Pedidos Disponibles
                            </button>
                        </div>
                    </div>
                @endforelse
            @endif
        </div>
    </main>

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
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
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
                            `🍕 ¡Pedido listo para entregar! ${numeroPedido} - Gs. ${total}`,
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
                            `🚀 Pedido ${numeroPedido} en camino al cliente`,
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

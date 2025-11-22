@props(['show' => false, 'pedido' => null])

@if($show && $pedido)
    <!-- Backdrop -->
    <div wire:click="closeDetailModal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
    </div>

    <!-- Modal Centrado -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col animate-scale-in pointer-events-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Detalles del Pedido</h2>
                        <p class="text-orange-100 text-sm mt-1">{{ $pedido->numero_pedido }}</p>
                    </div>
                    <button wire:click="closeDetailModal" class="text-white hover:text-orange-100 transition-colors">
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
                            <p class="text-2xl font-bold">{{ $pedido->estado_texto }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <i class="fas
                                @if($pedido->estado === 'pendiente') fa-clock
                                @elseif($pedido->estado === 'en_preparacion') fa-fire
                                @elseif($pedido->estado === 'listo') fa-check
                                @elseif($pedido->estado === 'en_camino') fa-motorcycle
                                @elseif($pedido->estado === 'entregado') fa-check-circle
                                @else fa-times-circle
                                @endif text-3xl"></i>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="flex items-center justify-between relative">
                        @php
                            $estados = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado'];
                            $estadoActualIndex = array_search($pedido->estado, $estados);
                            $cancelado = $pedido->estado === 'cancelado';
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

                <!-- Información del Cliente -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-orange-600 mr-2"></i>
                        Información del Cliente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Nombre</h4>
                            <p class="text-gray-800 flex items-center font-medium">
                                <i class="fas fa-user text-orange-600 mr-2"></i>
                                {{ $pedido->user->name }}
                            </p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Email</h4>
                            <p class="text-gray-800 flex items-center font-medium">
                                <i class="fas fa-envelope text-orange-600 mr-2"></i>
                                <span class="truncate">{{ $pedido->user->email }}</span>
                            </p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Teléfono de Contacto</h4>
                            <p class="text-gray-800 flex items-center font-medium">
                                <i class="fas fa-phone text-orange-600 mr-2"></i>
                                {{ $pedido->telefono_contacto }}
                            </p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Dirección de Entrega</h4>
                            <p class="text-gray-800 flex items-start font-medium">
                                <i class="fas fa-map-marker-alt text-orange-600 mr-2 mt-1"></i>
                                <span>{{ $pedido->direccion_entrega }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información de Pago -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                        Información de Pago
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Estado de Pago</h4>
                            <span class="px-3 py-1.5 inline-flex text-sm font-semibold rounded-full
                                @if($pedido->estado_pago === 'pagado') bg-emerald-100 text-emerald-800
                                @elseif($pedido->estado_pago === 'pendiente') bg-amber-100 text-amber-800
                                @elseif($pedido->estado_pago === 'fallido') bg-red-100 text-red-800
                                @endif">
                                <i class="fas
                                    @if($pedido->estado_pago === 'pagado') fa-check-circle
                                    @elseif($pedido->estado_pago === 'pendiente') fa-clock
                                    @else fa-times-circle
                                    @endif mr-1"></i>
                                {{ ucfirst($pedido->estado_pago) }}
                            </span>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Método de Pago</h4>
                            <p class="text-gray-800 flex items-center font-medium">
                                <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                                {{ ucfirst(str_replace('_', ' ', $pedido->metodo_pago_preferido)) }}
                            </p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Fecha del Pedido</h4>
                            <p class="text-gray-800 flex items-center font-medium">
                                <i class="fas fa-calendar text-orange-600 mr-2"></i>
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                @if($pedido->notas)
                    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-amber-800 uppercase mb-2 flex items-center">
                            <i class="fas fa-sticky-note mr-2"></i>
                            Notas del Pedido
                        </h4>
                        <p class="text-gray-700 flex items-start">
                            <i class="fas fa-quote-left text-amber-400 mr-2 mt-1"></i>
                            <span class="italic">{{ $pedido->notas }}</span>
                        </p>
                    </div>
                @endif

                <!-- Productos del Pedido -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-shopping-bag text-orange-600 mr-2"></i>
                        Productos ({{ $pedido->detalles->count() }})
                    </h3>
                    <div class="space-y-3">
                        @foreach($pedido->detalles as $detalle)
                            @if($detalle->promocion_id)
                                {{-- Es una promoción --}}
                                <div class="bg-gradient-to-br from-orange-50 to-amber-100 rounded-xl border-2 border-orange-300 hover:shadow-md transition-shadow duration-200 overflow-hidden">
                                    <div class="flex items-center gap-4 p-4">
                                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-orange-200 shadow-md">
                                            @if($detalle->promocion->image_url)
                                                <img src="{{ $detalle->promocion->image_url }}"
                                                     alt="{{ $detalle->promocion->nombre }}"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            @endif
                                            <div class="w-full h-full flex items-center justify-center" style="{{ $detalle->promocion->image_url ? 'display: none;' : '' }}">
                                                <i class="fas fa-gift text-orange-500 text-2xl"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-800 text-lg">
                                                <i class="fas fa-gift text-orange-500 mr-2"></i>
                                                {{ $detalle->promocion->nombre }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <span class="inline-flex items-center bg-orange-100 text-orange-700 px-2 py-1 rounded-md font-semibold">
                                                    <i class="fas fa-times text-xs mr-1"></i>
                                                    {{ $detalle->cantidad }}
                                                </span>
                                                <span class="mx-2 text-gray-400">×</span>
                                                <span class="font-medium">${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                                            <p class="font-bold text-orange-600 text-xl">
                                                ${{ number_format($detalle->subtotal, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    {{-- Lista de productos incluidos en la promoción --}}
                                    @if($detalle->promocion && $detalle->promocion->productos && $detalle->promocion->productos->count() > 0)
                                        <div class="px-4 pb-4 border-t border-orange-200 mt-2 pt-3">
                                            <p class="text-xs font-semibold text-orange-700 uppercase mb-2 flex items-center">
                                                <i class="fas fa-list-ul mr-2"></i>
                                                Productos incluidos:
                                            </p>
                                            <div class="space-y-2">
                                                @foreach($detalle->promocion->productos as $producto)
                                                    <div class="flex items-center gap-2 text-sm bg-white/60 rounded-lg p-2">
                                                        <div class="w-8 h-8 rounded overflow-hidden flex-shrink-0 bg-gray-100">
                                                            @if($producto->image_url)
                                                                <img src="{{ $producto->image_url }}"
                                                                     alt="{{ $producto->nombre }}"
                                                                     class="w-full h-full object-cover"
                                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            @endif
                                                            <div class="w-full h-full flex items-center justify-center" style="{{ $producto->image_url ? 'display: none;' : '' }}">
                                                                <i class="fas fa-utensils text-gray-400 text-xs"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-gray-700 font-medium truncate">{{ $producto->nombre }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-1 text-orange-600 font-semibold">
                                                            <span class="text-xs">×</span>
                                                            <span>{{ $producto->pivot->cantidad }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                {{-- Es un producto --}}
                                <div class="flex items-center gap-4 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200 shadow-md">
                                        @if($detalle->producto->image_url)
                                            <img src="{{ $detalle->producto->image_url }}"
                                                 alt="{{ $detalle->producto->nombre }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        @endif
                                        <div class="w-full h-full flex items-center justify-center" style="{{ $detalle->producto->image_url ? 'display: none;' : '' }}">
                                            <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-800 text-lg">{{ $detalle->producto->nombre }}</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="inline-flex items-center bg-orange-100 text-orange-700 px-2 py-1 rounded-md font-semibold">
                                                <i class="fas fa-times text-xs mr-1"></i>
                                                {{ $detalle->cantidad }}
                                            </span>
                                            <span class="mx-2 text-gray-400">×</span>
                                            <span class="font-medium">${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                                        <p class="font-bold text-orange-600 text-xl">
                                            ${{ number_format($detalle->subtotal, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 shadow-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-orange-100 text-sm mb-1">Total del Pedido</p>
                            <p class="text-3xl font-bold text-white">${{ number_format($pedido->total, 2, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-receipt text-white text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer con Acciones -->
            <div class="border-t border-gray-200 p-6 bg-gradient-to-br from-gray-50 to-gray-100">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="closeDetailModal"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
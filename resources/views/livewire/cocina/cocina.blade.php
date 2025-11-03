<div class="p-6 space-y-6 bg-white/80 rounded-3xl w-full max-w-none">
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-amber-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">
                        <i class="fas fa-fire mr-3"></i>
                        Módulo Cocina
                    </h1>
                    <p class="text-orange-100">Panel de gestión de cocina - {{ auth()->user()->name }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <i class="fas fa-sync-alt text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje Flash -->
    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <!-- Kitchen Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-500 p-3 rounded-xl shadow-sm">
                    <i class="fas fa-utensils text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-blue-600 text-sm font-medium">En Preparación</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $stats['en_preparacion'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-500 p-3 rounded-xl shadow-sm">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-green-600 text-sm font-medium">Completados Hoy</p>
                    <p class="text-3xl font-bold text-green-700">{{ $stats['completados_hoy'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos En Preparación -->
    @if($pedidosEnPreparacion->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-utensils mr-3"></i>
                        En Preparación
                    </h3>
                    <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-bold">
                        {{ $pedidosEnPreparacion->count() }} pedidos
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($pedidosEnPreparacion as $pedido)
                        <div class="@if($pedido->es_urgente) bg-red-50 border-l-4 border-red-500 @else bg-blue-50 border-l-4 border-blue-500 @endif rounded-lg p-4 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold @if($pedido->es_urgente) text-red-800 @else text-blue-800 @endif">
                                    {{ $pedido->numero_pedido }}
                                </span>
                                <span class="text-xs @if($pedido->es_urgente) text-red-600 @else text-blue-600 @endif font-medium">
                                    {{ $pedido->tiempo_formateado }}
                                </span>
                            </div>

                            <div class="mb-3 space-y-1">
                                @foreach($pedido->detalles->take(3) as $detalle)
                                    <div class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-utensils text-gray-400 text-xs mr-2"></i>
                                        {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                                    </div>
                                @endforeach
                                @if($pedido->detalles->count() > 3)
                                    <div class="text-xs text-gray-500 italic">
                                        +{{ $pedido->detalles->count() - 3 }} productos más
                                    </div>
                                @endif
                            </div>

                            @if($pedido->notas)
                                <div class="mb-3 bg-amber-50 border border-amber-200 rounded-lg p-2">
                                    <p class="text-xs text-amber-800 font-medium">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        {{ Str::limit($pedido->notas, 50) }}
                                    </p>
                                </div>
                            @endif

                            <div class="flex gap-2">
                                <button
                                    wire:click="marcarComoListo({{ $pedido->id }})"
                                    class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-bold transition-all transform hover:scale-105 shadow-sm">
                                    <i class="fas fa-check mr-2"></i>Listo
                                </button>
                                <button
                                    wire:click="verDetalles({{ $pedido->id }})"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Vista Previa de Pedidos Listos -->
    @if($pedidosListos->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-green-100">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        Pedidos Listos
                    </h3>
                    <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-bold">
                        {{ $pedidosListos->count() }} recientes
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($pedidosListos as $pedido)
                        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-bold text-green-800">
                                    {{ $pedido->numero_pedido }}
                                </span>
                                <span class="text-xs bg-green-500 text-white px-3 py-1 rounded-full font-bold">
                                    LISTO
                                </span>
                            </div>

                            <div class="mb-2 text-xs text-gray-600">
                                <i class="fas fa-user text-gray-400 mr-1"></i>
                                {{ $pedido->user->name }}
                            </div>

                            <div class="space-y-1">
                                @foreach($pedido->detalles->take(2) as $detalle)
                                    <div class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-utensils text-gray-400 text-xs mr-2"></i>
                                        {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                                    </div>
                                @endforeach
                                @if($pedido->detalles->count() > 2)
                                    <div class="text-xs text-gray-500 italic">
                                        +{{ $pedido->detalles->count() - 2 }} productos más
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Empty State -->
    @if($pedidosPendientes->count() === 0 && $pedidosEnPreparacion->count() === 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="bg-gradient-to-br from-orange-100 to-amber-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-clipboard-check text-4xl text-orange-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Todo al día</h3>
                <p class="text-gray-600 mb-6">
                    No hay pedidos pendientes en este momento. Los nuevos pedidos aparecerán aquí automáticamente.
                </p>
                <div class="inline-flex items-center text-sm text-gray-500">
                    <i class="fas fa-sync-alt animate-spin mr-2"></i>
                    Actualizando en tiempo real
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Ver Detalles -->
    <x-modals.pedidos.detalle-pedido
        :show="$showDetailModal"
        :pedido="$pedidoSeleccionado" />

    <!-- Loading Overlays -->
    <x-loading-overlay target="iniciarPreparacion" message="Iniciando preparación..." />
    <x-loading-overlay target="marcarComoListo" message="Marcando como listo..." />
    <x-loading-overlay target="verDetalles" message="Cargando detalles..." />
    <x-loading-overlay target="closeDetailModal" message="Cerrando..." />
</div>

@push('scripts')
<script>    
    // Función para inicializar los listeners de Echo
    function inicializarEchoListeners() {
        if (!window.Echo) {
            return false;
        }
        
        const cocinaChannel = window.Echo.channel('cocina');
        
        cocinaChannel
            .listen('.pedido.creado', (e) => {
                console.log('Cocina - Nuevo pedido creado:', e);
                
                // Mostrar toast notification con énfasis para cocina
                if (window.showToast) {
                    const total = e.pedido.total ? parseFloat(e.pedido.total).toLocaleString('es-PY') : '0';
                    const itemsCount = e.pedido.items_count || 'varios';
                    
                    window.showToast(
                        `🔥 ¡NUEVO PEDIDO! ${e.pedido.numero_pedido} - ${itemsCount} items - Gs. ${total}`,
                        'warning',
                        6000
                    );
                    
                    // Reproducir sonido de alerta
                    if (window.playNotificationSound) {
                        window.playNotificationSound();
                    }
                    
                    // Incrementar badge
                    if (window.incrementBadge) {
                        window.incrementBadge();
                    }
                }
            })
            .listen('.pedido.cambio-estado', (e) => {
                console.log('Cocina - Estado de pedido cambió:', e);
                
                // Solo notificar cambios relevantes para cocina
                if (window.showToast && e.pedido && e.pedido.estado_nuevo) {
                    const estadosRelevantes = ['en_preparacion', 'listo'];
                    
                    if (estadosRelevantes.includes(e.pedido.estado_nuevo)) {
                        const mensajes = {
                            'en_preparacion': `Pedido ${e.pedido.numero_pedido} en preparación`,
                            'listo': `Pedido ${e.pedido.numero_pedido} listo para entregar`
                        };
                        
                        const tipo = e.pedido.estado_nuevo === 'listo' ? 'success' : 'info';
                        
                        window.showToast(
                            mensajes[e.pedido.estado_nuevo],
                            tipo,
                            5000
                        );
                        
                        // Reproducir sonido de alerta para cambios importantes
                        if (e.pedido.estado_nuevo === 'en_preparacion' && window.playNotificationSound) {
                            window.playNotificationSound();
                        }
                    }
                }
            })
            .listen('.pedido.cancelado', (e) => {
                console.log('Cocina - Pedido cancelado:', e);
                
                // Notificar cancelación
                if (window.showToast) {
                    window.showToast(
                        `⚠️ Pedido ${e.pedido.numero_pedido} CANCELADO - Detener preparación`,
                        'error',
                        5000
                    );
                    
                    // Reproducir sonido de alerta
                    if (window.playNotificationSound) {
                        window.playNotificationSound();
                    }
                }
            });
            
        return true;
    }
    
    // Intentar inicializar inmediatamente
    if (!inicializarEchoListeners()) {
        // Si no está disponible, esperar con un intervalo
        console.log('⏳ Esperando a que Echo esté disponible...');
        
        let intentos = 0;
        const maxIntentos = 50; // Máximo 5 segundos (50 * 100ms)
        
        const intervalo = setInterval(() => {
            intentos++;
            
            if (inicializarEchoListeners()) {
                console.log('✅ Echo listeners de cocina inicializados correctamente');
                clearInterval(intervalo);
            } else if (intentos >= maxIntentos) {
                console.error('❌ No se pudo inicializar Echo después de ' + maxIntentos + ' intentos');
                clearInterval(intervalo);
            }
        }, 100); // Verificar cada 100ms
    } else {
        console.log('✅ Echo listeners de cocina inicializados inmediatamente');
    }
</script>
@endpush
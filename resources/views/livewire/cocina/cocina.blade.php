<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Módulo Cocina</h1>
                <p class="text-gray-500">Panel de gestión de cocina</p>
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
                <!-- En Preparación -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">En Preparación</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['en_preparacion'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Completados Hoy -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Completados Hoy</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['completados_hoy'] }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Empty State -->
            @if($pedidosEnPreparacion->count() == 0)
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-100 rounded-full mb-6">
                        <i class="fas fa-clipboard-check text-orange-600 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Todo al día</h3>
                    <p class="text-gray-500 mb-6">
                        No hay pedidos en preparación en este momento. Los nuevos pedidos aparecerán aquí automáticamente.
                    </p>
                    <div class="inline-flex items-center text-sm text-gray-500">
                        <i class="fas fa-sync-alt animate-spin mr-2"></i>
                        Actualizando en tiempo real
                    </div>
                </div>
            @endif

            <!-- Pedidos En Preparación -->
            @if($pedidosEnPreparacion->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-t-2xl -m-6 mb-6">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pedidosEnPreparacion as $pedido)
                            <div wire:key="pedido-{{ $pedido->id }}"
                                 class="pedido-card @if($pedido->es_urgente) animate-pulse bg-red-50 border-l-4 border-red-500 @else bg-blue-50 border-l-4 border-blue-500 @endif rounded-lg p-4 shadow-lg hover:shadow-xl transition-shadow duration-300"
                                 data-pedido-id="{{ $pedido->id }}"
                                 data-updated-at="{{ $pedido->updated_at->timestamp }}">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="pedido-numero text-sm font-bold @if($pedido->es_urgente) text-red-800 @else text-blue-800 @endif">
                                        {{ $pedido->numero_pedido }}
                                    </span>
                                    <span class="pedido-tiempo text-xs @if($pedido->es_urgente) text-red-600 @else text-blue-600 @endif font-medium">
                                        {{ $pedido->tiempo_formateado }}
                                    </span>
                                </div>

                                <div class="mb-3 space-y-1">
                                    @foreach($pedido->detalles->take(3) as $detalle)
                                        <div class="text-sm text-gray-700 font-medium">
                                            @if($detalle->promocion_id)
                                                <i class="fas fa-gift text-orange-500 text-xs mr-2"></i>
                                                {{ $detalle->cantidad }}x {{ $detalle->promocion->nombre }}
                                            @else
                                                <i class="fas fa-utensils text-gray-400 text-xs mr-2"></i>
                                                {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                                            @endif
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
                                        class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-2 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <span wire:loading.remove wire:target="marcarComoListo({{ $pedido->id }})">
                                            <i class="fas fa-check mr-2"></i>Listo
                                        </span>
                                        <span wire:loading wire:target="marcarComoListo({{ $pedido->id }})">
                                            <svg class="animate-spin h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                    <button
                                        wire:click="verDetalles({{ $pedido->id }})"
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                                        title="Ver detalles">
                                        <span wire:loading.remove wire:target="verDetalles({{ $pedido->id }})">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <span wire:loading wire:target="verDetalles({{ $pedido->id }})">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Vista Previa de Pedidos Listos -->
            @if($pedidosListos->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-t-2xl -m-6 mb-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                Pedidos Completados Hoy
                            </h3>
                            <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-bold">
                                {{ $pedidosListos->count() }} pedidos
                            </span>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-3 lg:grid-cols-1 gap-4">
                        @foreach($pedidosListos as $pedido)
                            <div wire:key="pedido-listo-{{ $pedido->id }}"
                                 class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-lg hover:shadow-xl transition-shadow duration-300">
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
                                            @if($detalle->promocion_id)
                                                <i class="fas fa-gift text-orange-500 text-xs mr-2"></i>
                                                {{ $detalle->cantidad }}x {{ $detalle->promocion->nombre }}
                                            @else
                                                <i class="fas fa-utensils text-gray-400 text-xs mr-2"></i>
                                                {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                                            @endif
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
            @endif

        </main>
    </div>

    <!-- Modal Ver Detalles -->
    <x-modals.pedidos.detalle-pedido-cocina
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
                        `¡NUEVO PEDIDO! ${e.pedido.numero_pedido} - ${itemsCount} items - $ ${total}`,
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
    
    // Función para formatear tiempo transcurrido
    function formatearTiempo(segundos) {
        if (segundos < 60) {
            return segundos < 1 ? 'Hace menos de 1 min' : `Hace ${segundos} ${segundos === 1 ? 'segundo' : 'segundos'}`;
        }
        
        const minutos = Math.floor(segundos / 60);
        const segundosRestantes = segundos % 60;
        
        if (minutos < 60) {
            if (segundosRestantes === 0) {
                return `Hace ${minutos} ${minutos === 1 ? 'minuto' : 'minutos'}`;
            }
            return `Hace ${minutos} ${minutos === 1 ? 'minuto' : 'minutos'} y ${segundosRestantes} ${segundosRestantes === 1 ? 'segundo' : 'segundos'}`;
        }
        
        const horas = Math.floor(minutos / 60);
        const minutosRestantes = minutos % 60;
        
        if (horas < 24) {
            if (minutosRestantes === 0) {
                return `Hace ${horas} ${horas === 1 ? 'hora' : 'horas'}`;
            }
            return `Hace ${horas} ${horas === 1 ? 'hora' : 'horas'} y ${minutosRestantes} ${minutosRestantes === 1 ? 'minuto' : 'minutos'}`;
        }
        
        const dias = Math.floor(horas / 24);
        return `Hace ${dias} ${dias === 1 ? 'día' : 'días'}`;
    }
    
    // Función para actualizar estilos de pedidos en tiempo real
    function actualizarEstilosPedidos() {
        const pedidosCards = document.querySelectorAll('.pedido-card[data-updated-at]');
        const ahora = Math.floor(Date.now() / 1000); // Timestamp actual en segundos
        const umbralUrgencia = 60; // 1 minuto en segundos
        
        pedidosCards.forEach(card => {
            const updatedAt = parseInt(card.getAttribute('data-updated-at'));
            const segundosTranscurridos = ahora - updatedAt;
            
            const tiempoElement = card.querySelector('.pedido-tiempo');
            const numeroElement = card.querySelector('.pedido-numero');
            
            // Actualizar texto del tiempo
            if (tiempoElement) {
                tiempoElement.textContent = formatearTiempo(segundosTranscurridos);
            }
            
            // Determinar si es urgente (>= 60 segundos = 1 minuto)
            const esUrgente = segundosTranscurridos >= umbralUrgencia;
            
            // Actualizar clases CSS
            if (esUrgente) {
                // Aplicar estilos urgentes (rojo)
                card.classList.remove('bg-blue-50', 'border-blue-500');
                card.classList.add('bg-red-50', 'border-red-500', 'animate-pulse');
                
                if (tiempoElement) {
                    tiempoElement.classList.remove('text-blue-600');
                    tiempoElement.classList.add('text-red-600');
                }
                
                if (numeroElement) {
                    numeroElement.classList.remove('text-blue-800');
                    numeroElement.classList.add('text-red-800');
                }
            } else {
                // Aplicar estilos normales (azul)
                card.classList.remove('bg-red-50', 'border-red-500', 'animate-pulse');
                card.classList.add('bg-blue-50', 'border-blue-500');
                
                if (tiempoElement) {
                    tiempoElement.classList.remove('text-red-600');
                    tiempoElement.classList.add('text-blue-600');
                }
                
                if (numeroElement) {
                    numeroElement.classList.remove('text-red-800');
                    numeroElement.classList.add('text-blue-800');
                }
            }
        });
    }
    
    // Limpiar intervalos anteriores si existen (evita memory leaks cuando Livewire actualiza el DOM)
    if (window.cocinaIntervaloEstilos) {
        clearInterval(window.cocinaIntervaloEstilos);
        window.cocinaIntervaloEstilos = null;
    }
    if (window.cocinaIntervaloRefresh) {
        clearInterval(window.cocinaIntervaloRefresh);
        window.cocinaIntervaloRefresh = null;
    }
    
    // Ejecutar actualización inmediatamente al cargar
    actualizarEstilosPedidos();
    
    // Actualizar estilos cada segundo (1000ms) en tiempo real
    window.cocinaIntervaloEstilos = setInterval(actualizarEstilosPedidos, 1000);
    
    // Re-ejecutar cuando Livewire actualice el DOM
    // Usar un patrón de inicialización única para evitar múltiples hooks
    if (typeof Livewire !== 'undefined' && !window.cocinaMorphHookRegistered) {
        window.cocinaMorphHookRegistered = true;
        Livewire.hook('morph.updated', () => {
            // Pequeño delay para asegurar que el DOM esté completamente actualizado
            setTimeout(actualizarEstilosPedidos, 100);
        });
    }
    
    // Auto-refresh cada minuto (60 segundos) para actualizar urgencia de pedidos
    window.cocinaIntervaloRefresh = setInterval(() => {
        // Usar Livewire.visit si está disponible para un refresh más suave
        if (typeof Livewire !== 'undefined' && Livewire.visit) {
            Livewire.visit(window.location.href, { 
                method: 'get',
                preserveScroll: true 
            });
        } else {
            // Fallback a location.reload si Livewire no está disponible
            location.reload();
        }
    }, 60000); // 60000 ms = 60 segundos = 1 minuto
    
    // Limpiar intervalos cuando la página se descargue
    window.addEventListener('beforeunload', () => {
        if (window.cocinaIntervaloEstilos) {
            clearInterval(window.cocinaIntervaloEstilos);
        }
        if (window.cocinaIntervaloRefresh) {
            clearInterval(window.cocinaIntervaloRefresh);
        }
    });
</script>
@endpush
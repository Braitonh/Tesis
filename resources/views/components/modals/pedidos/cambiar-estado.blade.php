@props(['show' => false, 'pedido' => null, 'nuevoEstado' => '', 'nuevaDireccion' => '', 'nuevoTelefono' => '', 'nuevasNotas' => ''])

@if($show && $pedido)
    @php
        // Determinar qué campos son editables según el estado
        $estadosOrden = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado'];
        $estadoActualIndex = array_search($pedido->estado, $estadosOrden);
        $estadoListoIndex = array_search('listo', $estadosOrden);
        $estadoPreparacionIndex = array_search('en_preparacion', $estadosOrden);
        
        $puedeEditarDireccion = $estadoActualIndex !== false && $estadoActualIndex <= $estadoListoIndex;
        $puedeEditarNotas = $estadoActualIndex !== false && $estadoActualIndex <= $estadoPreparacionIndex;
    @endphp

    <!-- Backdrop -->
    <div wire:click="closeEstadoModal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
    </div>

    <!-- Modal Centrado -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-scale-in pointer-events-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold flex items-center">
                            <i class="fas fa-edit mr-3"></i>
                            Editar Pedido
                        </h2>
                        <p class="text-orange-100 text-sm mt-1">{{ $pedido->numero_pedido }} - {{ $pedido->user->name }}</p>
                    </div>
                    <button wire:click="closeEstadoModal" class="text-white hover:text-orange-100 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenido Scrolleable -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Información del Pedido -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border-l-4 border-orange-500">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-3 rounded-full">
                            <i class="fas fa-info-circle text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-orange-600 font-semibold">Estado Actual</p>
                            <p class="text-lg font-bold text-gray-800">{{ $pedido->estado_texto }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estado del Pedido - Siempre Editable -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 flex items-center">
                        <i class="fas fa-sync-alt text-orange-600 mr-2"></i>
                        Estado del Pedido
                        <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Siempre editable</span>
                    </label>
                    <select wire:model="nuevoEstado" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white">
                        <option value="pendiente">Pendiente</option>
                        <option value="en_preparacion">En preparación</option>
                        <option value="listo">Listo</option>
                        <option value="en_camino">En camino</option>
                        <option value="entregado">Entregado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    @error('nuevoEstado')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Puedes cambiar el estado del pedido en cualquier momento
                    </p>
                </div>

                <!-- Dirección de Entrega -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>
                            Dirección de Entrega
                        </span>
                        @if($puedeEditarDireccion)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Editable</span>
                        @else
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Bloqueado</span>
                        @endif
                    </label>
                    <textarea wire:model="nuevaDireccion" 
                              rows="2"
                              {{ !$puedeEditarDireccion ? 'disabled' : '' }}
                              class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 {{ $puedeEditarDireccion ? 'border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white' : 'border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed' }}"
                              placeholder="Ingresa la dirección de entrega"></textarea>
                    @error('nuevaDireccion')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    @if(!$puedeEditarDireccion)
                        <p class="text-xs text-amber-600 flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            No se puede editar la dirección cuando el pedido está en camino o entregado
                        </p>
                    @else
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Solo editable hasta que el pedido esté listo
                        </p>
                    @endif
                </div>

                <!-- Teléfono de Contacto -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-phone text-green-600 mr-2"></i>
                            Teléfono de Contacto
                        </span>
                        @if($puedeEditarDireccion)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Editable</span>
                        @else
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Bloqueado</span>
                        @endif
                    </label>
                    <input type="text" 
                           wire:model="nuevoTelefono"
                           {{ !$puedeEditarDireccion ? 'disabled' : '' }}
                           class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 {{ $puedeEditarDireccion ? 'border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white' : 'border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed' }}"
                           placeholder="Ej: +595 981 123 456">
                    @error('nuevoTelefono')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    @if(!$puedeEditarDireccion)
                        <p class="text-xs text-amber-600 flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            No se puede editar el teléfono cuando el pedido está en camino o entregado
                        </p>
                    @endif
                </div>

                <!-- Notas del Pedido -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-sticky-note text-purple-600 mr-2"></i>
                            Notas del Pedido
                        </span>
                        @if($puedeEditarNotas)
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Editable</span>
                        @else
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Bloqueado</span>
                        @endif
                    </label>
                    <textarea wire:model="nuevasNotas" 
                              rows="3"
                              {{ !$puedeEditarNotas ? 'disabled' : '' }}
                              class="w-full px-4 py-3 border-2 rounded-xl transition-all duration-200 {{ $puedeEditarNotas ? 'border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white' : 'border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed' }}"
                              placeholder="Instrucciones especiales, alergias, etc."></textarea>
                    @error('nuevasNotas')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    @if(!$puedeEditarNotas)
                        <p class="text-xs text-amber-600 flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            No se pueden editar las notas cuando el pedido está listo o en proceso de entrega
                        </p>
                    @else
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Solo editable hasta que el pedido esté en preparación
                        </p>
                    @endif
                </div>

                <!-- Advertencia de Cambios -->
                <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-orange-600 mt-0.5 mr-3"></i>
                        <div class="text-sm text-orange-700">
                            <p class="font-semibold mb-1">Información importante</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Los cambios se aplicarán inmediatamente</li>
                                <li>El cliente no será notificado automáticamente</li>
                                <li>Algunos campos se bloquean según el estado del pedido por seguridad</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer con Acciones -->
            <div class="border-t border-gray-200 p-6 bg-gradient-to-br from-gray-50 to-gray-100">
                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="closeEstadoModal"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    <button wire:click="actualizarPedido"
                            class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
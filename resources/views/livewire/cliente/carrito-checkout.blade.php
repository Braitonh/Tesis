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
                <h1 class="text-4xl font-bold text-gray-800">Checkout</h1>
                <p class="text-gray-600 mt-2">Completa tu pedido</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                <!-- Formulario de Checkout -->
                <div class="lg:col-span-2 flex">
                    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 flex-1 flex flex-col">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-clipboard-list text-orange-600 mr-2"></i>
                            Información de Entrega
                        </h2>

                        <form wire:submit="confirmarPedido" class="space-y-6">
                            <!-- Dirección de Entrega -->
                            <div>
                                <label for="direccion_entrega" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Dirección de Entrega
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           id="direccion_entrega"
                                           wire:model="direccion_entrega"
                                           placeholder="Ej: Av. Principal #123, entre Calle A y B"
                                           class="block w-full pl-10 pr-3 py-3 border @error('direccion_entrega') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                @error('direccion_entrega')
                                    <p class="mt-1 text-sm text-red-500">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Teléfono de Contacto -->
                            <div>
                                <label for="telefono_contacto" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Teléfono de Contacto
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="tel"
                                           id="telefono_contacto"
                                           wire:model="telefono_contacto"
                                           placeholder="Ej: 0981123456"
                                           class="block w-full pl-10 pr-3 py-3 border @error('telefono_contacto') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                @error('telefono_contacto')
                                    <p class="mt-1 text-sm text-red-500">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Notas Adicionales -->
                            <div>
                                <label for="notas" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Notas Adicionales
                                    <span class="text-gray-500 text-xs font-normal">(Opcional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 pointer-events-none">
                                        <i class="fas fa-comment-alt text-gray-400"></i>
                                    </div>
                                    <textarea id="notas"
                                              wire:model="notas"
                                              rows="4"
                                              placeholder="Ej: Sin cebolla, con extra queso, timbre roto..."
                                              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-orange-500 focus:border-orange-500 resize-none"></textarea>
                                </div>
                                @error('notas')
                                    <p class="mt-1 text-sm text-red-500">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Máximo 500 caracteres
                                </p>
                            </div>

                            <!-- Información Adicional -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-semibold mb-1">Información importante</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>El tiempo estimado de entrega es de 30-45 minutos</li>
                                            <li>Recibirás una confirmación de tu pedido</li>
                                            <li>Puedes pagar en efectivo al recibir tu pedido</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Message -->
                            @if (session()->has('error'))
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Resumen del Pedido -->
                <div class="lg:col-span-1 flex">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-4 flex-1 flex flex-col">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-shopping-bag text-orange-600 mr-2"></i>
                            Resumen del Pedido
                        </h2>

                        <!-- Items -->
                        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto flex-1">
                            @foreach($items as $item)
                                <div class="flex items-center gap-3 pb-3 border-b border-gray-100" wire:key="checkout-item-{{ $item->producto->id }}">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
                                        <img src="{{ $item->producto->image_url }}"
                                             alt="{{ $item->producto->nombre }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                            <i class="fas fa-utensils text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">
                                            {{ $item->producto->nombre }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->cantidad }} x ${{ number_format($item->precio, 2) }}
                                        </p>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-800 font-semibold">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Delivery</span>
                                <span class="text-green-600 font-semibold">Gratis</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold mt-4 pt-4 border-t border-gray-200">
                                <span class="text-gray-800">Total</span>
                                <span class="text-orange-600 text-2xl">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Botón Confirmar Pedido -->
                        <button type="submit"
                                wire:click="confirmarPedido"
                                wire:loading.attr="disabled"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span wire:loading.remove wire:target="confirmarPedido">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirmar Pedido
                            </span>
                            <span wire:loading wire:target="confirmarPedido">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Procesando...
                            </span>
                        </button>

                        <!-- Métodos de Pago -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 text-center mb-2">
                                <i class="fas fa-shield-alt text-green-600 mr-1"></i>
                                Pago seguro
                            </p>
                            <div class="flex justify-center items-center gap-2">
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-money-bill-wave text-green-600"></i> Efectivo
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
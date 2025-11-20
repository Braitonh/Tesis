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

                            <!-- Método de Pago -->
                            <div class="space-y-6" id="metodo-pago-container">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        Método de Pago
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="space-y-3">
                                        <!-- Efectivo -->
                                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition metodo-pago-option {{ $metodo_pago === 'efectivo' ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}"
                                               data-metodo="efectivo">
                                            <input type="radio"
                                                   name="metodo_pago"
                                                   value="efectivo"
                                                   wire:model.live="metodo_pago"
                                                   class="w-5 h-5 text-orange-600 focus:ring-orange-500 metodo-pago-radio">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-800">Efectivo</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Paga al recibir tu pedido</p>
                                            </div>
                                        </label>

                                        <!-- Tarjeta de Crédito -->
                                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition metodo-pago-option {{ $metodo_pago === 'tarjeta_credito' ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}"
                                               data-metodo="tarjeta_credito">
                                            <input type="radio"
                                                   name="metodo_pago"
                                                   value="tarjeta_credito"
                                                   wire:model.live="metodo_pago"
                                                   class="w-5 h-5 text-orange-600 focus:ring-orange-500 metodo-pago-radio">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-800">Tarjeta de Crédito</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Visa, Mastercard, American Express</p>
                                            </div>
                                        </label>

                                        <!-- Tarjeta de Débito -->
                                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition metodo-pago-option {{ $metodo_pago === 'tarjeta_debito' ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}"
                                               data-metodo="tarjeta_debito">
                                            <input type="radio"
                                                   name="metodo_pago"
                                                   value="tarjeta_debito"
                                                   wire:model.live="metodo_pago"
                                                   class="w-5 h-5 text-orange-600 focus:ring-orange-500 metodo-pago-radio">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-800">Tarjeta de Débito</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Débito instantáneo</p>
                                            </div>
                                        </label>

                                        <!-- Billetera Digital -->
                                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:bg-gray-50 transition metodo-pago-option {{ $metodo_pago === 'billetera_digital' ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}"
                                               data-metodo="billetera_digital">
                                            <input type="radio"
                                                   name="metodo_pago"
                                                   value="billetera_digital"
                                                   wire:model.live="metodo_pago"
                                                   class="w-5 h-5 text-orange-600 focus:ring-orange-500 metodo-pago-radio">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-wallet text-orange-600 mr-2"></i>
                                                    <span class="font-semibold text-gray-800">Billetera Digital</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">Zimple, Tigo Money, Personal Pay</p>
                                            </div>
                                        </label>
                                    </div>

                                    @error('metodo_pago')
                                        <p class="mt-2 text-sm text-red-500">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Formulario de Tarjeta (Condicional) -->
                            <div id="formulario-tarjeta"
                                 class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 space-y-4 {{ $metodo_pago === 'efectivo' ? 'hidden' : '' }}"
                                 style="transition: all 0.2s ease-out;">

                                <div class="flex items-center mb-4">
                                    <i class="fas fa-lock text-blue-600 mr-2"></i>
                                    <span class="text-sm font-semibold text-blue-800">Información de Pago Segura</span>
                                </div>

                                <!-- Número de Tarjeta -->
                                <div>
                                    <label for="numero_tarjeta" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Número de Tarjeta
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-credit-card text-gray-400"></i>
                                        </div>
                                        <input type="text"
                                               id="numero_tarjeta"
                                               wire:model="numero_tarjeta"
                                               placeholder="1234 5678 9012 3456"
                                               maxlength="19"
                                               class="block w-full pl-10 pr-3 py-3 border @error('numero_tarjeta') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-orange-500 focus:border-orange-500 bg-white">
                                    </div>
                                    @error('numero_tarjeta')
                                        <p class="mt-1 text-sm text-red-500">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Nombre en Tarjeta -->
                                <div>
                                    <label for="nombre_tarjeta" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nombre en la Tarjeta
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text"
                                               id="nombre_tarjeta"
                                               wire:model="nombre_tarjeta"
                                               placeholder="JUAN PÉREZ"
                                               class="block w-full pl-10 pr-3 py-3 border @error('nombre_tarjeta') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-orange-500 focus:border-orange-500 bg-white uppercase">
                                    </div>
                                    @error('nombre_tarjeta')
                                        <p class="mt-1 text-sm text-red-500">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Fecha y CVV -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="fecha_vencimiento" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Vencimiento
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                            <input type="text"
                                                   id="fecha_vencimiento"
                                                   wire:model="fecha_vencimiento"
                                                   placeholder="MM/AA"
                                                   maxlength="5"
                                                   class="block w-full pl-10 pr-3 py-3 border @error('fecha_vencimiento') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-orange-500 focus:border-orange-500 bg-white">
                                        </div>
                                        @error('fecha_vencimiento')
                                            <p class="mt-1 text-sm text-red-500">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="cvv" class="block text-sm font-semibold text-gray-700 mb-2">
                                            CVV
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                            <input type="password"
                                                   id="cvv"
                                                   wire:model="cvv"
                                                   placeholder="123"
                                                   maxlength="4"
                                                   class="block w-full pl-10 pr-3 py-3 border @error('cvv') border-red-500 @else border-gray-300 @enderror rounded-xl focus:ring-orange-500 focus:border-orange-500 bg-white">
                                        </div>
                                        @error('cvv')
                                            <p class="mt-1 text-sm text-red-500">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Iconos de Tarjetas -->
                                <div class="flex items-center justify-between pt-2 border-t border-blue-200">
                                    <div class="flex items-center gap-2">
                                        <i class="fab fa-cc-visa text-3xl text-blue-700"></i>
                                        <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                                        <i class="fab fa-cc-amex text-3xl text-blue-500"></i>
                                    </div>
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-shield-alt text-green-600"></i> Pago seguro SSL
                                    </span>
                                </div>
                            </div>
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
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-4 flex-1 flex flex-col max-h-[calc(100vh-2rem)]">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-shopping-bag text-orange-600 mr-2"></i>
                            Resumen del Pedido
                        </h2>

                        <!-- Items -->
                        <div class="space-y-3 mb-4 overflow-y-auto flex-1 min-h-0">
                            <!-- Promociones -->
                            @foreach($promociones as $item)
                                <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg p-3 border border-orange-200" wire:key="checkout-promo-{{ $item->promocion->id }}">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-orange-100">
                                            <img src="{{ $item->promocion->image_url }}"
                                                 alt="{{ $item->promocion->nombre }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                                <i class="fas fa-gift text-orange-400"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                <i class="fas fa-gift text-orange-500 mr-1"></i>
                                                {{ $item->promocion->nombre }}
                                            </p>
                                            <p class="text-xs text-gray-500 mb-1">
                                                {{ $item->cantidad }} x ${{ number_format($item->precio, 2, ',', '.') }}
                                            </p>
                                            <span class="inline-block bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                                -{{ $item->promocion->precio_descuento_porcentaje }}% OFF
                                            </span>
                                        </div>
                                        <p class="text-sm font-bold text-orange-600">
                                            ${{ number_format($item->subtotal, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Productos -->
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
                                            {{ $item->cantidad }} x ${{ number_format($item->precio, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">
                                        ${{ number_format($item->subtotal, 2, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4 mb-6 mt-auto">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-800 font-semibold">${{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Delivery</span>
                                <span class="text-green-600 font-semibold">Gratis</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold mt-4 pt-4 border-t border-gray-200">
                                <span class="text-gray-800">Total</span>
                                <span class="text-orange-600 text-2xl">${{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Botón Confirmar Pedido -->
                        <button type="submit"
                                wire:click="confirmarPedido"
                                wire:loading.attr="disabled"
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span wire:loading.remove wire:target="confirmarPedido">
                                <i class="fas fa-check-circle mr-2"></i>
                                @if($metodo_pago === 'efectivo')
                                    Confirmar Pedido
                                @else
                                    Proceder al Pago
                                @endif
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

    <!-- Modal de Error de Pago -->
    @if($mostrarModalError)
    <div class="fixed inset-0 z-50 overflow-y-auto animate-fade-in backdrop-blur-sm"
         x-data
         @scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })">

        <!-- Modal Container -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 md:p-8 animate-scale-in">

                <!-- Icono de Error -->
                <div class="flex justify-center mb-4">
                    @if($tipoError === 'rechazado')
                        <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-times-circle text-5xl text-red-600"></i>
                        </div>
                    @elseif($tipoError === 'fondos')
                        <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-wallet text-5xl text-orange-600"></i>
                        </div>
                    @elseif($tipoError === 'vencida')
                        <div class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-calendar-times text-5xl text-yellow-600"></i>
                        </div>
                    @elseif($tipoError === 'bloqueada')
                        <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-ban text-5xl text-red-600"></i>
                        </div>
                    @elseif($tipoError === 'invalido')
                        <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-5xl text-red-600"></i>
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-5xl text-red-600"></i>
                        </div>
                    @endif
                </div>

                <!-- Título -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">
                    Error al Procesar el Pago
                </h3>

                <!-- Mensaje de Error -->
                <p class="text-gray-600 text-center mb-6">
                    {{ $mensajeError }}
                </p>

                <!-- Información Adicional según el tipo de error -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-800">
                            @if($tipoError === 'rechazado')
                                <p class="font-semibold mb-1">¿Qué puedes hacer?</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Verifica que tu tarjeta esté activa</li>
                                    <li>Contacta a tu banco para más información</li>
                                    <li>Intenta con otro método de pago</li>
                                </ul>
                            @elseif($tipoError === 'fondos')
                                <p class="font-semibold mb-1">Fondos insuficientes</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Verifica el saldo disponible en tu tarjeta</li>
                                    <li>Intenta con otra tarjeta</li>
                                    <li>Puedes pagar en efectivo al recibir</li>
                                </ul>
                            @elseif($tipoError === 'vencida')
                                <p class="font-semibold mb-1">Tarjeta vencida</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Usa una tarjeta vigente</li>
                                    <li>Contacta a tu banco para renovarla</li>
                                    <li>Puedes pagar en efectivo</li>
                                </ul>
                            @elseif($tipoError === 'bloqueada')
                                <p class="font-semibold mb-1">Tarjeta bloqueada</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Contacta a tu banco inmediatamente</li>
                                    <li>Verifica si hay alguna alerta de seguridad</li>
                                    <li>Intenta con otro método de pago</li>
                                </ul>
                            @elseif($tipoError === 'invalido')
                                <p class="font-semibold mb-1">Número de tarjeta inválido</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Verifica que ingresaste el número correctamente</li>
                                    <li>Revisa que no falten dígitos</li>
                                    <li>Intenta nuevamente</li>
                                </ul>
                            @else
                                <p class="font-semibold mb-1">Sugerencias</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Verifica tus datos de pago</li>
                                    <li>Intenta nuevamente en unos momentos</li>
                                    <li>Puedes pagar en efectivo</li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="space-y-3">
                    <button wire:click="cerrarModalError"
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-redo mr-2"></i>
                        Intentar Nuevamente
                    </button>

                    <button onclick="window.location.href='{{ route('cliente.bienvenida') }}'"
                            class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Menú
                    </button>
                </div>

                <!-- Botón Cerrar (X) -->
                <button wire:click="cerrarModalError"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Script JavaScript puro para manejar método de pago -->
    <script>
        (function() {
            // Esperar a que el DOM esté listo
            document.addEventListener('DOMContentLoaded', function() {
                const radioButtons = document.querySelectorAll('.metodo-pago-radio');
                const formularioTarjeta = document.getElementById('formulario-tarjeta');
                
                // Función para actualizar estilos y visibilidad
                function actualizarMetodoPago() {
                    const radioSeleccionado = document.querySelector('.metodo-pago-radio:checked');
                    
                    if (!radioSeleccionado) return;
                    
                    const metodoPago = radioSeleccionado.value;
                    const todasOpciones = document.querySelectorAll('.metodo-pago-option');
                    
                    // Actualizar estilos de las opciones
                    todasOpciones.forEach(function(opcion) {
                        const opcionMetodo = opcion.getAttribute('data-metodo');
                        if (opcionMetodo === metodoPago) {
                            opcion.classList.remove('border-gray-300');
                            opcion.classList.add('border-orange-500', 'bg-orange-50');
                        } else {
                            opcion.classList.remove('border-orange-500', 'bg-orange-50');
                            opcion.classList.add('border-gray-300');
                        }
                    });
                    
                    // Mostrar/ocultar formulario de tarjeta
                    if (formularioTarjeta) {
                        if (metodoPago === 'efectivo') {
                            formularioTarjeta.classList.add('hidden');
                        } else {
                            formularioTarjeta.classList.remove('hidden');
                            
                            // Scroll automático al campo de número de tarjeta
                            setTimeout(function() {
                                const inputTarjeta = document.getElementById('numero_tarjeta');
                                if (inputTarjeta) {
                                    inputTarjeta.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                    inputTarjeta.focus();
                                }
                            }, 100);
                        }
                    }
                }
                
                // Escuchar cambios en los radio buttons
                radioButtons.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        // Pequeño delay para asegurar que Livewire haya actualizado el valor
                        setTimeout(actualizarMetodoPago, 50);
                    });
                });
                
                // Inicializar el estado al cargar la página
                actualizarMetodoPago();
                
                // Escuchar eventos de Livewire para actualizar después de las actualizaciones del servidor
                document.addEventListener('livewire:init', function() {
                    Livewire.hook('morph.updated', function({ el }) {
                        // Verificar si el contenedor de método de pago fue actualizado
                        const container = document.getElementById('metodo-pago-container');
                        if (container && container.contains(el)) {
                            setTimeout(actualizarMetodoPago, 50);
                        }
                    });
                });
            });
        })();
    </script>
</div>
<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4 animate-scale-in">
                    <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">¡Pedido Confirmado!</h1>
                <p class="text-xl text-gray-600">Tu pedido ha sido recibido y está siendo procesado</p>
            </div>

            <!-- Pedido Information Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-6">
                <!-- Número de Pedido -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm mb-1">Número de Pedido</p>
                            <p class="text-3xl font-bold">{{ $pedido->numero_pedido }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-orange-100 text-sm mb-1">Estado</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white/20">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $pedido->estado_texto }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información de Entrega -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 mb-2 uppercase">Dirección de Entrega</h3>
                        <p class="text-gray-800 flex items-start">
                            <i class="fas fa-map-marker-alt text-orange-600 mr-2 mt-1"></i>
                            <span>{{ $pedido->direccion_entrega }}</span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 mb-2 uppercase">Teléfono de Contacto</h3>
                        <p class="text-gray-800 flex items-center">
                            <i class="fas fa-phone text-orange-600 mr-2"></i>
                            {{ $pedido->telefono_contacto }}
                        </p>
                    </div>
                </div>

                @if($pedido->notas)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-gray-500 mb-2 uppercase">Notas del Pedido</h3>
                        <p class="text-gray-700 flex items-start">
                            <i class="fas fa-comment-alt text-gray-400 mr-2 mt-1"></i>
                            <span>{{ $pedido->notas }}</span>
                        </p>
                    </div>
                @endif

                <!-- Detalles del Pedido -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Detalles del Pedido</h3>
                    <div class="space-y-3">
                        @foreach($pedido->detalles as $detalle)
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
                                        <span class="inline-block bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold mt-1">
                                            -{{ $detalle->promocion->precio_descuento_porcentaje }}% OFF
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-orange-600">
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
                                    <p class="text-lg font-bold text-gray-800">
                                        ${{ number_format($detalle->subtotal, 2, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span class="text-gray-800">Total</span>
                        <span class="text-orange-600">${{ number_format($pedido->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Información de Pago en Efectivo -->
                @if($pedido->metodo_pago_preferido === 'efectivo' && $pedido->monto_recibido)
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Pago en Efectivo</h3>
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Monto Recibido:</span>
                                <span class="font-semibold text-gray-800">${{ number_format($pedido->monto_recibido, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total del Pedido:</span>
                                <span class="font-semibold text-gray-800">${{ number_format($pedido->total, 2, ',', '.') }}</span>
                            </div>
                            @if($pedido->vuelto > 0)
                            <div class="flex justify-between items-center pt-3 border-t border-green-300">
                                <span class="font-bold text-green-700">Vuelto a Devolver:</span>
                                <span class="text-2xl font-bold text-green-600">${{ number_format($pedido->vuelto, 2, ',', '.') }}</span>
                            </div>
                            @else
                            <div class="pt-3 border-t border-green-300 text-center">
                                <span class="font-semibold text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Pago exacto - No hay vuelto
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Información de Pago -->
                @if($pedido->transaccion)
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Pago</h3>

                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-600 text-2xl mr-3 mt-1"></i>
                            <div class="flex-1">
                                <p class="font-semibold text-green-900 mb-2">Pago Confirmado</p>
                                <div class="space-y-2 text-sm text-gray-700">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Método de pago:</span>
                                        <span class="font-medium">
                                            @switch($pedido->transaccion->metodo_pago)
                                                @case('efectivo')
                                                    <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>
                                                    Efectivo
                                                    @break
                                                @case('tarjeta_credito')
                                                    <i class="fas fa-credit-card text-blue-600 mr-1"></i>
                                                    Tarjeta de Crédito
                                                    @break
                                                @case('tarjeta_debito')
                                                    <i class="fas fa-credit-card text-purple-600 mr-1"></i>
                                                    Tarjeta de Débito
                                                    @break
                                                @case('billetera_digital')
                                                    <i class="fas fa-wallet text-orange-600 mr-1"></i>
                                                    Billetera Digital
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">N° Transacción:</span>
                                        <span class="font-mono font-medium">{{ $pedido->transaccion->numero_transaccion }}</span>
                                    </div>
                                    @if($pedido->transaccion->detalles_tarjeta)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tarjeta:</span>
                                            <span class="font-medium">
                                                {{ $pedido->transaccion->detalles_tarjeta['tipo'] ?? 'N/A' }}
                                                **** {{ $pedido->transaccion->detalles_tarjeta['ultimos_digitos'] ?? '' }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Estado:</span>
                                        <span class="font-medium text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ ucfirst($pedido->transaccion->estado) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fecha:</span>
                                        <span class="font-medium">
                                            {{ $pedido->transaccion->fecha_procesamiento ? $pedido->transaccion->fecha_procesamiento->format('d/m/Y H:i') : 'Pendiente' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Información Adicional -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 text-2xl mr-4 mt-1"></i>
                    <div>
                        <h3 class="font-bold text-blue-900 mb-2">¿Qué sigue?</h3>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-start">
                                <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                                <span>Tu pedido está siendo preparado en nuestra cocina</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-clock text-blue-600 mr-2 mt-1"></i>
                                <span>Tiempo estimado de entrega: <strong>30-45 minutos</strong></span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-motorcycle text-blue-600 mr-2 mt-1"></i>
                                <span>Te notificaremos cuando el pedido esté en camino</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-money-bill-wave text-blue-600 mr-2 mt-1"></i>
                                <span>Pago en efectivo al recibir tu pedido</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('cliente.bienvenida') }}"
                   class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 px-6 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-center">
                    <i class="fas fa-home mr-2"></i>
                    Volver al Inicio
                </a>
                <a href="{{ route('cliente.pedido.factura.pdf', $pedido->id) }}" target="_blank"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 px-6 rounded-xl font-bold transition-colors text-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Ver Factura PDF
                </a>
            </div>

            <!-- Mensaje de Agradecimiento -->
            <div class="text-center mt-8 p-6">
                <p class="text-gray-600 text-lg">
                    ¡Gracias por tu preferencia! <i class="fas fa-heart text-red-500"></i>
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Si tienes alguna duda, contáctanos al <strong>0981-123456</strong>
                </p>
            </div>
        </main>
    </div>
</div>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 via-white to-blue-50 px-4 py-12">
    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center max-w-md w-full" wire:poll.2s="verificarEstado">

        @if($estado === 'procesando')
            <!-- Estado: Procesando -->
            <div class="mb-8">
                <div class="relative inline-flex">
                    <div class="w-24 h-24 border-8 border-orange-200 border-t-orange-600 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-credit-card text-3xl text-orange-600"></i>
                    </div>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-4">Procesando Pago</h2>
            <p class="text-gray-600 mb-6">Por favor espera mientras verificamos tu pago con el banco...</p>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-center text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Esto puede tomar unos segundos</span>
                </div>
            </div>

        @elseif($estado === 'aprobado')
            <!-- Estado: Aprobado -->
            <div class="mb-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto animate-bounce">
                    <i class="fas fa-check-circle text-6xl text-green-600"></i>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-4">¡Pago Aprobado!</h2>
            <p class="text-gray-600 mb-6">Tu pedido ha sido confirmado exitosamente</p>

            @if($transaccion)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="text-sm text-gray-700 space-y-2">
                        <div class="flex justify-between">
                            <span class="font-semibold">N° Transacción:</span>
                            <span class="font-mono">{{ $transaccion->numero_transaccion }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Monto:</span>
                            <span class="text-green-600 font-bold">${{ number_format($transaccion->monto, 2, ',', '.') }}</span>
                        </div>
                        @if($transaccion->detalles_tarjeta)
                            <div class="flex justify-between">
                                <span class="font-semibold">Tarjeta:</span>
                                <span>{{ $transaccion->detalles_tarjeta['tipo'] ?? 'N/A' }} **** {{ $transaccion->detalles_tarjeta['ultimos_digitos'] ?? '' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <a href="{{ route('cliente.pedido.confirmacion', $transaccion->pedido_id) }}"
               class="inline-block w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-xl font-bold hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-receipt mr-2"></i>
                Ver mi Pedido
            </a>

            <a href="{{ route('cliente.bienvenida') }}"
               class="inline-block w-full mt-3 text-gray-600 hover:text-gray-800 font-medium">
                Volver al inicio
            </a>

        @elseif($estado === 'rechazado')
            <!-- Estado: Rechazado -->
            <div class="mb-8">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-times-circle text-6xl text-red-600"></i>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-4">Pago Rechazado</h2>
            <p class="text-gray-600 mb-4">Lo sentimos, no pudimos procesar tu pago</p>

            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-start text-left">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="text-sm text-red-800">
                        <p class="font-semibold mb-1">Motivo del rechazo:</p>
                        <p>{{ $mensaje_error ?: 'No se pudo completar la transacción' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('cliente.carrito.checkout') }}"
                   class="inline-block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 px-6 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-redo mr-2"></i>
                    Reintentar Pago
                </a>

                <a href="{{ route('cliente.bienvenida') }}"
                   class="inline-block w-full text-gray-600 hover:text-gray-800 font-medium">
                    Volver al inicio
                </a>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-xs text-blue-800">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <strong>Consejo:</strong> Verifica los datos de tu tarjeta o intenta con otro método de pago
                </p>
            </div>

        @else
            <!-- Estado: Error -->
            <div class="mb-8">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-question-circle text-6xl text-gray-400"></i>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-4">Error</h2>
            <p class="text-gray-600 mb-6">{{ $mensaje_error ?: 'Hubo un problema al procesar tu solicitud' }}</p>

            <a href="{{ route('cliente.bienvenida') }}"
               class="inline-block w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white py-4 px-6 rounded-xl font-bold hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-lg">
                <i class="fas fa-home mr-2"></i>
                Volver al inicio
            </a>
        @endif

    </div>
</div>
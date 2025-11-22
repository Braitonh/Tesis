@props(['show' => false, 'producto' => null])

@if($show && $producto)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Container -->
        <div class="bg-white rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.2)] overflow-hidden max-w-[1200px] w-full grid grid-cols-1 lg:grid-cols-2 relative mx-4" style="animation: slideUp 0.8s ease forwards;">

            <!-- Image Section -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-8 lg:p-12 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">

                <!-- Background decorations -->
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-400/20 rounded-full" style="animation: pulse 4s ease-in-out infinite;"></div>
                <div class="absolute -bottom-8 -left-8 w-36 h-36 bg-red-500/20 rounded-full" style="animation: pulse 4s ease-in-out infinite 2s;"></div>

                <div class="relative z-10 w-full">
                    <!-- Product Image -->
                    <div class="mb-6">
                        <div class="w-full h-80 lg:h-96 mx-auto rounded-2xl overflow-hidden shadow-2xl bg-white/10 backdrop-blur-sm">
                            <img
                                src="{{ $producto->image_url }}"
                                alt="{{ $producto->nombre }}"
                                class="w-full h-full object-cover"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <!-- Fallback icon -->
                            <div class="w-full h-full bg-white/20 flex items-center justify-center" style="display: none;">
                                @php
                                    $iconoCategoria = [
                                        'Hamburguesas' => 'fas fa-hamburger',
                                        'Pizzas' => 'fas fa-pizza-slice',
                                        'Bebidas' => 'fas fa-glass-whiskey',
                                        'Postres' => 'fas fa-ice-cream'
                                    ];
                                @endphp
                                <i class="{{ $iconoCategoria[$producto->categoria->nombre] ?? 'fas fa-utensils' }} text-6xl text-white/80"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="space-y-3">
                        @if($producto->destacado)
                            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-sm font-bold">
                                <i class="fas fa-star mr-2"></i>Destacado
                            </div>
                        @endif

                        @php
                            $estadoClasses = [
                                'disponible' => 'bg-green-500',
                                'stock_bajo' => 'bg-yellow-500',
                                'agotado' => 'bg-red-500'
                            ];
                            $estadoTexts = [
                                'disponible' => 'Disponible',
                                'stock_bajo' => 'Stock Bajo',
                                'agotado' => 'Agotado'
                            ];
                        @endphp
                        <div class="inline-flex items-center px-4 py-2 {{ $estadoClasses[$producto->estado] }} text-white rounded-full text-sm font-semibold">
                            {{ $estadoTexts[$producto->estado] }}
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mt-6">
                        <div class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm">
                            <i class="{{ $iconoCategoria[$producto->categoria->nombre] ?? 'fas fa-utensils' }} mr-2"></i>
                            {{ $producto->categoria->nombre }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8 lg:p-12 flex flex-col justify-center relative">

                <!-- Close button -->
                <button wire:click="closeDetailModal" class="absolute top-4 right-4 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Product Title -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $producto->nombre }}</h2>
                    <p class="text-gray-600">Detalles del producto</p>
                </div>

                <!-- Product Details -->
                <div class="space-y-6">

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-align-left text-orange-500 mr-2"></i>
                            Descripción
                        </h3>
                        <p class="text-gray-600 leading-relaxed">{{ $producto->descripcion }}</p>
                    </div>

                    <!-- Pricing -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-dollar-sign text-orange-500 mr-2"></i>
                            Precios
                        </h3>
                        <div class="space-y-2">
                            @if($producto->precio_descuento)
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl font-bold text-orange-600">${{ number_format($producto->precio_descuento, 2, ',', '.') }}</span>
                                    <span class="text-lg text-gray-400 line-through">${{ number_format($producto->precio, 2, ',', '.') }}</span>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">
                                        -{{ round((($producto->precio - $producto->precio_descuento) / $producto->precio) * 100) }}%
                                    </span>
                                </div>
                            @else
                                <span class="text-2xl font-bold text-orange-600">${{ number_format($producto->precio, 2, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-boxes text-orange-500 mr-2"></i>
                            Inventario
                        </h3>
                        <div class="flex items-center space-x-4">
                            <span class="text-xl font-semibold">{{ $producto->stock }} unidades</span>
                            @if($producto->stock > 50)
                                <span class="text-green-600 text-sm">Stock alto</span>
                            @elseif($producto->stock > 10)
                                <span class="text-yellow-600 text-sm">Stock medio</span>
                            @else
                                <span class="text-red-600 text-sm">Stock bajo</span>
                            @endif
                        </div>
                    </div>

                    <!-- Technical Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-cog text-orange-500 mr-2"></i>
                            Información Técnica
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Estado:</span>
                                <span class="ml-2 font-medium">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Creado:</span>
                                <span class="ml-2 font-medium">{{ $producto->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Actualizado:</span>
                                <span class="ml-2 font-medium">{{ $producto->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    </style>
@endif
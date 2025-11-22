@props([
    'promocion',
    'showActions' => true,
    'variant' => 'cliente'
])

@php
    $cardClass = $promocion->stock_disponible <= 0 ? 'opacity-75' : '';
    $precioClass = $promocion->stock_disponible <= 0 ? 'text-gray-400' : 'text-orange-600';
    $stockClass = $promocion->stock_disponible <= 5 ? 'text-red-600' : 'text-green-600';
@endphp

<div class="bg-white rounded-2xl shadow-lg overflow-hidden relative promocion-card {{ $cardClass }} flex flex-col h-full border-2 border-transparent hover:border-orange-300 transition-all duration-300 hover:shadow-2xl"
     wire:key="promocion-card-{{ $promocion->id }}"
     data-promocion-id="{{ $promocion->id }}">

    <!-- Imagen de la promoción -->
    <div class="h-56 bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center relative overflow-hidden">
        <img
            src="{{ $promocion->image_url }}"
            alt="{{ $promocion->nombre }}"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
            loading="lazy"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >

        <!-- Fallback icon si la imagen falla -->
        <div class="absolute inset-0 bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center" style="display: none;">
            <i class="fas fa-tags text-8xl text-orange-300"></i>
        </div>

        <!-- Badge de Destacada -->
        @if($promocion->destacado)
            <div class="absolute top-3 left-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg z-10 animate-pulse">
                <i class="fas fa-star mr-1"></i>Promoción Destacada
            </div>
        @endif

        <!-- Badge sin stock -->
        @if($promocion->stock_disponible <= 0)
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-20">
                <div class="bg-red-600 text-white px-6 py-3 rounded-full text-lg font-bold shadow-xl">
                    <i class="fas fa-times-circle mr-2"></i>Sin Stock
                </div>
            </div>
        @endif
    </div>

    <!-- Contenido -->
    <div class="p-6 flex flex-col flex-1">
        <!-- Nombre y etiqueta -->
        <div class="mb-3">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-gift text-orange-500"></i>
                <h3 class="text-xl font-bold text-gray-800">{{ $promocion->nombre }}</h3>
            </div>
            <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ $promocion->descripcion }}</p>
        </div>

        <!-- Productos incluidos -->
        <div class="mb-4 flex-1">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">
                <i class="fas fa-box-open mr-1"></i>Incluye:
            </p>
            <div class="flex flex-wrap gap-2">
                @foreach($promocion->productos->take(4) as $producto)
                    <span class="inline-flex items-center bg-orange-50 text-orange-700 px-3 py-1 rounded-full text-xs font-medium border border-orange-200">
                        <span class="bg-orange-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-1.5 font-bold">{{ $producto->pivot->cantidad }}</span>
                        {{ Str::limit($producto->nombre, 15) }}
                    </span>
                @endforeach
                @if($promocion->productos->count() > 4)
                    <span class="inline-flex items-center bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                        +{{ $promocion->productos->count() - 4 }} más
                    </span>
                @endif
            </div>
        </div>

        <!-- Precios -->
        <div class="mb-4 bg-gradient-to-r from-orange-50 to-amber-50 p-4 rounded-xl border-2 border-orange-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500">Precio normal:</span>
                <span class="text-lg text-gray-400 line-through font-semibold">
                    ${{ number_format($promocion->precio_original, 2, ',', '.') }}
                </span>
            </div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-base font-semibold text-gray-700">Precio promoción:</span>
                <span class="text-3xl font-black {{ $precioClass }}">
                    ${{ number_format($promocion->precio_final, 2, ',', '.') }}
                </span>
            </div>
            <div class="flex items-center justify-center pt-2 border-t border-orange-200">
                <div class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                    <i class="fas fa-piggy-bank mr-1"></i>
                    Ahorrás ${{ number_format($promocion->descuento_aplicado, 2, ',', '.') }}
                </div>
            </div>
        </div>



        <!-- Acciones -->
        @if($showActions && $variant === 'cliente')
            @if($promocion->stock_disponible > 0 && $promocion->activo)
                <button
                    wire:click="$dispatch('agregar-promocion-al-carrito', { promocionId: {{ $promocion->id }} })"
                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-cart-plus text-lg"></i>
                    <span>Agregar al Carrito</span>
                </button>
            @else
                <button
                    disabled
                    class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-6 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="fas fa-ban"></i>
                    <span>No Disponible</span>
                </button>
            @endif
        @endif
    </div>
</div>

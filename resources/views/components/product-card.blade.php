@props([
    'producto',
    'showActions' => true,
    'variant' => 'admin'
])

@php
    $estadoClasses = [
        'disponible' => 'bg-green-100 text-green-800',
        'stock_bajo' => 'bg-yellow-100 text-yellow-800',
        'agotado' => 'bg-red-100 text-red-800'
    ];

    $estadoTexts = [
        'disponible' => 'Disponible',
        'stock_bajo' => 'Stock Bajo',
        'agotado' => 'Agotado'
    ];

    $iconoCategoria = [
        'Hamburguesas' => 'fas fa-hamburger',
        'Pizzas' => 'fas fa-pizza-slice',
        'Bebidas' => 'fas fa-glass-whiskey',
        'Postres' => 'fas fa-ice-cream'
    ];

    $cardClass = $producto->estado === 'agotado' ? 'opacity-75' : '';
    $precioClass = $producto->estado === 'agotado' ? 'text-gray-400' : 'text-orange-600';
    $stockClass = match($producto->estado) {
        'disponible' => 'text-gray-500',
        'stock_bajo' => 'text-yellow-600',
        'agotado' => 'text-red-600',
        default => 'text-gray-500'
    };
@endphp

<div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden relative {{ $cardClass }}" wire:key="card-{{ $producto->id }}">
    <!-- Imagen del producto -->
    <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
        <img
            src="{{ $producto->image_url }}"
            alt="{{ $producto->nombre }}"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
            loading="lazy"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >

        <!-- Fallback icon si la imagen falla -->
        <div class="absolute inset-0 bg-gray-200 flex items-center justify-center" style="display: none;">
            <i class="{{ $iconoCategoria[$producto->categoria->nombre] ?? 'fas fa-utensils' }} text-6xl text-gray-400"></i>
        </div>

        @if($producto->destacado && $variant === 'cliente')
            <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold z-10">
                <i class="fas fa-star mr-1"></i>Destacado
            </div>
        @endif

    </div>

    <!-- Contenido -->
    <div class="p-6">
        <!-- Header con nombre y estado -->
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-800">{{ $producto->nombre }}</h3>
            <span class="px-2 py-1 text-xs rounded-full {{ $estadoClasses[$producto->estado] }}">
                {{ $estadoTexts[$producto->estado] }}
            </span>
        </div>

        <!-- Descripción -->
        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($producto->descripcion, 80) }}</p>

        <!-- Precio y Stock -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex flex-col">
                @if($producto->precio_descuento)
                    <span class="text-2xl font-bold {{ $precioClass }}">${{ number_format($producto->precio_descuento, 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">${{ number_format($producto->precio, 2) }}</span>
                @else
                    <span class="text-2xl font-bold {{ $precioClass }}">${{ number_format($producto->precio, 2) }}</span>
                @endif
            </div>
            <span class="text-sm {{ $stockClass }}">Stock: {{ $producto->stock }}</span>
        </div>

        <!-- Acciones -->
        @if($showActions)
            @if($variant === 'admin')
                <div class="flex space-x-2" wire:key="actions-{{ $producto->id }}">
                    <button
                        wire:click="editarProducto({{ $producto->id }})"
                        wire:key="edit-btn-{{ $producto->id }}"
                        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                    >
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </button>
                    <button
                        wire:click="verProducto({{ $producto->id }})"
                        wire:key="view-btn-{{ $producto->id }}"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                    >
                        <i class="fas fa-eye mr-1"></i>
                        Ver
                    </button>
                </div>
            @elseif($variant === 'cliente')
                <button
                    wire:click="agregarAlCarrito({{ $producto->id }})"
                    @if($producto->estado === 'agotado') disabled @endif
                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                >
                    @if($producto->estado === 'agotado')
                        <i class="fas fa-times mr-2"></i>Agotado
                    @else
                        <i class="fas fa-plus mr-2"></i>Agregar al Carrito
                    @endif
                </button>
            @endif
        @endif
    </div>

    <!-- Botón eliminar (solo para admin) -->
    @if($variant === 'admin' && $showActions)
        <button
            wire:click="confirmDeleteProducto({{ $producto->id }})"
            wire:key="delete-btn-{{ $producto->id }}"
            class="absolute top-1 right-1 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-all duration-200 opacity-80 hover:opacity-100 z-20"
            title="Eliminar producto"
        >
            <i class="fas fa-times text-sm"></i>
        </button>
    @endif


</div>
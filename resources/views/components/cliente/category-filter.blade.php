@props(['categorias', 'categoriaSeleccionada' => ''])

@php
    $iconoCategoria = [
        'Hamburguesas' => 'fas fa-hamburger',
        'Pizzas' => 'fas fa-pizza-slice',
        'Bebidas' => 'fas fa-coffee',
        'Postres' => 'fas fa-ice-cream'
    ];
@endphp

<div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 mb-8">
    <div class="flex flex-wrap gap-5 justify-center">
        <button
            wire:click="$set('categoriaSeleccionada', '')"
            class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105
                {{ $categoriaSeleccionada === '' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
        >
            <i class="fas fa-list mr-2"></i>Todos los Productos
            <span class="ml-2 {{ $categoriaSeleccionada === '' ? 'bg-white/20' : 'bg-gray-200' }} px-2 py-1 rounded-full text-xs">
                {{ $categorias->sum('productos_count') }}
            </span>
        </button>

        @foreach($categorias as $categoria)
            <button
                wire:click="$set('categoriaSeleccionada', '{{ $categoria->id }}')"
                class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105
                    {{ $categoriaSeleccionada == $categoria->id ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                <i class="{{ $iconoCategoria[$categoria->nombre] ?? 'fas fa-utensils' }} mr-2"></i>{{ $categoria->nombre }}
                <span class="ml-2 {{ $categoriaSeleccionada == $categoria->id ? 'bg-white/20' : 'bg-gray-200' }} px-2 py-1 rounded-full text-xs">
                    {{ $categoria->productos_count }}
                </span>
            </button>
        @endforeach
    </div>
</div>
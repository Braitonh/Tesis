<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <!-- Hero Section -->
            <x-cliente.hero-section :usuario="$usuario" />

            <!-- Features Cards -->
            <x-cliente.features-cards />

            <!-- Quick Actions -->
            <x-cliente.quick-actions />

            <!-- Productos Destacados -->
            <div id="destacados-section" class="mb-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">Productos Destacados</h2>
                    <p class="text-lg text-gray-600">Los favoritos de nuestros clientes</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @forelse($productosDestacados as $producto)
                        <x-product-card
                            :producto="$producto"
                            :show-actions="true"
                            variant="cliente"
                            wire:key="destacado-{{ $producto->id }}"
                        />
                    @empty
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No hay productos destacados en este momento</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Menu Section -->
            <div id="menu-section" class="mb-8">
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold text-gray-800 mb-3">Nuestro Delicioso Menú</h2>
                    <p class="text-xl text-gray-600">Platillos preparados con ingredientes frescos y mucho amor</p>
                </div>

                <!-- Filtros de Categorías -->
                <x-cliente.category-filter
                    :categorias="$categorias"
                    :categoriaSeleccionada="$categoriaSeleccionada"
                />

                <!-- Grid de Productos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="products-grid">
                    @forelse($productos as $producto)
                        <x-product-card
                            :producto="$producto"
                            :show-actions="true"
                            variant="cliente"
                            wire:key="producto-{{ $producto->id }}"
                        />
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="max-w-md mx-auto">
                                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay productos disponibles</h3>
                                <p class="text-gray-500 mb-6">
                                    @if($categoriaSeleccionada)
                                        No hay productos disponibles en esta categoría en este momento.
                                    @else
                                        No hay productos disponibles en este momento.
                                    @endif
                                </p>
                                @if($categoriaSeleccionada)
                                    <button
                                        wire:click="$set('categoriaSeleccionada', '')"
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                    >
                                        <i class="fas fa-list mr-2"></i>Ver Todos los Productos
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
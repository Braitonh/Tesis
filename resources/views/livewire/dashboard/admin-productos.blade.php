<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">Gestión de Productos</h1>
                        <p class="text-gray-500">Administra el catálogo de productos del restaurante</p>
                    </div>
                    <button
                        wire:click="crearProducto"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Producto
                    </button>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Productos -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-boxes text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Disponibles -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Disponibles</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['disponibles'] }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Stock Bajo -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Stock Bajo</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['stock_bajo'] }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-full">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Agotados -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Agotados</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['agotados'] }}</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                <select wire:model.live="filtroCategoria" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select wire:model.live="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Todos</option>
                    <option value="Disponible">Disponible</option>
                    <option value="Stock Bajo">Stock Bajo</option>
                    <option value="Agotado">Agotado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Destacado</label>
                <select wire:model.live="filtroDestacado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Todos</option>
                    <option value="1">Destacados</option>
                    <option value="0">No Destacados</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="busqueda"
                    placeholder="Nombre del producto..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                >
            </div>
            <div class="flex items-end space-x-2">
                <button
                    wire:click="limpiarFiltros"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-times mr-2"></i>
                    Limpiar
                </button>
            </div>
        </div>

                <!-- Filtros activos -->
                @if($filtroCategoria || $filtroEstado || $filtroDestacado !== '' || $busqueda)
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                        <span class="text-sm text-gray-600 font-semibold">Filtros activos:</span>

                        @if($filtroCategoria)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ $filtroCategoria }}
                                <button wire:click="$set('filtroCategoria', '')" class="ml-1 text-orange-600 hover:text-orange-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($filtroEstado)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $filtroEstado }}
                                <button wire:click="$set('filtroEstado', '')" class="ml-1 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($filtroDestacado !== '')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $filtroDestacado === '1' ? 'Destacados' : 'No Destacados' }}
                                <button wire:click="$set('filtroDestacado', '')" class="ml-1 text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($busqueda)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                "{{ $busqueda }}"
                                <button wire:click="$set('busqueda', '')" class="ml-1 text-green-600 hover:text-green-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Mensajes de estado -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Hidden input for triggering reorder -->
            <input type="hidden" wire:model.live="productOrderJson" id="product-order-input">

            <!-- Spinner component for reordering -->
            <x-drag-drop-spinner
                id="reorder-spinner"
                title="Actualizando orden de productos"
                message="Por favor espera un momento..."
            />

            <!-- Grid de Productos -->
            <div class="space-y-4 bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:key="productos-grid" id="products-grid">
                    @forelse($productos as $producto)
                        <x-product-card
                            :producto="$producto"
                            :show-actions="true"
                            variant="admin"
                            wire:key="producto-{{ $producto->id }}"
                        />
                    @empty
                        <!-- Estado Vacío -->
                        <div class="col-span-full">
                            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                                    <i class="fas fa-box-open text-gray-400 text-5xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">No se encontraron productos</h3>
                                <p class="text-gray-500 mb-6">
                                    @if($filtroCategoria || $filtroEstado || $filtroDestacado !== '' || $busqueda)
                                        No hay productos que coincidan con los filtros aplicados.
                                    @else
                                        Aún no hay productos registrados en el sistema.
                                    @endif
                                </p>
                                @if($filtroCategoria || $filtroEstado || $filtroDestacado !== '' || $busqueda)
                                    <button
                                        wire:click="limpiarFiltros"
                                        class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl"
                                    >
                                        <i class="fas fa-times mr-2"></i>
                                        Limpiar Filtros
                                    </button>
                                @else
                                    <button
                                        wire:click="crearProducto"
                                        class="inline-flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <i class="fas fa-plus mr-2"></i>
                                        Agregar Primer Producto
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- Product Edit Modal -->
    <x-modals.productos.form-producto
        :productoId="$productoId"
        :modalTitle="$modalTitle"
        :showModal="$showEditModal"
        :categorias="$categorias"
    />

    <!-- Product Detail Modal -->
    <x-modals.productos.detalle-producto
        :show="$showDetailModal"
        :producto="$productoToView"
    />

    <!-- Delete Confirmation Modal -->
    <x-modals.delete-confirmation
        :show="$showDeleteModal"
        :item="$productoToDelete"
        title="Confirmar Eliminación de Producto"
        :message="$productoToDelete ? '¿Estás seguro de que quieres eliminar <strong>' . $productoToDelete->nombre . '</strong>?' : ''"
        onCancel="closeDeleteModal"
        onConfirm="eliminarProducto"
    />

    <!-- Loading Overlays -->
    <x-loading-overlay target="crearProducto" message="Preparando formulario..." />
    <x-loading-overlay target="editarProducto" message="Cargando datos del producto..." />
    <x-loading-overlay target="saveProducto" message="Guardando producto..." />
    <x-loading-overlay target="verProducto" message="Cargando detalles del producto..." />
    <x-loading-overlay target="confirmDeleteProducto" message="Preparando eliminación..." />
    <x-loading-overlay target="eliminarProducto" message="Eliminando producto..." />
    <x-loading-overlay target="closeDeleteModal" message="Cerrando..." />
    <x-loading-overlay target="closeDetailModal" message="Cerrando..." />
    <x-loading-overlay target="closeEditModal" message="Cerrando..." />

    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <!-- Include CSS and JS assets -->
    @vite(['resources/css/sortable-styles.css', 'resources/js/sortable-helper.js'])

    <!-- Initialize drag and drop -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeProductsSortable();
        });

        function initializeProductsSortable() {
            // Configuración para el drag and drop de productos
            const sortableConfig = {
                spinnerId: 'reorder-spinner',
                onReorder: 'updateProductOrder',
                idSelectors: ['data-product-id', 'wire:key']
            };

            // Inicializar sortable usando el helper
            window.SortableHelper.initSortable('products-grid', sortableConfig);

            // Configurar listeners de Livewire
            window.SortableHelper.setupLivewireListeners({
                spinnerId: 'reorder-spinner',
                reinitCallbacks: [initializeProductsSortable]
            });
        }
    </script>
</div>
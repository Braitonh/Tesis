<div class="p-6 space-y-6 bg-white/80  rounded-3xl w-full max-w-none">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-boxes text-orange-600 mr-2"></i>
                    Gestión de Productos
                </h2>
                <p class="text-gray-600 mt-1">Administra el catálogo de productos del restaurante</p>
            </div>
            <button
                wire:click="crearProducto"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Agregar Producto
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-boxes text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Productos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Disponibles</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['disponibles'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Stock Bajo</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['stock_bajo'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Agotados</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['agotados'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="text-sm text-gray-600">Filtros activos:</span>

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
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
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


    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:key="productos-grid" id="products-grid">
        @forelse($productos as $producto)
            <x-product-card
                :producto="$producto"
                :show-actions="true"
                variant="admin"
                wire:key="producto-{{ $producto->id }}"
            />
        @empty
            <div class="col-span-full text-center py-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron productos</h3>
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
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Limpiar Filtros
                        </button>
                    @else
                        <button
                            wire:click="crearProducto"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Primer Producto
                        </button>
                    @endif
                </div>
            </div>
        @endforelse
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
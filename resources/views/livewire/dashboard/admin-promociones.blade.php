<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">Gestión de Promociones</h1>
                        <p class="text-gray-500">Administra las promociones y ofertas especiales</p>
                    </div>
                    <button
                        wire:click="crearPromocion"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Promoción
                    </button>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Promociones -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-tags text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Activas -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Activas</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['activas'] }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Destacadas -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Destacadas</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['destacadas'] }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-full">
                            <i class="fas fa-star text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Inactivas -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-gray-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Inactivas</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['inactivas'] }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-full">
                            <i class="fas fa-pause-circle text-gray-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input
                            type="text"
                            wire:model.live="busqueda"
                            placeholder="Buscar por nombre o descripción..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destacado</label>
                        <select wire:model.live="filtroDestacado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Todas</option>
                            <option value="1">Destacadas</option>
                            <option value="0">No destacadas</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button
                        wire:click="limpiarFiltros"
                        class="text-sm text-gray-600 hover:text-orange-600 transition-colors duration-200">
                        <i class="fas fa-times-circle mr-1"></i>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <!-- Grid de Promociones -->
            <div id="promociones-container" class="bg-white rounded-2xl shadow-lg p-6 mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($promociones as $promocion)
                    <div
                        data-promocion-id="{{ $promocion->id }}"
                        class="promocion-item bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2 border-transparent hover:border-orange-300">

                        <!-- Imagen -->
                        <div class="relative h-48 bg-gray-200">
                            <img
                                src="{{ $promocion->image_url }}"
                                alt="{{ $promocion->nombre }}"
                                class="w-full h-full object-cover {{ $promocion->stock_disponible <= 0 ? 'opacity-50' : '' }}"
                            />
                            <!-- Descuento Badge -->
                            <div class="absolute top-3 right-3">
                                <div class="bg-red-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                    {{ $promocion->precio_descuento_porcentaje }}% OFF
                                </div>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $promocion->nombre }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700 px-3 py-1 rounded-full {{ $promocion->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $promocion->activo ? 'Activa' : 'Inactiva' }}</span>
                                    <span class="text-sm font-medium text-gray-700 px-3 py-1 rounded-full {{ $promocion->destacado ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $promocion->destacado ? 'Destacada' : 'No destacada' }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $promocion->descripcion }}</p>

                            <!-- Productos incluidos -->
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 font-semibold mb-2">Incluye:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($promocion->productos->take(3) as $producto)
                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                            {{ $producto->pivot->cantidad }}x {{ $producto->nombre }}
                                        </span>
                                    @endforeach
                                    @if($promocion->productos->count() > 3)
                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                            +{{ $promocion->productos->count() - 3 }} más
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Precios -->
                            <div class="mb-4">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($promocion->precio_original, 2, ',', '.') }}</span>
                                    <span class="text-2xl font-bold text-orange-600">${{ number_format($promocion->precio_final, 2, ',', '.') }}</span>
                                </div>
                                <p class="text-xs text-green-600 font-semibold">
                                    Ahorras ${{ number_format($promocion->descuento_aplicado, 2, ',', '.') }}
                                </p>
                            </div>

                            <!-- Stock -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between p-3 rounded-lg {{ $promocion->stock_disponible <= 0 ? 'bg-red-50 border-2 border-red-200' : ($promocion->stock_disponible <= 5 ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-green-50 border-2 border-green-200') }}">
                                    <div class="flex items-center gap-2">
                                        @if($promocion->stock_disponible <= 0)
                                            <i class="fas fa-times-circle text-red-600 text-lg"></i>
                                            <span class="text-sm font-semibold text-red-600">Sin Stock</span>
                                        @elseif($promocion->stock_disponible <= 5)
                                            <i class="fas fa-exclamation-triangle text-yellow-600 text-lg"></i>
                                            <span class="text-sm font-semibold text-yellow-600">Stock Bajo</span>
                                        @else
                                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                            <span class="text-sm font-semibold text-green-600">Stock Disponible</span>
                                        @endif
                                    </div>
                                    <span class="text-lg font-bold {{ $promocion->stock_disponible <= 0 ? 'text-red-600' : ($promocion->stock_disponible <= 5 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $promocion->stock_disponible }} unidades
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex gap-2">
                                <button
                                    wire:click="verPromocion({{ $promocion->id }})"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </button>
                                <button
                                    wire:click="editarPromocion({{ $promocion->id }})"
                                    class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </button>
                                <button
                                    wire:click="confirmDeletePromocion({{ $promocion->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay promociones</h3>
                            <p class="text-gray-500 mb-6">
                                @if($busqueda || $filtroDestacado !== '')
                                    No se encontraron promociones con los filtros aplicados.
                                @else
                                    Comienza creando tu primera promoción.
                                @endif
                            </p>
                            @if($busqueda || $filtroDestacado !== '')
                                <button
                                    wire:click="limpiarFiltros"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-times-circle mr-2"></i>Limpiar Filtros
                                </button>
                            @else
                                <button
                                    wire:click="crearPromocion"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Crear Promoción
                                </button>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <!-- Modal de Edición/Creación -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $modalTitle }}</h2>
                        <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex gap-4 mt-4">
                        <button
                            wire:click="cambiarTab('info')"
                            class="pb-2 px-1 border-b-2 font-medium transition-colors {{ $activeTab === 'info' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            <i class="fas fa-info-circle mr-2"></i>Información
                        </button>
                        <button
                            wire:click="cambiarTab('productos')"
                            class="pb-2 px-1 border-b-2 font-medium transition-colors {{ $activeTab === 'productos' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            <i class="fas fa-box mr-2"></i>Productos ({{ count($selectedProductos) }})
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <!-- Tab: Información -->
                    @if($activeTab === 'info')
                    <div class="space-y-4">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                            <input
                                type="text"
                                wire:model="nombre"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Ej: Combo Familiar"
                            />
                            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                            <textarea
                                wire:model="descripcion"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Describe la promoción..."></textarea>
                            @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Descuento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descuento (%) *</label>
                            <input
                                type="number"
                                wire:model="precio_descuento_porcentaje"
                                min="0"
                                max="100"
                                step="0.01"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                                placeholder="10"
                            />
                            @error('precio_descuento_porcentaje') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Imagen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen</label>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <input
                                        type="file"
                                        wire:model="imagen_file"
                                        accept="image/*"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                                    />
                                    @error('imagen_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @if($imagen_file)
                                <div class="mt-2">
                                    <img src="{{ $imagen_file->temporaryUrl() }}" class="h-32 w-32 object-cover rounded-lg" />
                                </div>
                            @elseif($imagen)
                                <div class="mt-2">
                                    <img src="{{ is_string($imagen) && filter_var($imagen, FILTER_VALIDATE_URL) ? $imagen : Storage::url($imagen) }}" class="h-32 w-32 object-cover rounded-lg" />
                                </div>
                            @endif
                        </div>

                        <!-- Checkboxes -->
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="destacado" class="rounded text-orange-600 focus:ring-orange-500" />
                                <span class="text-sm font-medium text-gray-700">Destacada</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="activo" class="rounded text-orange-600 focus:ring-orange-500" />
                                <span class="text-sm font-medium text-gray-700">Activa</span>
                            </label>
                        </div>
                    </div>
                    @endif

                    <!-- Tab: Productos -->
                    @if($activeTab === 'productos')
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600 mb-4">Selecciona los productos que incluirá esta promoción y especifica las cantidades.</p>
                        @error('selectedProductos') <span class="text-red-500 text-sm block mb-2">{{ $message }}</span> @enderror

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                            @foreach($productosDisponibles as $producto)
                                <div
                                    wire:click="toggleProductoSelection({{ $producto->id }})"
                                    class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ in_array($producto->id, $selectedProductos) ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-300' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start gap-2">
                                                <img src="{{ $producto->image_url }}" class="w-12 h-12 object-cover rounded" />
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-800 text-sm">{{ $producto->nombre }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $producto->categoria->nombre }}</p>
                                                    <p class="text-sm text-orange-600 font-semibold">${{ number_format($producto->precio_final, 2, ',', '.') }}</p>
                                                </div>
                                            </div>

                                            @if(in_array($producto->id, $selectedProductos))
                                                <div class="mt-2" onclick="event.stopPropagation()">
                                                    <label class="text-xs text-gray-600">Cantidad:</label>
                                                    <input
                                                        type="number"
                                                        wire:model="productoCantidades.{{ $producto->id }}"
                                                        min="1"
                                                        max="{{ $producto->stock }}"
                                                        class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-orange-500 focus:border-orange-500"
                                                    />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl">
                    <div class="flex justify-end gap-3">
                        <button
                            wire:click="closeEditModal"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                            Cancelar
                        </button>
                        <button
                            wire:click="savePromocion"
                            id="btn-guardar-promocion"
                            class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-save mr-2"></i>Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Detalle -->
    @if($showDetailModal && $promocionToView)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-800">Detalle de Promoción</h2>
                        <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6">
                    <!-- Imagen -->
                    <div class="text-center">
                        <img src="{{ $promocionToView->image_url }}" class="w-full h-64 object-cover rounded-lg" />
                    </div>

                    <!-- Información básica -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $promocionToView->nombre }}</h3>
                        <p class="text-gray-600">{{ $promocionToView->descripcion }}</p>
                    </div>

                    <!-- Estados -->
                    <div class="flex gap-2">
                        @if($promocionToView->destacado)
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-star mr-1"></i>Destacada
                            </span>
                        @endif
                        <span class="{{ $promocionToView->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $promocionToView->activo ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    <!-- Precios -->
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Precio original:</span>
                            <span class="text-lg font-semibold text-gray-400 line-through">${{ number_format($promocionToView->precio_original, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Descuento:</span>
                            <span class="text-lg font-semibold text-orange-600">{{ $promocionToView->precio_descuento_porcentaje }}%</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-orange-200">
                            <span class="text-gray-800 font-semibold">Precio final:</span>
                            <span class="text-2xl font-bold text-orange-600">${{ number_format($promocionToView->precio_final, 2, ',', '.') }}</span>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-sm text-green-600 font-semibold">
                                Ahorras ${{ number_format($promocionToView->descuento_aplicado, 2, ',', '.') }}
                            </span> 
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="p-4 rounded-lg {{ $promocionToView->stock_disponible <= 0 ? 'bg-red-50 border-2 border-red-200' : ($promocionToView->stock_disponible <= 5 ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-green-50 border-2 border-green-200') }}">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                @if($promocionToView->stock_disponible <= 0)
                                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                    <span class="text-gray-700 font-semibold">Stock disponible:</span>
                                @elseif($promocionToView->stock_disponible <= 5)
                                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                                    <span class="text-gray-700 font-semibold">Stock disponible:</span>
                                @else
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                    <span class="text-gray-700 font-semibold">Stock disponible:</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold {{ $promocionToView->stock_disponible <= 0 ? 'text-red-600' : ($promocionToView->stock_disponible <= 5 ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $promocionToView->stock_disponible }} unidades
                                </span>
                                @if($promocionToView->stock_disponible <= 0)
                                    <p class="text-xs text-red-600 font-medium mt-1">No disponible</p>
                                @elseif($promocionToView->stock_disponible <= 5)
                                    <p class="text-xs text-yellow-600 font-medium mt-1">Stock bajo - Reabastecer pronto</p>
                                @else
                                    <p class="text-xs text-green-600 font-medium mt-1">Disponible</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Productos incluidos -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">Productos incluidos:</h4>
                        <div class="space-y-3">
                            @foreach($promocionToView->productos as $producto)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <img src="{{ $producto->image_url }}" class="w-16 h-16 object-cover rounded" />
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-800">{{ $producto->nombre }}</h5>
                                        <p class="text-sm text-gray-500">{{ $producto->categoria->nombre }}</p>
                                        <p class="text-sm text-orange-600 font-semibold">${{ number_format($producto->precio_final, 2, ',', '.') }} c/u</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-gray-800">x{{ $producto->pivot->cantidad }}</span>
                                        <p class="text-xs text-gray-500">Stock: {{ $producto->stock }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl">
                    <div class="flex justify-end">
                        <button
                            wire:click="closeDetailModal"
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Confirmación de Eliminación -->
    @if($showDeleteModal && $promocionToDelete)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar promoción?</h3>
                        <p class="text-gray-600">
                            ¿Estás seguro de que deseas eliminar la promoción "<strong>{{ $promocionToDelete->nombre }}</strong>"? Esta acción no se puede deshacer.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            wire:click="closeDeleteModal"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                            Cancelar
                        </button>
                        <button
                            wire:click="eliminarPromocion"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlays -->
    <x-loading-overlay target="crearPromocion" message="Preparando formulario..." />
    <x-loading-overlay target="editarPromocion" message="Cargando datos de la promoción..." />
    <x-loading-overlay target="savePromocion" message="Guardando promoción..." />
    <x-loading-overlay target="verPromocion" message="Cargando detalles de la promoción..." />
    <x-loading-overlay target="confirmDeletePromocion" message="Preparando eliminación..." />
    <x-loading-overlay target="eliminarPromocion" message="Eliminando promoción..." />
    <x-loading-overlay target="closeDeleteModal" message="Cerrando..." />
    <x-loading-overlay target="closeDetailModal" message="Cerrando..." />
    <x-loading-overlay target="closeEditModal" message="Cerrando..." />

    <!-- Data attributes para mensajes flash -->
    @if (session()->has('message'))
        <div data-flash-message="{{ session('message') }}" data-flash-type="success" class="hidden"></div>
    @endif

    @if (session()->has('error'))
        <div data-flash-message="{{ session('error') }}" data-flash-type="error" class="hidden"></div>
    @endif
</div>

@push('scripts')
<script>
    (function() {
        // Función para mostrar mensajes flash como toasts
        function mostrarMensajesFlash() {
            const mensajes = document.querySelectorAll('[data-flash-message]');
            
            mensajes.forEach(function(elemento) {
                const mensaje = elemento.getAttribute('data-flash-message');
                const tipo = elemento.getAttribute('data-flash-type') || 'info';
                
                if (mensaje && window.showToast) {
                    window.showToast(mensaje, tipo, 5000);
                    
                    // Remover el elemento después de mostrar el toast
                    elemento.remove();
                }
            });
        }
        
        // Función para inicializar el observer y listeners
        function inicializar() {
            // Mostrar mensajes existentes
            mostrarMensajesFlash();
            
            // Usar MutationObserver para detectar cuando se agregan nuevos mensajes flash
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            // Verificar si el nodo agregado tiene el atributo data-flash-message
                            if (node.hasAttribute && node.hasAttribute('data-flash-message')) {
                                setTimeout(function() {
                                    mostrarMensajesFlash();
                                }, 100);
                            }
                            // También verificar si contiene elementos con data-flash-message
                            const mensajesInternos = node.querySelectorAll ? node.querySelectorAll('[data-flash-message]') : [];
                            if (mensajesInternos.length > 0) {
                                setTimeout(function() {
                                    mostrarMensajesFlash();
                                }, 100);
                            }
                        }
                    });
                });
            });
            
            // Observar el contenedor principal del componente
            const contenedor = document.querySelector('div[wire\\:id]');
            if (contenedor) {
                observer.observe(contenedor, {
                    childList: true,
                    subtree: true
                });
            }
            
            // También observar el body como fallback
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
        
        // Inicializar cuando el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(inicializar, 200);
            });
        } else {
            setTimeout(inicializar, 200);
        }
        
        // Escuchar eventos de Livewire v3 para mostrar mensajes después de actualizaciones
        document.addEventListener('livewire:init', function() {
            setTimeout(function() {
                mostrarMensajesFlash();
            }, 200);
        });

        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                mostrarMensajesFlash();
            }, 200);
        });

        // Función simplificada para hacer focus en el primer campo con error
        function focusEnPrimerError() {
            // Verificar si el modal de edición está visible
            const modalEdit = document.querySelector('[x-data*="activeTab"]');
            if (!modalEdit || !modalEdit.offsetParent) {
                return; // El modal no está visible
            }

            // Buscar todos los mensajes de error dentro del modal
            const mensajesError = modalEdit.querySelectorAll('.text-red-500');
            if (mensajesError.length === 0) {
                return; // No hay errores
            }

            // Encontrar el primer mensaje de error visible
            let primerError = null;
            for (let i = 0; i < mensajesError.length; i++) {
                const elemento = mensajesError[i];
                // Verificar si el elemento es visible
                if (elemento.offsetParent !== null && window.getComputedStyle(elemento).display !== 'none') {
                    primerError = elemento;
                    break;
                }
            }

            if (!primerError) {
                return; // No hay errores visibles
            }

            // Hacer focus en el campo asociado
            focusEnCampoError(primerError);
        }
        
        // Función auxiliar para hacer focus en el campo de error
        function focusEnCampoError(primerError) {
            // Buscar el campo asociado (input, textarea, select) que está antes del mensaje de error
            let campo = primerError.previousElementSibling;
            
            // Si no está justo antes, buscar en el contenedor padre
            if (!campo || !['INPUT', 'TEXTAREA', 'SELECT'].includes(campo.tagName)) {
                const contenedor = primerError.closest('div');
                if (contenedor) {
                    // Buscar el primer input, textarea o select en el contenedor
                    campo = contenedor.querySelector('input, textarea, select');
                }
            }

            // Si encontramos un campo, hacer focus y scroll
            if (campo && ['INPUT', 'TEXTAREA', 'SELECT'].includes(campo.tagName)) {
                // Hacer scroll al campo
                campo.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Hacer focus después de un pequeño delay para asegurar que el scroll terminó
                setTimeout(function() {
                    campo.focus();
                }, 300);
            } else {
                // Si no hay un campo asociado (como en el caso de selectedProductos),
                // al menos hacer scroll al mensaje de error para que sea visible
                primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Escuchar eventos de Livewire para detectar errores de validación
        document.addEventListener('livewire:update', function() {
            setTimeout(function() {
                mostrarMensajesFlash();
                focusEnPrimerError();
            }, 300);
        });

        document.addEventListener('livewire:message-processed', function() {
            setTimeout(function() {
                mostrarMensajesFlash();
                focusEnPrimerError();
            }, 200);
        });

        // También usar el hook de Livewire si está disponible
        if (window.Livewire) {
            Livewire.hook('message.processed', ({ component, respond }) => {
                setTimeout(function() {
                    mostrarMensajesFlash();
                    focusEnPrimerError();
                }, 200);
            });
        }
    })();
</script>
@endpush

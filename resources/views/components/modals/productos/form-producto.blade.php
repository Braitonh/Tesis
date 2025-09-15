@props(['productoId' => null, 'modalTitle' => 'Editar Producto', 'showModal' => false, 'categorias' => []])

@if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-white/10 backdrop-blur-sm"></div>

        <!-- Modal Container -->
        <div class="bg-white rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.2)] overflow-hidden max-w-[1200px] w-full grid grid-cols-1 lg:grid-cols-2 relative mx-4" style="animation: slideUp 0.8s ease forwards;">

            <!-- Product Image Section -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-8 lg:p-12 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">

                <!-- Background decorations -->
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-400/20 rounded-full" style="animation: pulse 4s ease-in-out infinite;"></div>
                <div class="absolute -bottom-8 -left-8 w-36 h-36 bg-red-500/20 rounded-full" style="animation: pulse 4s ease-in-out infinite 2s;"></div>

                <div class="relative z-10 w-full">
                    <!-- Product Image Preview -->
                    <div class="mb-8">

                    <h1 class="text-2xl lg:text-3xl font-bold mb-4 leading-tight">
                        {{ $productoId ? '¡Actualizar Producto!' : '¡Nuevo Producto!' }}
                    </h1>
                        <div class="w-full h-80 lg:h-96 mx-auto rounded-2xl overflow-hidden shadow-2xl bg-white/10 backdrop-blur-sm relative">
                            @if($this->imagen_file)
                                <!-- Preview of uploaded file -->
                                <img
                                    src="{{ $this->imagen_file->temporaryUrl() }}"
                                    alt="Vista previa"
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs">
                                    Nueva
                                </div>
                            @elseif($productoId && !empty($this->imagen))
                                <!-- Current product image -->
                                <img
                                    src="{{ filter_var($this->imagen, FILTER_VALIDATE_URL) ? $this->imagen : Storage::url($this->imagen) }}"
                                    alt="{{ $this->nombre ?: 'Producto' }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <!-- Fallback icon -->
                                <div class="w-full h-full bg-white/20 flex items-center justify-center" style="display: none;">
                                    <i class="fas fa-image text-6xl text-white/80"></i>
                                </div>
                            @else
                                <!-- Default placeholder for products without image -->
                                <div class="w-full h-full bg-white/20 flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-utensils text-6xl text-white/80 mb-2"></i>
                                        <p class="text-white/80 text-sm">Sube una imagen</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload overlay -->
                            <div class="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <label for="imagen_file" class="cursor-pointer bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 text-white font-medium">
                                    <i class="fas fa-camera mr-2"></i>
                                    {{ $this->imagen_file || (!empty($this->imagen)) ? 'Cambiar imagen' : 'Subir imagen' }}
                                </label>
                            </div>
                        </div>

                        <!-- File input (hidden) -->
                        <input
                            type="file"
                            id="imagen_file"
                            wire:model="imagen_file"
                            accept="image/*"
                            class="hidden"
                        >

                        @error('imagen_file')
                            <div class="mt-2 text-red-300 text-sm text-center">{{ $message }}</div>
                        @enderror

                        <!-- Upload progress -->
                        <div wire:loading wire:target="imagen_file" class="mt-2 text-center">
                            <div class="inline-flex items-center text-white/80 text-sm">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Cargando imagen...
                            </div>
                        </div>
                    </div>
                    <h2 class="text-lg lg:text-xl font-bold mb-2 ">
                        Sube una imagen del producto!!!
                    </h2>

                    <p class="text-base lg:text-lg opacity-90 leading-relaxed mb-6">
                        {{ $productoId ? 'Modifica la información del producto para mantener el catálogo actualizado.' : 'Completa la información para agregar un nuevo producto al catálogo.' }}
                    </p>


                </div>
            </div>

            <!-- Form Section -->
            <div class="p-4 lg:p-8 flex flex-col justify-center relative max-h-[90vh] overflow-y-auto">

                <!-- Close button -->
                <button wire:click="closeEditModal" class="absolute top-4 right-4 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Form header -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">{{ $modalTitle }}</h2>
                    <p class="text-gray-600">Complete la información del producto</p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="saveProducto" class="space-y-4">

                    <!-- Name field -->
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-gray-800">
                            Nombre del Producto
                        </label>
                        <div class="relative">
                            <i class="fas fa-utensils absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                            <input
                                type="text"
                                id="nombre"
                                wire:model="nombre"
                                class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('nombre') border-red-500 @enderror"
                                placeholder="Ej: Hamburguesa Clásica">
                        </div>
                        @error('nombre')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description field -->
                    <div class="space-y-2">
                        <label for="descripcion" class="block text-sm font-semibold text-gray-800">
                            Descripción
                        </label>
                        <div class="relative">
                            <i class="fas fa-align-left absolute left-5 top-6 text-gray-400 text-lg"></i>
                            <textarea
                                id="descripcion"
                                wire:model="descripcion"
                                rows="3"
                                class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('descripcion') border-red-500 @enderror"
                                placeholder="Describe el producto..."></textarea>
                        </div>
                        @error('descripcion')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="precio" class="block text-sm font-semibold text-gray-800">
                                Precio Regular
                            </label>
                            <div class="relative">
                                <i class="fas fa-dollar-sign absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <input
                                    type="number"
                                    id="precio"
                                    wire:model="precio"
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('precio') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('precio')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="precio_descuento" class="block text-sm font-semibold text-gray-800">
                                Precio con Descuento (Opcional)
                            </label>
                            <div class="relative">
                                <i class="fas fa-percentage absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <input
                                    type="number"
                                    id="precio_descuento"
                                    wire:model="precio_descuento"
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('precio_descuento') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('precio_descuento')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock and Category -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="stock" class="block text-sm font-semibold text-gray-800">
                                Stock
                            </label>
                            <div class="relative">
                                <i class="fas fa-boxes absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <input
                                    type="number"
                                    id="stock"
                                    wire:model="stock"
                                    min="0"
                                    class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('stock') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('stock')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="categoria_id" class="block text-sm font-semibold text-gray-800">
                                Categoría
                            </label>
                            <div class="relative">
                                <i class="fas fa-tags absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <select
                                    id="categoria_id"
                                    wire:model="categoria_id"
                                    class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('categoria_id') border-red-500 @enderror">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('categoria_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

  

                    <!-- Checkboxes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                            <input
                                type="checkbox"
                                id="destacado"
                                wire:model="destacado"
                                class="w-5 h-5 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                            <label for="destacado" class="text-sm font-medium text-gray-700">
                                <i class="fas fa-star text-orange-500 mr-1"></i>
                                Producto Destacado
                            </label>
                        </div>

                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                            <input
                                type="checkbox"
                                id="activo"
                                wire:model="activo"
                                class="w-5 h-5 text-orange-500 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                            <label for="activo" class="text-sm font-medium text-gray-700">
                                <i class="fas fa-eye text-green-500 mr-1"></i>
                                Producto Activo
                            </label>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="saveProducto"
                        class="mt-6 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-3 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">

                        <span wire:loading.remove wire:target="saveProducto" class="flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            {{ $productoId ? 'Actualizar' : 'Crear' }} Producto
                        </span>

                        <span wire:loading wire:target="saveProducto" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $productoId ? 'Actualizando...' : 'Creando...' }}
                        </span>
                    </button>
                </form>
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

    .food-item {
        animation: bounce 2s ease-in-out infinite;
    }

    .food-item:nth-child(2) {
        animation-delay: 0.2s;
    }

    .food-item:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
    </style>
@endif
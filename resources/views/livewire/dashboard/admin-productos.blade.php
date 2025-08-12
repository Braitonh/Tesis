<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-boxes text-orange-600 mr-2"></i>
                    Gestión de Productos
                </h2>
                <p class="text-gray-600 mt-1">Administra el catálogo de productos del restaurante</p>
            </div>
            <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
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
                    <p class="text-2xl font-bold text-gray-800">89</p>
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
                    <p class="text-2xl font-bold text-gray-800">76</p>
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
                    <p class="text-2xl font-bold text-gray-800">8</p>
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
                    <p class="text-2xl font-bold text-gray-800">5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option>Todas las categorías</option>
                    <option>Hamburguesas</option>
                    <option>Pizzas</option>
                    <option>Bebidas</option>
                    <option>Postres</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option>Todos</option>
                    <option>Disponible</option>
                    <option>Agotado</option>
                    <option>Deshabilitado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" placeholder="Nombre del producto..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="flex items-end">
                <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Product Card 1 -->
        <div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-hamburger text-6xl text-gray-400"></i>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Hamburguesa Clásica</h3>
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Disponible</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Carne, lechuga, tomate, cebolla y salsa especial</p>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-2xl font-bold text-orange-600">$12.99</span>
                    <span class="text-sm text-gray-500">Stock: 25</span>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </button>
                    <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Ver
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Card 2 -->
        <div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-pizza-slice text-6xl text-gray-400"></i>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Pizza Margarita</h3>
                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Stock Bajo</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Masa artesanal, salsa de tomate, mozzarella y albahaca</p>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-2xl font-bold text-orange-600">$18.50</span>
                    <span class="text-sm text-yellow-600">Stock: 3</span>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </button>
                    <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Ver
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Card 3 -->
        <div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden opacity-75">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-glass-whiskey text-6xl text-gray-400"></i>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Refresco Cola</h3>
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Agotado</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Bebida gaseosa cola 500ml</p>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-2xl font-bold text-gray-400">$3.50</span>
                    <span class="text-sm text-red-600">Stock: 0</span>
                </div>
                <div class="flex space-x-2">
                    <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </button>
                    <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Ver
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
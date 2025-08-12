<div class="p-6 space-y-6 bg-white/80  rounded-3xl w-full max-w-none">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-6 text-white">
            <h1 class="text-3xl font-bold mb-2">
                <i class="fas fa-utensils mr-3"></i>
                Módulo Cocina
            </h1>
            <p class="text-amber-100">Panel de gestión de cocina - {{ auth()->user()->name }}</p>
        </div>

        <!-- Kitchen Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-fire text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Pedidos Pendientes</p>
                        <p class="text-2xl font-bold text-gray-800">8</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Tiempo Promedio</p>
                        <p class="text-2xl font-bold text-gray-800">12min</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Completados Hoy</p>
                        <p class="text-2xl font-bold text-gray-800">24</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-utensils text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">En Preparación</p>
                        <p class="text-2xl font-bold text-gray-800">5</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-orange-100">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Pedidos Activos</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Order Card 1 -->
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-red-800">#1234 - URGENTE</span>
                            <span class="text-xs text-red-600">Hace 15min</span>
                        </div>
                        <div class="text-sm text-gray-700 mb-3">
                            <p>2x Hamburguesa Clásica</p>
                            <p>1x Papas Fritas</p>
                        </div>
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                            Comenzar Preparación
                        </button>
                    </div>

                    <!-- Order Card 2 -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-yellow-800">#1235</span>
                            <span class="text-xs text-yellow-600">Hace 5min</span>
                        </div>
                        <div class="text-sm text-gray-700 mb-3">
                            <p>1x Pizza Grande</p>
                            <p>1x Refresco</p>
                        </div>
                        <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                            Marcar como Listo
                        </button>
                    </div>

                    <!-- Order Card 3 -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-blue-800">#1236</span>
                            <span class="text-xs text-blue-600">Hace 2min</span>
                        </div>
                        <div class="text-sm text-gray-700 mb-3">
                            <p>3x Tacos</p>
                            <p>2x Bebidas</p>
                        </div>
                        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="space-y-6">
    <!-- Dashboard Home Header -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">¡Bienvenido a FoodDesk!</h1>
        <p class="text-orange-100">Panel principal de gestión del restaurante</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pedidos Hoy -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Pedidos Hoy</p>
                    <p class="text-2xl font-bold text-gray-800">24</p>
                </div>
            </div>
        </div>

        <!-- Ventas del Día -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Ventas Hoy</p>
                    <p class="text-2xl font-bold text-gray-800">$1,240</p>
                </div>
            </div>
        </div>

        <!-- Clientes Activos -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Clientes Activos</p>
                    <p class="text-2xl font-bold text-gray-800">156</p>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-boxes text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Productos</p>
                    <p class="text-2xl font-bold text-gray-800">89</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm border border-orange-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Pedidos Recientes</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="bg-orange-100 p-2 rounded-lg">
                            <i class="fas fa-hamburger text-orange-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-800">Pedido #1234</p>
                            <p class="text-sm text-gray-500">Juan Pérez - 2 hamburguesas</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">$25.99</p>
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Completado</span>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="bg-orange-100 p-2 rounded-lg">
                            <i class="fas fa-pizza-slice text-orange-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-800">Pedido #1235</p>
                            <p class="text-sm text-gray-500">María García - 1 pizza grande</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">$18.50</p>
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">En preparación</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
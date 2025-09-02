<div class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-8 text-white shadow-lg mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    ¡Bienvenido{{ $usuario->name ? ', ' . $usuario->name : '' }}!
                </h1>
                <p class="text-orange-100 text-lg">
                    Estamos listos para preparar tu pedido favorito
                </p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-utensils text-6xl text-orange-200"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Rápido y Fácil</h3>
                </div>
            </div>
            <p class="text-gray-600">
                Pedidos listos en tiempo récord. Tu comida favorita cuando la necesites.
            </p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center mb-4">
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-heart text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Calidad Premium</h3>
                </div>
            </div>
            <p class="text-gray-600">
                Ingredientes frescos y preparación cuidadosa en cada platillo.
            </p>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-truck text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Delivery Seguro</h3>
                </div>
            </div>
            <p class="text-gray-600">
                Seguimiento en tiempo real y entrega hasta tu puerta.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">¿Qué te gustaría hacer hoy?</h2>
            <p class="text-gray-600">Explora nuestras opciones disponibles</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <button class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-6 hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105">
                <div class="flex items-center justify-center mb-3">
                    <i class="fas fa-hamburger text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Ver Menú</h3>
                <p class="text-red-100">Descubre todos nuestros deliciosos platillos</p>
            </button>

            <button class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105">
                <div class="flex items-center justify-center mb-3">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Mis Pedidos</h3>
                <p class="text-blue-100">Revisa el estado de tus pedidos anteriores</p>
            </button>
        </div>
    </div>

    <div class="mt-8 text-center">
        <p class="text-gray-500">
            <i class="fas fa-phone mr-2"></i>
            ¿Necesitas ayuda? Contáctanos al (123) 456-7890
        </p>
    </div>
</div>

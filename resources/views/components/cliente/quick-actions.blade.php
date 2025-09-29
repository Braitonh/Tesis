<div class="bg-gradient-to-r from-gray-50 to-orange-50 border border-orange-500/20 rounded-2xl p-8 mb-12 shadow-lg">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-3">¿Qué te gustaría hacer hoy?</h2>
        <p class="text-gray-600 text-lg">Explora nuestras opciones disponibles y comienza tu experiencia gastronómica</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <button
            onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})"
            class="group bg-gradient-to-br from-red-500 to-red-600 text-white rounded-2xl p-8 hover:from-red-600 hover:to-red-700 hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
        >
            <div class="text-center">
                <div class="bg-white/20 p-4 rounded-full mb-4 group-hover:scale-110 transition-transform duration-300 mx-auto w-fit">
                    <i class="fas fa-utensils text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Explorar Menú</h3>
                <p class="text-red-100 text-lg leading-relaxed mb-4">Descubre nuestra amplia variedad de platillos deliciosos y frescos</p>
                <div class="flex items-center justify-center text-red-200">
                    <span class="mr-2">Ver productos</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </div>
            </div>
        </button>

        <button
            class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-8 hover:from-blue-600 hover:to-blue-700 hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
        >
            <div class="text-center">
                <div class="bg-white/20 p-4 rounded-full mb-4 group-hover:scale-110 transition-transform duration-300 mx-auto w-fit">
                    <i class="fas fa-clock text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Mis Pedidos</h3>
                <p class="text-blue-100 text-lg leading-relaxed mb-4">Revisa el estado y historial de todos tus pedidos anteriores</p>
                <div class="flex items-center justify-center text-blue-200">
                    <span class="mr-2">Ver historial</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </div>
            </div>
        </button>
    </div>
</div>
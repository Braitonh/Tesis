@props(['usuario'])

<div class="bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 rounded-3xl overflow-hidden shadow-2xl mb-12 relative">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-white rounded-full animate-pulse" style="animation-delay: 1s"></div>
        <div class="absolute bottom-20 left-32 w-12 h-12 bg-white rounded-full animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center p-8 md:p-16">
        <div class="space-y-6">
            <div class="bg-white/20 text-white hover:bg-white/30 border-0 inline-block px-4 py-2 rounded-full text-sm font-medium">
                <i class="fas fa-fire mr-2"></i>¡Los mejores sabores te esperan!
            </div>

            <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight">
                ¡Hola{{ $usuario && $usuario->name ? ', ' . $usuario->name : '' }}!
            </h1>

            <p class="text-xl text-orange-100 leading-relaxed max-w-lg">
                Descubre nuestra increíble variedad de platillos frescos, preparados con amor y entregados directo a tu mesa.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button
                    onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})"
                    class="bg-white text-orange-600 hover:bg-gray-50 shadow-lg transform hover:scale-105 font-bold text-lg px-8 py-4 rounded-xl transition-all duration-300"
                >
                    <i class="fas fa-utensils mr-2"></i>Explorar Menú
                </button>

                <button
                    onclick="document.getElementById('destacados-section').scrollIntoView({behavior: 'smooth'})"
                    class="border-2 border-white text-white hover:bg-white hover:text-orange-600 font-bold text-lg px-8 py-4 rounded-xl transition-all duration-300"
                >
                    <i class="fas fa-star mr-2"></i>Ver Especialidades
                </button>
            </div>
        </div>

        <div class="hidden md:block relative">
            <div class="relative">
                <img
                    src="{{ asset('images/burgerHero.png') }}"
                    alt="Hamburguesa Premium"
                    class="w-full h-96 object-cover rounded-2xl shadow-2xl rotate-3 hover:rotate-6 transition-transform duration-500"
                />
            </div>
        </div>
    </div>
</div>
<nav class="bg-white border-b border-gray-100 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-xl font-bold text-gray-800 hover:text-orange-600 transition-colors duration-300">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white w-10 h-10 rounded-full flex items-center justify-center text-lg">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <span class="hidden sm:block">FoodDesk</span>
                </a>
            </div>

            <!-- Dashboard Navigation (optional slot for menu items) -->
            <div class="hidden md:flex items-center space-x-8">
                @isset($navigation)
                    {{ $navigation }}
                @endisset
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            class="flex items-center space-x-2 text-gray-700 hover:text-orange-600 transition-colors duration-300 focus:outline-none"
                        >
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:block font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-[100]"
                        >
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-200">
                                <i class="fas fa-user"></i>
                                Mi Perfil
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-200">
                                <i class="fas fa-cog"></i>
                                Configuración
                            </a>
                            <hr class="my-2 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200 text-left">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-700 font-medium hover:underline transition-colors duration-300">Iniciar Sesi�n</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 hover:shadow-lg">Registrarse</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button 
                    x-data="{ mobileOpen: false }"
                    @click="mobileOpen = !mobileOpen"
                    class="p-2 text-gray-400 hover:text-orange-600 transition-colors duration-300"
                >
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="md:hidden border-t border-orange-100" x-data="{ mobileOpen: false }" x-show="mobileOpen" x-cloak>
        @isset($mobileNavigation)
            {{ $mobileNavigation }}
        @endisset
    </div>
</nav>
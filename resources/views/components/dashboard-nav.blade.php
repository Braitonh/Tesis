@props(['currentRoute' => null])

@php
$currentRouteName = request()->route()->getName() ?? '';
$currentPath = request()->path();
@endphp

<!-- Horizontal Navigation Menu -->
<nav class="bg-white sticky rounded-b-3xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">

            <!-- Main Navigation Links -->
            <div class="flex items-center space-x-8">
                <!-- Dashboard Home -->
                @php
                $DashboardRoutes = ['dashboard'];
                $isDashboardActive = in_array($currentRouteName, $DashboardRoutes) || str_contains($currentRoute ?? '', 'dashboard');
                @endphp
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200 rounded-lg
                            {{ $currentRouteName === 'dashboard' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>

                <!-- Pedidos Module -->
                @php
                $pedidosRoutes = ['pedidos']; // Agrega rutas específicas del módulo cliente aquí
                $isPedidosActive = in_array($currentRouteName, $pedidosRoutes) || str_contains($currentRoute ?? '', 'pedidos');
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 
                                   {{ $isPedidosActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-users"></i>
                        <span class="hidden sm:block">Pedidos</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-cloak
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2">
                        <a href="{{ route('pedidos') }}" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                           {{ $currentRouteName === 'pedidos' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="fas fa-shopping-cart"></i>
                            Ordenes
                        </a>
                        <button wire:click="$dispatch('navigateTo', 'cliente', 'perfil')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-user"></i>
                            Detalles
                        </button>
                    </div>
                </div>

                <!-- Cliente Module -->
                @php
                $clienteRoutes = []; // Agrega rutas específicas del módulo cliente aquí
                $isClienteActive = in_array($currentRouteName, $clienteRoutes) || str_contains($currentRoute ?? '', 'cliente');
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 
                                   {{ $isClienteActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-users"></i>
                        <span class="hidden sm:block">Clientes</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-cloak
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2">
                        <button wire:click="$dispatch('navigateTo', 'cliente', 'pedidos')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-shopping-cart"></i>
                            Pedidos
                        </button>
                        <button wire:click="$dispatch('navigateTo', 'cliente', 'perfil')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-user"></i>
                            Perfil
                        </button>
                    </div>
                </div>

                <!-- Empleado Module -->
                @php
                $empleadoRoutes = ['cocina', 'empleados']; // Agrega más rutas aquí si tienes otras del módulo empleado
                $isEmpleadoActive = in_array($currentRouteName, $empleadoRoutes) || str_contains($currentRoute ?? '', 'empleado');
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 
                                   {{ $isEmpleadoActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-user-tie"></i>
                        <span class="hidden sm:block">Empleados</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-cloak
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2">
                        <button wire:click="$dispatch('navigateTo', 'empleado', 'ventas')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-cash-register"></i>
                            Ventas
                        </button>
                        <a href="{{ route('cocina') }}" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                           {{ $currentRouteName === 'cocina' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="fas fa-utensils"></i>
                            Cocina
                        </a>
                        <button wire:click="$dispatch('navigateTo', 'empleado', 'delivery')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-motorcycle"></i>
                            Delivery
                        </button>
                        <a href="{{ route('empleados') }}" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                           {{ $currentRouteName === 'empleados' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="fas fa-user-tie"></i>
                            Empleados
                        </a>
                    </div>
                </div>

                <!-- Admin Module -->
                @php
                $adminRoutes = ['productos']; // Agrega rutas específicas del módulo admin aquí
                $isAdminActive = in_array($currentRouteName, $adminRoutes) || str_contains($currentRoute ?? '', 'admin');
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 
                                   {{ $isAdminActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-cog"></i>
                        <span class="hidden sm:block">Administración</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-cloak
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-2">
                        <button wire:click="$dispatch('navigateTo', 'admin', 'analitica')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-chart-bar"></i>
                            Analítica
                        </button>
                        <a href="{{ route('pedidos') }}" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                           {{ $currentRouteName === 'pedidos' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="fas fa-shopping-cart"></i>
                            Pedidos
                        </a>
                        <a href="{{ route('productos') }}" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                           {{ $currentRouteName === 'productos' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="fas fa-boxes"></i>
                            Productos
                        </a>
                        <button wire:click="$dispatch('navigateTo', 'admin', 'personalizacion')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-palette"></i>
                            Personalización
                        </button>
                        <button wire:click="$dispatch('navigateTo', 'admin', 'usuarios')" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 w-full text-left">
                            <i class="fas fa-users-cog"></i>
                            Usuarios
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Mobile Navigation (collapsible) -->
    <div class="md:hidden border-t border-orange-100" x-data="{ mobileNavOpen: false }">
        <button @click="mobileNavOpen = !mobileNavOpen"
            class="w-full px-4 py-2 text-left text-sm text-gray-600 hover:bg-orange-50">
            <i class="fas fa-bars mr-2"></i>
            Menú de navegación
            <i class="fas fa-chevron-down float-right mt-0.5"></i>
        </button>

        <div x-show="mobileNavOpen" x-transition class="px-4 pb-4 space-y-2">
            <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded">
                <i class="fas fa-users mr-2"></i> Clientes
            </a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded">
                <i class="fas fa-user-tie mr-2"></i> Empleados
            </a>
            <a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-orange-600 hover:bg-orange-50 rounded">
                <i class="fas fa-cog mr-2"></i> Administración
            </a>
        </div>
    </div>
</nav>
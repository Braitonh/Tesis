@props(['currentRoute' => null])

@php
$currentRouteName = request()->route()->getName() ?? '';
$currentPath = request()->path();
@endphp

<!-- Horizontal Navigation Menu -->
<nav class="bg-white sticky rounded-b-3xl z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">

            <!-- Main Navigation Links -->
            <div class="flex items-center space-x-6">
                <!-- Dashboard Home (visible para admin y empleado) -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'empleado')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $currentRouteName === 'dashboard' ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-home"></i>
                        <span class="hidden sm:block">Dashboard</span>
                    </a>
                @endif

                <!-- Pedidos Module (visible para admin y empleado) -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'empleado')
                    @php
                    $pedidosRoutes = ['pedidos', 'pedidos.show', 'pedidos.edit'];
                    $isPedidosActive = in_array($currentRouteName, $pedidosRoutes) || str_contains($currentRoute ?? '', 'pedidos');
                    @endphp
                    <a href="{{ route('pedidos') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $isPedidosActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="hidden sm:block">Pedidos</span>
                    </a>
                @endif

                <!-- Cocina Module (visible para admin) -->
                @if(auth()->user()->role === 'admin')
                    @php
                    $cocinaRoutes = ['cocina'];
                    $isCocinaActive = in_array($currentRouteName, $cocinaRoutes);
                    @endphp
                    <a href="{{ route('cocina') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $isCocinaActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-utensils"></i>
                        <span class="hidden sm:block">Cocina</span>
                    </a>
                @endif

                <!-- Delivery Module (visible para admin y delivery) -->
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'delivery')
                    @php
                    $deliveryRoutes = ['delivery'];
                    $isDeliveryActive = in_array($currentRouteName, $deliveryRoutes) || str_contains($currentRoute ?? '', 'delivery');
                    @endphp
                    <a href="{{ route('delivery') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $isDeliveryActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-motorcycle"></i>
                        <span class="hidden sm:block">Delivery</span>
                    </a>
                @endif

                <!-- Productos Module (visible solo para admin) -->
                @if(auth()->user()->role === 'admin')
                    @php
                    $productosRoutes = ['productos', 'productos.create', 'productos.edit'];
                    $isProductosActive = in_array($currentRouteName, $productosRoutes) || str_contains($currentRoute ?? '', 'productos');
                    @endphp
                    <a href="{{ route('productos') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $isProductosActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-boxes"></i>
                        <span class="hidden sm:block">Productos</span>
                    </a>
                @endif

                <!-- Clientes Module (Admin only) -->
                @if(auth()->user()->role === 'admin')
                    @php
                    $clientesRoutes = ['clientes', 'clientes.show'];
                    $isClientesActive = in_array($currentRouteName, $clientesRoutes) || str_contains($currentRoute ?? '', 'clientes');
                    @endphp
                    <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $isClientesActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-users"></i>
                        <span class="hidden sm:block">Clientes</span>
                    </a>
                @endif

                <!-- Empleados Module (Admin only) -->
                @if(auth()->user()->role === 'admin')
                    @php
                    $empleadosRoutes = ['empleados', 'empleados.create', 'empleados.edit'];
                    $isEmpleadosActive = in_array($currentRouteName, $empleadosRoutes) || str_contains($currentRoute ?? '', 'empleados');
                    @endphp
                    <a href="{{ route('empleados') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                            {{ $isEmpleadosActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-user-tie"></i>
                        <span class="hidden sm:block">Empleados</span>
                    </a>
                @endif

                <!-- Administración Module (Admin only) -->
                @if(auth()->user()->role === 'admin')
                    @php
                    $adminRoutes = ['admin.analitica', 'admin.usuarios', 'admin.personalizacion'];
                    $isAdminActive = in_array($currentRouteName, $adminRoutes) || str_contains($currentRoute ?? '', 'admin');
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300
                                       {{ $isAdminActive ? 'bg-orange-100 text-orange-700 shadow-sm' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                            <i class="fas fa-cog"></i>
                            <span class="hidden sm:block">Administración</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-cloak
                            class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50">
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                               {{ $currentRouteName === 'admin.analitica' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i class="fas fa-chart-bar"></i>
                                Analítica
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                               {{ $currentRouteName === 'admin.usuarios' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i class="fas fa-users-cog"></i>
                                Usuarios
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm w-full text-left transition-colors duration-200
                               {{ $currentRouteName === 'admin.personalizacion' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i class="fas fa-palette"></i>
                                Personalización
                            </a>
                        </div>
                    </div>
                @endif
            </div>


        </div>
    </div>

    <!-- Mobile Navigation (collapsible) -->
    <div class="md:hidden border-t border-orange-100" x-data="{ mobileNavOpen: false }">
        <button @click="mobileNavOpen = !mobileNavOpen"
            class="w-full px-4 py-2 text-left text-sm text-gray-600 hover:bg-orange-50 transition-colors duration-200">
            <i class="fas fa-bars mr-2"></i>
            Menú de navegación
            <i class="fas fa-chevron-down float-right mt-0.5 transition-transform duration-200" :class="{ 'rotate-180': mobileNavOpen }"></i>
        </button>

        <div x-show="mobileNavOpen" x-transition class="px-4 pb-4 space-y-1">
            <!-- Dashboard Home (visible para admin y empleado) -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'empleado')
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $currentRouteName === 'dashboard' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
            @endif

            <!-- Pedidos (visible para admin y empleado) -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'empleado')
                <a href="{{ route('pedidos') }}" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $isPedidosActive ?? false ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-shopping-cart mr-2"></i> Pedidos
                </a>
            @endif

            <!-- Cocina (visible para admin) -->
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('cocina') }}" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $isCocinaActive ?? false ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-utensils mr-2"></i> Cocina
                </a>
            @endif

            <!-- Delivery (visible para admin y delivery) -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'delivery')
                <a href="{{ route('delivery') }}" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $isDeliveryActive ?? false ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-motorcycle mr-2"></i> Delivery
                </a>
            @endif

            <!-- Productos (visible solo para admin) -->
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('productos') }}" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $isProductosActive ?? false ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-boxes mr-2"></i> Productos
                </a>
            @endif

            <!-- Clientes (Admin only) -->
            @if(auth()->user()->role === 'admin')
                <a href="#" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $isClientesActive ?? false ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-users mr-2"></i> Clientes
                </a>
            @endif

            <!-- Empleados (Admin only) -->
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('empleados') }}" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                    {{ $isEmpleadosActive ?? false ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                    <i class="fas fa-user-tie mr-2"></i> Empleados
                </a>
            @endif

            <!-- Administración submenu in mobile (Admin only) -->
            @if(auth()->user()->role === 'admin')
                <div class="pt-2 border-t border-gray-100 mt-2">
                    <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Administración</div>
                    <a href="#" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                        {{ $currentRouteName === 'admin.analitica' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-chart-bar mr-2"></i> Analítica
                    </a>
                    <a href="#" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                        {{ $currentRouteName === 'admin.usuarios' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-users-cog mr-2"></i> Usuarios
                    </a>
                    <a href="#" class="block px-3 py-2 text-sm rounded transition-colors duration-200
                        {{ $currentRouteName === 'admin.personalizacion' ? 'bg-orange-100 text-orange-700 font-semibold' : 'text-gray-600 hover:text-orange-600 hover:bg-orange-50' }}">
                        <i class="fas fa-palette mr-2"></i> Personalización
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>
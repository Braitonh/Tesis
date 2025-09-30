<div>
    <!-- Contenido dinámico basado en la sección activa -->
    @switch($activeSection)
        @case('dashboard')
            <livewire:dashboard.dashboard-home />
            @break
        @case('pedidos')
            @switch($activeSubsection)
                @case('pedidos')
                    <livewire:dashboard.admin-pedidos />
                    @break
                @default
                    <livewire:dashboard.admin-pedidos />
                @endswitch
        @break
            
        @case('cliente')
            @switch($activeSubsection)
                @case('pedidos')
                    <livewire:dashboard.cliente-pedidos />
                    @break
                @case('perfil')
                    <livewire:dashboard.cliente-perfil />
                    @break
                @default
                    <livewire:dashboard.cliente-overview />
            @endswitch
            @break
            
        @case('empleado')
            @switch($activeSubsection)
                @case('ventas')
                    <livewire:dashboard.empleado-ventas />
                    @break
                @case('cocina')
                    <livewire:dashboard.empleado-cocina />
                    @break
                @case('productos')
                    <livewire:dashboard.empleado-productos />
                    @break
                @case('delivery')
                    <livewire:dashboard.empleado-delivery />
                    @break
                @default
                    <livewire:dashboard.empleado-overview />
            @endswitch
            @break
            
        @case('admin')
            @switch($activeSubsection)
                @case('analitica')
                    <livewire:dashboard.admin-analitica />
                    @break
                @case('productos')
                    <livewire:dashboard.admin-productos />
                    @break
                @case('personalizacion')
                    <livewire:dashboard.admin-personalizacion />
                    @break
                @case('usuarios')
                    <livewire:dashboard.admin-usuarios />
                    @break
                @default
                    <livewire:dashboard.admin-overview />
            @endswitch
            @break
            
        @default
            <livewire:dashboard.dashboard-home />
    @endswitch
</div>
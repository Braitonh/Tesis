@props(['filtroEstado', 'filtroEstadoPago', 'filtroFecha', 'busquedaCliente'])

<div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select wire:model.live="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                <option value="">Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="en_preparacion">En preparación</option>
                <option value="listo">Listo</option>
                <option value="en_camino">En camino</option>
                <option value="entregado">Entregado</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago</label>
            <select wire:model.live="filtroEstadoPago" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                <option value="">Todos</option>
                <option value="pendiente">Pendiente</option>
                <option value="pagado">Pagado</option>
                <option value="fallido">Fallido</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
            <input type="date" wire:model.live="filtroFecha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
            <input
                type="text"
                wire:model.live.debounce.300ms="busquedaCliente"
                placeholder="Buscar cliente..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
            >
        </div>
        <div class="flex items-end space-x-2">
            <button
                wire:click="limpiarFiltros"
                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors"
            >
                <i class="fas fa-times mr-2"></i>
                Limpiar
            </button>
        </div>
    </div>

    <!-- Filtros activos -->
    @if($filtroEstado || $filtroEstadoPago || $filtroFecha || $busquedaCliente)
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="text-sm text-gray-600">Filtros activos:</span>

            @if($filtroEstado)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    Estado: {{ ucfirst(str_replace('_', ' ', $filtroEstado)) }}
                    <button wire:click="$set('filtroEstado', '')" class="ml-1 text-orange-600 hover:text-orange-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif

            @if($filtroEstadoPago)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Pago: {{ ucfirst($filtroEstadoPago) }}
                    <button wire:click="$set('filtroEstadoPago', '')" class="ml-1 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif

            @if($filtroFecha)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Fecha: {{ \Carbon\Carbon::parse($filtroFecha)->format('d/m/Y') }}
                    <button wire:click="$set('filtroFecha', '')" class="ml-1 text-yellow-600 hover:text-yellow-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif

            @if($busquedaCliente)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    "{{ $busquedaCliente }}"
                    <button wire:click="$set('busquedaCliente', '')" class="ml-1 text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
        </div>
    @endif
</div>
@props(['pedidos', 'filtroEstado', 'filtroEstadoPago', 'filtroFecha', 'busquedaCliente'])

<div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Lista de Pedidos</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pedidos as $pedido)
                    <x-pedidos.pedido-row :pedido="$pedido" wire:key="pedido-{{ $pedido->id }}" />
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <div class="max-w-md mx-auto">
                                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron pedidos</h3>
                                <p class="text-gray-500 mb-6">
                                    @if($filtroEstado || $filtroEstadoPago || $filtroFecha || $busquedaCliente)
                                        No hay pedidos que coincidan con los filtros aplicados.
                                    @else
                                        Aún no hay pedidos registrados en el sistema.
                                    @endif
                                </p>
                                @if($filtroEstado || $filtroEstadoPago || $filtroFecha || $busquedaCliente)
                                    <button
                                        wire:click="limpiarFiltros"
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                    >
                                        <i class="fas fa-times mr-2"></i>
                                        Limpiar Filtros
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $pedidos->links() }}
    </div>
</div>
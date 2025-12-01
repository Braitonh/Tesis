<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Mensajes de estado -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">Gestión de Clientes</h1>
                        <p class="text-gray-500">Administra todos los clientes registrados</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Clientes -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Activos -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Activos</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['activos'] }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-user-check text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Cliente</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   wire:model.live="search"
                                   placeholder="Nombre o email..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Estado</label>
                        <select wire:model.live="filtroBloqueado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Todos</option>
                            <option value="activos">Activos</option>
                            <option value="bloqueados">Bloqueados</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="$set('search', ''); $set('filtroBloqueado', '')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Clientes -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Lista de Clientes ({{ $clientes->total() }} clientes)</h3>
                </div>
        
                @if($clientes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($clientes as $cliente)
                                    <tr wire:key="cliente-{{ $cliente->id }}" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white font-semibold">
                                                        {{ strtoupper(substr($cliente->name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $cliente->name }}</div>
                                                    <div class="text-sm text-gray-500">ID: #{{ $cliente->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $cliente->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $cliente->telefono ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($cliente->is_blocked)
                                                <span class="flex items-center px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-ban mr-1"></i>
                                                    Bloqueado
                                                </span>
                                            @else
                                                <span class="flex items-center px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Activo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $cliente->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button wire:click.prevent="verEstadisticas({{ $cliente->id }})" 
                                                        class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                                        title="Ver estadísticas">
                                                    <i class="fas fa-chart-bar"></i>
                                                </button>
                                                
                                                <button wire:click.prevent="toggleBlock({{ $cliente->id }})" 
                                                        class="p-2 rounded-lg transition-colors {{ $cliente->is_blocked ? 'text-green-600 hover:text-green-900 hover:bg-green-50' : 'text-red-600 hover:text-red-900 hover:bg-red-50' }}"
                                                        title="{{ $cliente->is_blocked ? 'Desbloquear cliente' : 'Bloquear cliente' }}">
                                                    @if($cliente->is_blocked)
                                                        <i class="fas fa-unlock"></i>
                                                    @else
                                                        <i class="fas fa-lock"></i>
                                                    @endif
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t border-gray-200">
                        {{ $clientes->links() }}
                    </div>
                @else
                    <!-- Estado Vacío -->
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-users text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">No hay clientes</h3>
                        <p class="text-gray-500 mb-6">No se encontraron clientes que coincidan con los filtros aplicados.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal de Estadísticas -->
    @if($showStatsModal && $clienteSeleccionado && $estadisticasCliente)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm animate-fade-in" wire:click="closeStatsModal"></div>

            <!-- Modal Container -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-4xl w-full relative animate-fade-in" style="animation: slideUp 0.3s ease forwards;">
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <i class="fas fa-chart-bar text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Estadísticas de {{ $clienteSeleccionado->name }}</h3>
                            <p class="text-orange-100 text-sm">Información detallada del cliente</p>
                        </div>
                    </div>
                    <button wire:click="closeStatsModal" class="text-white hover:text-orange-100 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 max-h-[80vh] overflow-y-auto">

                <div class="space-y-6">
                    <!-- Información del Cliente -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3">Información del Cliente</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Email:</span>
                                <span class="ml-2 font-medium">{{ $clienteSeleccionado->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Teléfono:</span>
                                <span class="ml-2 font-medium">{{ $clienteSeleccionado->telefono ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Registrado:</span>
                                <span class="ml-2 font-medium">{{ $clienteSeleccionado->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Estado:</span>
                                <span class="ml-2">
                                    @if($clienteSeleccionado->is_blocked)
                                        <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-1"></i> Bloqueado
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Activo
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas de Pedidos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Total Pedidos -->
                        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Total Pedidos</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $estadisticasCliente['total_pedidos'] }}</p>
                                </div>
                                <i class="fas fa-shopping-cart text-blue-500 text-3xl"></i>
                            </div>
                        </div>

                        <!-- Pedidos Completados -->
                        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Completados</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $estadisticasCliente['pedidos_completados'] }}</p>
                                </div>
                                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                            </div>
                        </div>

                        <!-- Pedidos Cancelados -->
                        <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Cancelados</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $estadisticasCliente['pedidos_cancelados'] }}</p>
                                </div>
                                <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                            </div>
                        </div>

                        <!-- Total Gastado -->
                        <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 font-medium">Total Gastado</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">${{ number_format($estadisticasCliente['total_gastado'], 2, ',', '.') }}</p>
                                </div>
                                <i class="fas fa-dollar-sign text-orange-500 text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Último Pedido -->
                    @if($estadisticasCliente['ultimo_pedido'])
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-3">Último Pedido</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Número:</span>
                                    <span class="ml-2 font-medium">{{ $estadisticasCliente['ultimo_pedido']['numero'] }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Fecha:</span>
                                    <span class="ml-2 font-medium">{{ $estadisticasCliente['ultimo_pedido']['fecha'] }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Total:</span>
                                    <span class="ml-2 font-medium">${{ number_format($estadisticasCliente['ultimo_pedido']['total'], 2, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Estado:</span>
                                    <span class="ml-2">
                                        @php
                                            $estadoColors = [
                                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                                'en_preparacion' => 'bg-blue-100 text-blue-800',
                                                'listo' => 'bg-purple-100 text-purple-800',
                                                'en_camino' => 'bg-indigo-100 text-indigo-800',
                                                'entregado' => 'bg-green-100 text-green-800',
                                                'cancelado' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs {{ $estadoColors[$estadisticasCliente['ultimo_pedido']['estado']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $estadisticasCliente['ultimo_pedido']['estado'])) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-gray-500">Este cliente aún no ha realizado ningún pedido.</p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end border-t border-gray-200 pt-4">
                    <button wire:click="closeStatsModal" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Cerrar
                    </button>
                </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlays -->
    <x-loading-overlay target="toggleBlock" message="Actualizando estado del cliente..." />
</div>


<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Registros de Actividad</h1>
                <p class="text-gray-500">Seguimiento de acciones realizadas por los empleados</p>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Registros -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-file-alt text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Hoy -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Hoy</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stats['hoy'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empleado</label>
                        <select wire:model.live="filtroEmpleado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Todos</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}">{{ $empleado->name }} ({{ $empleado->role }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                        <select wire:model.live="filtroAccion" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Todas</option>
                            @foreach($acciones as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input type="date" wire:model.live="filtroFecha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda</label>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="busqueda"
                            placeholder="Buscar..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500"
                        >
                    </div>
                    <div class="flex items-end">
                        <button
                            wire:click="limpiarFiltros"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Limpiar
                        </button>
                    </div>
                </div>

                <!-- Filtros activos -->
                @if($filtroEmpleado || $filtroAccion || $filtroFecha || $filtroModelo || $busqueda)
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                        <span class="text-sm text-gray-600 font-semibold">Filtros activos:</span>

                        @if($filtroEmpleado)
                            @php
                                $empleadoSeleccionado = $empleados->firstWhere('id', $filtroEmpleado);
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Empleado: {{ $empleadoSeleccionado ? $empleadoSeleccionado->name : 'N/A' }}
                                <button wire:click="$set('filtroEmpleado', '')" class="ml-1 text-orange-600 hover:text-orange-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($filtroAccion)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Acción: {{ $acciones[$filtroAccion] ?? $filtroAccion }}
                                <button wire:click="$set('filtroAccion', '')" class="ml-1 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif

                        @if($filtroModelo)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Tipo: {{ $modelos[$filtroModelo] ?? $filtroModelo }}
                                <button wire:click="$set('filtroModelo', '')" class="ml-1 text-green-600 hover:text-green-800">
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

                        @if($busqueda)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                "{{ $busqueda }}"
                                <button wire:click="$set('busqueda', '')" class="ml-1 text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                    </div>
                @endif
            </div>

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

            <!-- Tabla de Registros -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($registros as $registro)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $registro->user->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $registro->user->role ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($registro->action === 'pedido.estado_cambiado') bg-blue-100 text-blue-800
                                            @elseif($registro->action === 'pedido.asignado_delivery') bg-purple-100 text-purple-800
                                            @elseif($registro->action === 'pedido.actualizado') bg-yellow-100 text-yellow-800
                                            @elseif($registro->action === 'pedido.cancelado') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $registro->action_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $registro->entity_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ Str::limit($registro->description, 60) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $registro->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $registro->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            wire:click="verDetalles({{ $registro->id }})"
                                            class="text-orange-600 hover:text-orange-900 mr-3"
                                            title="Ver detalles"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                            <p class="text-gray-500 text-lg font-semibold">No se encontraron registros</p>
                                            <p class="text-gray-400 text-sm mt-2">Intenta ajustar los filtros de búsqueda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($registros->hasPages())
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $registros->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Modal de Detalles -->
    @if($showDetailModal && $registroSeleccionado)
        <!-- Backdrop -->
        <div wire:click="closeDetailModal"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
        </div>

        <!-- Modal Centrado -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col animate-scale-in pointer-events-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-white/20 p-3 rounded-full">
                                <i class="fas fa-file-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">Detalles del Registro</h3>
                                <p class="text-orange-100 text-sm mt-1">Información completa de la actividad</p>
                            </div>
                        </div>
                        <button wire:click="closeDetailModal" class="text-white hover:text-orange-100 transition-colors">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <!-- Información del Empleado -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Empleado</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Nombre:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $registroSeleccionado->user->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Rol:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $registroSeleccionado->user->role ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Email:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $registroSeleccionado->user->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Acción -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Acción</h4>
                        <div class="text-sm">
                            <span class="text-gray-500">Tipo:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $registroSeleccionado->action_label }}</span>
                        </div>
                        <div class="text-sm mt-2">
                            <span class="text-gray-500">Descripción:</span>
                            <p class="ml-2 mt-1 text-gray-900">{{ $registroSeleccionado->description }}</p>
                        </div>
                    </div>

                    <!-- Entidad Afectada -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Entidad Afectada</h4>
                        <div class="text-sm">
                            <span class="text-gray-500">{{ $registroSeleccionado->entity_name }}</span>
                        </div>
                    </div>

                    <!-- Valores Anteriores y Nuevos -->
                    @if($registroSeleccionado->old_values || $registroSeleccionado->new_values)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($registroSeleccionado->old_values)
                                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                    <h4 class="font-semibold text-red-700 mb-2">Valores Anteriores</h4>
                                    <pre class="text-xs text-gray-700 overflow-auto">{{ json_encode($registroSeleccionado->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                            @if($registroSeleccionado->new_values)
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <h4 class="font-semibold text-green-700 mb-2">Valores Nuevos</h4>
                                    <pre class="text-xs text-gray-700 overflow-auto">{{ json_encode($registroSeleccionado->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Información Adicional -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Información Adicional</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Fecha/Hora:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $registroSeleccionado->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200 flex-shrink-0">
                    <button
                        wire:click="closeDetailModal"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


<div class="p-6 space-y-6 bg-white/80  rounded-3xl w-full max-w-none">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl p-6 text-white">
    <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-users text-orange-600 mr-2"></i>
                    Gestión de Empleados
                </h2>
                <p class="text-gray-600 mt-1">Administra todos los empleados del restaurante</p>
            </div>
            <button wire:click="createEmpleado" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-300">
                <i class="fas fa-plus mr-2"></i>
                Nuevo Empleado
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Empleados</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $empleados->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-user-tie text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Activos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $empleados->where('email_verified_at', '!=', null)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-utensils text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Cocina</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $empleados->where('role', 'cocina')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-motorcycle text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Delivery</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $empleados->where('role', 'delivery')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Empleado</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           wire:model.live="search"
                           placeholder="Nombre o email..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Rol</label>
                <select wire:model.live="roleFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Todos los roles</option>
                    <option value="admin">Administrador</option>
                    <option value="empleado">Empleado</option>
                    <option value="cocina">Cocina</option>
                    <option value="ventas">Ventas</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="$set('search', ''); $set('roleFilter', '')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="bg-white rounded-lg shadow-sm border border-orange-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Lista de Empleados ({{ $empleados->total() }} empleados)</h3>
        </div>
        
        @if($empleados->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($empleados as $empleado)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($empleado->name, 0, 2)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $empleado->name }}</div>
                                            <div class="text-sm text-gray-500">ID: #{{ $empleado->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $empleado->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'empleado' => 'bg-blue-100 text-blue-800',
                                            'cocina' => 'bg-yellow-100 text-yellow-800',
                                            'ventas' => 'bg-green-100 text-green-800',
                                            'delivery' => 'bg-purple-100 text-purple-800'
                                        ];
                                        $roleIcons = [
                                            'admin' => 'fas fa-crown',
                                            'empleado' => 'fas fa-user',
                                            'cocina' => 'fas fa-utensils',
                                            'ventas' => 'fas fa-cash-register',
                                            'delivery' => 'fas fa-motorcycle'
                                        ];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColors[$empleado->role ?? 'empleado'] }}">
                                        <i class="{{ $roleIcons[$empleado->role ?? 'empleado'] }} mr-1"></i>
                                        {{ ucfirst($empleado->role ?? 'Empleado') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($empleado->email_verified_at)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $empleado->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="editEmpleado({{ $empleado->id }})" 
                                                class="text-orange-600 hover:text-orange-900 p-2 rounded-lg hover:bg-orange-50 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        @if($empleado->id !== auth()->id())
                                            <button wire:click="deleteEmpleado({{ $empleado->id }})" 
                                                    wire:confirm="¿Estás seguro de que quieres eliminar a {{ $empleado->name }}?"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <span class="text-gray-400 p-2">
                                                <i class="fas fa-user-shield" title="No puedes eliminarte a ti mismo"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200">
                {{ $empleados->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="mx-auto h-24 w-24 text-gray-300 mb-4">
                    <i class="fas fa-users text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay empleados</h3>
                <p class="text-gray-500 mb-6">No se encontraron empleados que coincidan con los filtros aplicados.</p>
                <button wire:click="createEmpleado" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primer Empleado
                </button>
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 " aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background Overlay - Fully Transparent -->
            <div class="fixed inset-0 bg-white/10 backdrop-blur-sm rounded-3xl"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto transform transition-all p-4">
                <!-- Close button outside modal -->
                <button wire:click="closeModal" class="absolute -top-12 -right-2 text-white hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                    <form wire:submit.prevent="saveEmpleado">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                        {{ $modalTitle }}
                                    </h3>
                                    
                                    <!-- Name Field -->
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                        <input type="text" 
                                               id="name"
                                               wire:model="name" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                                               placeholder="Ingresa el nombre completo">
                                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email Field -->
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                                        <input type="email" 
                                               id="email"
                                               wire:model="email" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror"
                                               placeholder="correo@ejemplo.com">
                                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Password Field -->
                                    <div class="mb-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            Contraseña
                                            @if($empleadoId) <span class="text-gray-500">(Dejar vacío para no cambiar)</span> @endif
                                        </label>
                                        <input type="password" 
                                               id="password"
                                               wire:model="password" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                                               placeholder="Contraseña segura">
                                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Role Field -->
                                    <div class="mb-4">
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rol del Empleado</label>
                                        <select id="role"
                                                wire:model="role" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 @error('role') border-red-500 @enderror">
                                            <option value="empleado">Empleado</option>
                                            <option value="admin">Administrador</option>
                                            <option value="cocina">Cocina</option>
                                            <option value="ventas">Ventas</option>
                                            <option value="delivery">Delivery</option>
                                        </select>
                                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-500 text-base font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                {{ $empleadoId ? 'Actualizar' : 'Crear' }} Empleado
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    @endif
</div>

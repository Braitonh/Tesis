<div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

    <!-- Modal Container -->
    <div class="relative bg-white rounded-2xl shadow-lg max-w-2xl w-full overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $modalTitle }}</h2>
                    <p class="text-orange-100 text-sm">{{ $empleadoId ? 'Actualiza la información del empleado' : 'Agrega un nuevo miembro al equipo' }}</p>
                </div>
            </div>
            <button wire:click="closeModal" class="text-white hover:text-orange-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form Section -->
        <div class="p-6 lg:p-8">
            <!-- Form -->
            <form wire:submit.prevent="saveEmpleado" class="space-y-5">

                <!-- Name field -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nombre Completo
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Ingresa el nombre completo">
                    </div>
                    @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- DNI field -->
                <div class="space-y-2">
                    <label for="dni" class="block text-sm font-medium text-gray-700">
                        DNI / Cédula
                    </label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input
                            type="text"
                            id="dni"
                            wire:model="dni"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('dni') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="12.345.678">
                    </div>
                    @error('dni')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="correo@ejemplo.com">
                    </div>
                    @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Telefono field -->
                <div class="space-y-2">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">
                        Teléfono
                    </label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input
                            type="tel"
                            id="telefono"
                            wire:model="telefono"
                            pattern="[0-9+\s\-()]*"
                            inputmode="numeric"
                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 43 || event.charCode === 32 || event.charCode === 45 || event.charCode === 40 || event.charCode === 41"
                            oninput="this.value = this.value.replace(/[^0-9+\s\-()]/g, '')"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('telefono') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="+595 123 456 789">
                    </div>
                    @error('telefono')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Direccion field -->
                <div class="space-y-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">
                        Dirección
                    </label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input
                            type="text"
                            id="direccion"
                            wire:model="direccion"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('direccion') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Calle, número">
                    </div>
                    @error('direccion')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role field -->
                <div class="space-y-2">
                    <label for="role" class="block text-sm font-medium text-gray-700">
                        Rol del Empleado
                    </label>
                    <div class="relative">
                        <i class="fas fa-user-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <select
                            id="role"
                            wire:model="role"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-base transition-all duration-200 bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('role') border-red-500 focus:ring-red-500 @enderror appearance-none">
                            <option value="cocina">Cocina</option>
                            <option value="ventas">Ventas</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    @error('role')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <!-- Submit button -->
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="saveEmpleado"
                    class="mt-4 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 hover:from-orange-600 hover:to-orange-700 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">

                    <span wire:loading.remove wire:target="saveEmpleado" class="flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        {{ $empleadoId ? 'Actualizar' : 'Crear' }} Empleado
                    </span>

                    <span wire:loading wire:target="saveEmpleado" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ $empleadoId ? 'Actualizando...' : 'Creando...' }}
                    </span>

                </button>

                <!-- Close button -->
                <button
                    type="button"
                    wire:click="closeModal"
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    <span class="flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cerrar
                    </span>
                </button>
            </form>


        </div>
    </div>
</div>
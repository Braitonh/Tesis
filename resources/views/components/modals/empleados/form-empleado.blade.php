<div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-white/10 backdrop-blur-sm"></div>

    <!-- Modal Container - Login Style -->
    <div class="login-container bg-white rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.2)] overflow-hidden max-w-[1200px] w-full grid grid-cols-1 lg:grid-cols-2 relative" style="animation: slideUp 0.8s ease forwards;">

        <!-- Welcome Section -->
        <div class="welcome-section bg-gradient-to-br from-amber-900 to-amber-800 p- lg:p-12 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">

            <!-- Background decorations -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-400/20 rounded-full" style="animation: pulse 4s ease-in-out infinite;"></div>
            <div class="absolute -bottom-8 -left-8 w-36 h-36 bg-orange-500/20 rounded-full" style="animation: pulse 4s ease-in-out infinite 2s;"></div>

            <div class="relative z-10">
                <!-- Logo section -->
                <div class="mb-8">
                    <div class="flex items-center justify-center gap-3 text-3xl font-bold mb-5">
                        <div class="bg-white text-orange-500 w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        FoodDesk
                    </div>
                </div>

                <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">
                    {{ $empleadoId ? '¡Actualizar Empleado!' : '¡Nuevo Empleado!' }}
                </h1>
                <p class="text-lg opacity-90 leading-relaxed mb-8">
                    {{ $empleadoId ? 'Modifica la información del empleado para mantener los datos actualizados.' : 'Completa la información para agregar un nuevo miembro al equipo. El empleado recibirá un email para crear su contraseña.' }}
                </p>

                <!-- Food illustration -->
                <div class="flex justify-center gap-5 mt-8">
                    <span class="food-item text-4xl">🍕</span>
                    <span class="food-item text-4xl">🍔</span>
                    <span class="food-item text-4xl">🌮</span>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-4 lg:p-8 flex flex-col justify-center relative">

            <!-- Close button -->
            <button wire:click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Form header -->
            <div class="text-center mb-10">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">{{ $modalTitle }}</h2>
                <p class="text-gray-600">Complete la información del empleado</p>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveEmpleado" class="space-y-4">

                <!-- Name field -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-gray-800">
                        Nombre Completo
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('name') border-red-500 @enderror"
                            placeholder="Ingresa el nombre completo">
                    </div>
                    @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- DNI field -->
                <div class="space-y-2">
                    <label for="dni" class="block text-sm font-semibold text-gray-800">
                        DNI / Cédula
                    </label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input
                            type="text"
                            id="dni"
                            wire:model="dni"
                            class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('dni') border-red-500 @enderror"
                            placeholder="12.345.678">
                    </div>
                    @error('dni')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-800">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('email') border-red-500 @enderror"
                            placeholder="correo@ejemplo.com">
                    </div>
                    @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Telefono field -->
                <div class="space-y-2">
                    <label for="telefono" class="block text-sm font-semibold text-gray-800">
                        Teléfono
                    </label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input
                            type="tel"
                            id="telefono"
                            wire:model="telefono"
                            class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('telefono') border-red-500 @enderror"
                            placeholder="+595 123 456 789">
                    </div>
                    @error('telefono')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Direccion field -->
                <div class="space-y-2">
                    <label for="direccion" class="block text-sm font-semibold text-gray-800">
                        Dirección
                    </label>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input
                            type="text"
                            id="direccion"
                            wire:model="direccion"
                            class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('direccion') border-red-500 @enderror"
                            placeholder="Calle, número.">
                    </div>
                    @error('direccion')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role field -->
                <div class="space-y-2">
                    <label for="role" class="block text-sm font-semibold text-gray-800">
                        Rol del Empleado
                    </label>
                    <div class="relative">
                        <i class="fas fa-user-tag absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <select
                            id="role"
                            wire:model="role"
                            class="w-full pl-14 pr-5 py-3 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('role') border-red-500 @enderror">
                            <option value="cocina">Cocina</option>
                            <option value="ventas">Ventas</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    @error('role')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                @if(!$empleadoId)
                <!-- Information about email verification for new employees -->
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="text-blue-600 text-lg">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-blue-800 mb-1">Configuración automática</h3>
                            <p class="text-sm text-blue-700">
                                Se enviará un email al empleado con un enlace seguro para que cree su contraseña.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit button -->
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="saveEmpleado"
                    class="mt-2 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-3 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">

                    <span wire:loading.remove wire:target="saveEmpleado" class="flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        {{ $empleadoId ? 'Actualizar' : 'Crear' }} Empleado
                    </span>

                    <span wire:loading wire:target="saveEmpleado" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ $empleadoId ? 'Actualizando...' : 'Creando...' }}
                    </span>

                </button>
            </form>


        </div>
    </div>
</div>
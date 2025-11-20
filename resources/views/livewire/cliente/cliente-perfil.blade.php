<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('cliente.bienvenida') }}"
                   class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al menú
                </a>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Mi Perfil</h1>
                <p class="text-gray-600">Gestiona tu información personal</p>
            </div>

            <!-- Formulario de Perfil -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form wire:submit="actualizarPerfil">
                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-orange-600 mr-2"></i>
                            Nombre Completo
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            wire:model="name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('name') border-red-500 @enderror"
                            placeholder="Ingresa tu nombre completo"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-orange-600 mr-2"></i>
                            Correo Electrónico
                        </label>
                        <input 
                            type="email" 
                            id="email"
                            wire:model="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('email') border-red-500 @enderror"
                            placeholder="tu@email.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

   

                    <!-- Dirección -->
                    <div class="mb-6">
                        <label for="direccion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-orange-600 mr-2"></i>
                            Dirección
                        </label>
                        <textarea 
                            id="direccion"
                            wire:model="direccion"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 resize-none @error('direccion') border-red-500 @enderror"
                            placeholder="Ingresa tu dirección completa"
                        ></textarea>
                        @error('direccion')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-8">
                        <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone text-orange-600 mr-2"></i>
                            Teléfono <span class="text-gray-400 font-normal text-xs">(opcional)</span>
                        </label>
                        <input 
                            type="text" 
                            id="telefono"
                            wire:model="telefono"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 @error('telefono') border-red-500 @enderror"
                            placeholder="0981234567"
                        >
                        @error('telefono')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="actualizarPerfil">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Cambios
                            </span>
                            <span wire:loading wire:target="actualizarPerfil">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Guardando...
                            </span>
                        </button>
                        <a 
                            href="{{ route('cliente.bienvenida') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold transition-colors text-center"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

</div>



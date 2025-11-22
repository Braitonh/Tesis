<div>
    <!-- Register container -->
    <div class="login-container bg-white rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,0.2)] overflow-hidden max-w-[900px] w-full grid grid-cols-1 lg:grid-cols-2 relative">
        
        <!-- Welcome section -->
        <div class="welcome-section bg-gradient-to-br from-amber-900 to-amber-800 p-10 lg:p-16 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">
            
            
            <!-- Background decorations -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-yellow-400/20 rounded-full" style="animation: pulse 4s ease-in-out infinite;"></div>
            <div class="absolute -bottom-8 -left-8 w-36 h-36 bg-orange-500/20 rounded-full" style="animation: pulse 4s ease-in-out infinite 2s;"></div>
            
            <div class="relative z-10">
                <!-- Logo section -->
                <div class="mb-8">
                    <div class="flex items-center justify-center gap-3 text-3xl font-bold mb-5">
                        <div class="bg-white text-emerald-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        FoodDesk
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">¡Únete a nosotros!</h1>
                <p class="text-lg opacity-90 leading-relaxed mb-8">
                    Crea tu cuenta y comienza a disfrutar de nuestros productos.
                </p>
{{--                 
                <!-- Food illustration -->
                <div class="flex justify-center gap-5 mt-8">
                    <span class="food-item text-4xl">👨‍🍳</span>
                    <span class="food-item text-4xl">📱</span>
                    <span class="food-item text-4xl">🚀</span>
                </div> --}}
            </div>
        </div>

        <!-- Register section -->
        <div class="p-10 lg:p-16 flex flex-col justify-center">
            
            <!-- Register header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Crear Cuenta</h2>
                <p class="text-gray-600">Completa tus datos para comenzar</p>
            </div>

            <!-- Register form -->
            <form wire:submit.prevent="register" class="space-y-5">
                
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
                            class="w-full pl-14 pr-5 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(16,185,129,0.1)] @error('name') border-red-500 @enderror"
                            placeholder="Tu nombre completo"
                        >
                    </div>
                    @error('name')
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
                            class="w-full pl-14 pr-5 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(16,185,129,0.1)] @error('email') border-red-500 @enderror"
                            placeholder="tu@email.com"
                        >
                    </div>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password field -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-800">
                        Contraseña
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input 
                            type="password" 
                            id="password"
                            wire:model="password"
                            class="w-full pl-14 pr-14 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(16,185,129,0.1)] @error('password') border-red-500 @enderror"
                            placeholder="Mínimo 8 caracteres"
                        >
                        <i class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer text-lg mt-0.5 hover:text-orange-500 transition-colors duration-300"></i>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password confirmation field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-800">
                        Confirmar Contraseña
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                        <input 
                            type="password" 
                            id="password_confirmation"
                            wire:model="password_confirmation"
                            class="w-full pl-14 pr-14 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(16,185,129,0.1)] @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Confirma tu contraseña"
                        >
                        <i class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer text-lg mt-0.5 hover:text-orange-500 transition-colors duration-300"></i>
                    </div>
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Register button -->
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="mt-4 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-4 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                    
                    <span class="flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        Crear Cuenta
                    </span>
 
                </button>
            </form>

            <!-- Login link -->
            <div class="text-center mt-6 text-gray-600 text-sm">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="text-emerald-600 font-semibold hover:underline" wire:navigate>
                    Inicia sesión aquí
                </a>
            </div>
        </div>
    </div>
</div>
<div>
    <!-- Forgot Password container -->
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
                        <div class="bg-white text-orange-500 w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                            <i class="fas fa-utensils"></i>
                        </div>
                        FoodDesk
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">¿Olvidaste tu contraseña?</h1>
                <p class="text-lg opacity-90 leading-relaxed mb-8">
                    No te preocupes, te enviaremos un enlace para restablecer tu contraseña a tu correo electrónico.
                </p>
                
                <!-- Food illustration -->
                <div class="flex justify-center gap-5 mt-8">
                    <span class="food-item text-4xl">🔐</span>
                    <span class="food-item text-4xl">📧</span>
                    <span class="food-item text-4xl">🔑</span>
                </div>
            </div>
        </div>

        <!-- Forgot Password section -->
        <div class="p-10 lg:p-16 flex flex-col justify-center">
            
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Restablecer Contraseña</h2>
                <p class="text-gray-600">Ingresa tu correo electrónico y te enviaremos un enlace de restablecimiento</p>
            </div>

            <!-- Success message -->
            @if($emailSent || session('status'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                    {{ session('status') ?? 'Recibirás un enlace de restablecimiento a tu correo electrónico.' }}
                </div>
                
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-orange-500 font-semibold hover:underline" wire:navigate>
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al inicio de sesión
                    </a>
                </div>
            @else
                <!-- Forgot Password form -->
                <form wire:submit.prevent="sendResetLink" method="POST" class="space-y-6">
                    
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
                                class="w-full pl-14 pr-5 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('email') border-red-500 @enderror"
                                placeholder="tu@email.com"
                                autofocus
                            >
                        </div>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit button -->
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="sendResetLink"
                        class="mt-4 w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-4 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed">
                        
                        <span class="flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            <span wire:loading.remove wire:target="sendResetLink">Enviar Enlace</span>
                            <span wire:loading wire:target="sendResetLink">Enviando...</span>
                        </span>

                    </button>
                </form>

                <!-- Back to login link -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-orange-500 text-sm font-medium hover:text-orange-600 hover:underline transition-colors duration-300" wire:navigate>
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al inicio de sesión
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>


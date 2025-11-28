<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Restablecer Contraseña - FoodDesk</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom animations for floating shapes */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-shape {
            animation: float 6s ease-in-out infinite;
        }

        .bg-shape:nth-child(1) {
            animation-delay: 0s;
        }

        .bg-shape:nth-child(2) {
            animation-delay: 2s;
        }

        .bg-shape:nth-child(3) {
            animation-delay: 4s;
        }

        .bg-shape:nth-child(4) {
            animation-delay: 1s;
        }

        .welcome-section::before {
            animation: pulse 4s ease-in-out infinite;
        }

        .welcome-section::after {
            animation: pulse 4s ease-in-out infinite 2s;
        }

        .food-item {
            animation: bounce 2s ease-in-out infinite;
        }

        .food-item:nth-child(2) {
            animation-delay: 0.5s;
        }

        .food-item:nth-child(3) {
            animation-delay: 1s;
        }

        .login-container {
            animation: slideUp 0.8s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Animated background -->
    <div class="fixed inset-0 bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden pointer-events-none">
        <!-- Floating shapes -->
        <div class="bg-shape absolute w-48 h-48 bg-white/10 rounded-full top-[10%] left-[10%]"></div>
        <div class="bg-shape absolute w-36 h-36 bg-white/10 rounded-full top-[60%] right-[15%]"></div>
        <div class="bg-shape absolute w-24 h-24 bg-white/10 rounded-full bottom-[20%] left-[20%]"></div>
        <div class="bg-shape absolute w-20 h-20 bg-white/10 rounded-full top-[30%] right-[30%]"></div>
    </div>

    <!-- Main content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-5">
        <div>
            <!-- Reset Password container -->
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
                                    <i class="fas fa-key"></i>
                                </div>
                                FoodDesk
                            </div>
                        </div>
                        
                        <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">Restablecer Contraseña</h1>
                        <p class="text-lg opacity-90 leading-relaxed mb-8">
                            Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.
                        </p>
                        
                        <!-- Food illustration -->
                        <div class="flex justify-center gap-5 mt-8">
                            <span class="food-item text-4xl">🔐</span>
                            <span class="food-item text-4xl">🔑</span>
                            <span class="food-item text-4xl">✅</span>
                        </div>
                    </div>
                </div>

                <!-- Reset Password section -->
                <div class="p-10 lg:p-16 flex flex-col justify-center">
                    
                    <!-- Reset Password header -->
                    <div class="text-center mb-10">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Nueva Contraseña</h2>
                        <p class="text-gray-600">Ingresa tu nueva contraseña de acceso</p>
                    </div>

                    <!-- Messages -->
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Reset Password form -->
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- Email field (read-only) -->
                        <div class="space-y-2">
                            <label for="email_display" class="block text-sm font-semibold text-gray-800">
                                Correo Electrónico
                            </label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <input 
                                    type="email" 
                                    id="email_display" 
                                    value="{{ $email }}"
                                    readonly
                                    class="w-full pl-14 pr-5 py-4 border-2 border-gray-200 rounded-xl text-base bg-gray-100 text-gray-600 cursor-not-allowed"
                                >
                            </div>
                        </div>

                        <!-- Password field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-gray-800">
                                Nueva Contraseña
                            </label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    autofocus
                                    class="w-full pl-14 pr-14 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('password') border-red-500 @enderror"
                                    placeholder="Ingresa tu nueva contraseña"
                                >
                                <i class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer text-lg mt-0.5 hover:text-orange-500 transition-colors duration-300"></i>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password field -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-800">
                                Confirmar Contraseña
                            </label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg mt-0.5"></i>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required 
                                    class="w-full pl-14 pr-14 py-4 border-2 border-gray-200 rounded-xl text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-orange-500 focus:bg-white focus:shadow-[0_0_0_3px_rgba(255,107,53,0.1)] @error('password_confirmation') border-red-500 @enderror"
                                    placeholder="Confirma tu contraseña"
                                >
                                <i class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer text-lg mt-0.5 hover:text-orange-500 transition-colors duration-300"></i>
                            </div>
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        @error('email')
                            <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                                {{ $message }}
                            </div>
                        @enderror

                        <!-- Submit button -->
                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-4 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden">
                            
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-key"></i>
                                Restablecer Contraseña
                            </span>
                        </button>
                    </form>

                    <!-- Back to login link -->
                    <div class="text-center mt-6">
                        <a href="{{ route('login') }}" class="text-orange-500 text-sm font-medium hover:text-orange-600 hover:underline transition-colors duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al inicio de sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


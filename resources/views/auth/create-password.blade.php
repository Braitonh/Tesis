<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Crear Contraseña - FoodDesk</title>

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
            <!-- Login container -->
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
                        
                        <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">¡Bienvenido {{ $user->name }}!</h1>
                        <p class="text-lg opacity-90 leading-relaxed mb-8">
                            Crea tu contraseña para completar la configuración de tu cuenta y acceder a todas las funciones del sistema.
                        </p>
                        
                        <!-- Food illustration -->
                        <div class="flex justify-center gap-5 mt-8">
                            <span class="food-item text-4xl">🔐</span>
                            <span class="food-item text-4xl">✅</span>
                            <span class="food-item text-4xl">🚀</span>
                        </div>
                    </div>
                </div>

                <!-- Password creation section -->
                <div class="p-10 lg:p-16 flex flex-col justify-center">
                    
                    <!-- Password creation header -->
                    <div class="text-center mb-10">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Crear Contraseña</h2>
                        <p class="text-gray-600">Define tu contraseña de acceso</p>
                    </div>

                    <!-- Messages -->
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-6 p-4 bg-blue-100 border border-blue-300 text-blue-700 rounded-lg">
                            {{ session('info') }}
                        </div>
                    @endif

                    <!-- Password creation form -->
                    <form method="POST" action="{{ route('password.create', $token) }}" class="space-y-6">
                        @csrf

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

                        <!-- Submit button -->
                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-4 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden">
                            
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-key"></i>
                                Crear Contraseña
                            </span>
                        </button>
                    </form>

                    <!-- Password requirements -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-xl">
                        <h3 class="text-sm font-semibold text-gray-800 mb-4">
                            <i class="fas fa-shield-alt mr-2 text-orange-500"></i>Requisitos de contraseña:
                        </h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3 text-xs"></i>
                                <span>Mínimo 8 caracteres</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3 text-xs"></i>
                                <span>Al menos una letra mayúscula</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3 text-xs"></i>
                                <span>Al menos una letra minúscula</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3 text-xs"></i>
                                <span>Al menos un número</span>
                            </li>
                        </ul>
                    </div>


                    <!-- Security note -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Tu contraseña será encriptada y almacenada de forma segura
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
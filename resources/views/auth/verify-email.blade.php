<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verificación Pendiente - FoodDesk</title>

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
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                FoodDesk
                            </div>
                        </div>
                        
                        <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">¡Hola {{ $user->name }}!</h1>
                        <p class="text-lg opacity-90 leading-relaxed mb-8">
                            @if(!$user->password_created && $user->role !== 'cliente')
                                Hemos enviado un email con instrucciones para que crees tu contraseña de forma segura.
                            @elseif($user->role === 'cliente')
                                Hemos enviado un email de verificación a tu correo electrónico. Por favor, revisa tu bandeja de entrada para activar tu cuenta.
                            @else
                                Tu cuenta necesita verificación para acceder al sistema completo.
                            @endif
                        </p>
                        
                        <!-- Food illustration -->
                        <div class="flex justify-center gap-5 mt-8">
                            <span class="food-item text-4xl">📧</span>
                            <span class="food-item text-4xl">🔐</span>
                            <span class="food-item text-4xl">✅</span>
                        </div>
                    </div>
                </div>

                <!-- Verification section -->
                <div class="p-10 lg:p-16 flex flex-col justify-center">
                    
                    <!-- Verification header -->
                    <div class="text-center mb-10">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Verificación Pendiente</h2>
                        <p class="text-gray-600">Revisa tu email para continuar</p>
                    </div>

                    <!-- Messages -->
                    @if(session('status'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(!$user->password_created && $user->role !== 'cliente')
                        <!-- Employee verification info -->
                        <div class="space-y-6">
                            <!-- Email info -->
                            <div class="p-6 bg-amber-50 border border-amber-200 rounded-xl">
                                <div class="flex items-start gap-4">
                                    <div class="text-amber-600 text-2xl">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-amber-800 mb-2">Email enviado</h3>
                                        <p class="text-sm text-amber-700 mb-3">
                                            Hemos enviado un email a <strong>{{ $user->email }}</strong> con un enlace para crear tu contraseña.
                                        </p>
                                        <p class="text-xs text-amber-600">
                                            Una vez que crees tu contraseña, tu cuenta será verificada automáticamente.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-sm font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-list-ol mr-2 text-orange-500"></i>Instrucciones:
                                </h3>
                                <ul class="text-sm text-gray-600 space-y-3">
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">1</span>
                                        <span>Revisa tu bandeja de entrada de email</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">2</span>
                                        <span>También revisa la carpeta de spam o correo no deseado</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">3</span>
                                        <span>Haz clic en el enlace "Crear mi contraseña"</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">4</span>
                                        <span>Crea una contraseña segura y confirma</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Resend email button -->
                            <form method="POST" action="{{ route('verification.resend') }}" class="space-y-4">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-4 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden">
                                    <span class="flex items-center justify-center gap-2">
                                        <i class="fas fa-paper-plane"></i>
                                        Reenviar Email
                                    </span>
                                </button>
                            </form>
                        </div>
                    @elseif($user->role === 'cliente')
                        <!-- Client verification info -->
                        <div class="space-y-6">
                            <!-- Email info -->
                            <div class="p-6 bg-amber-50 border border-amber-200 rounded-xl">
                                <div class="flex items-start gap-4">
                                    <div class="text-amber-600 text-2xl">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-amber-800 mb-2">Email de verificación enviado</h3>
                                        <p class="text-sm text-amber-700 mb-3">
                                            Hemos enviado un email a <strong>{{ $user->email }}</strong> con un enlace para activar tu cuenta.
                                        </p>
                                        <p class="text-xs text-amber-600">
                                            Una vez que actives tu cuenta, podrás realizar pedidos y acceder a todas las funcionalidades.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-sm font-semibold text-gray-800 mb-4">
                                    <i class="fas fa-list-ol mr-2 text-orange-500"></i>Instrucciones:
                                </h3>
                                <ul class="text-sm text-gray-600 space-y-3">
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">1</span>
                                        <span>Revisa tu bandeja de entrada de email</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">2</span>
                                        <span>También revisa la carpeta de spam o correo no deseado</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">3</span>
                                        <span>Haz clic en el enlace "Activar Mi Cuenta" del email</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="bg-orange-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-medium mt-0.5">4</span>
                                        <span>Tu cuenta será activada automáticamente</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Resend email button -->
                            <form method="POST" action="{{ route('verification.resend') }}" class="space-y-4">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white border-0 py-4 px-6 rounded-xl text-base font-semibold cursor-pointer transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(255,107,53,0.3)] active:translate-y-0 relative overflow-hidden">
                                    <span class="flex items-center justify-center gap-2">
                                        <i class="fas fa-paper-plane"></i>
                                        Reenviar Email de Verificación
                                    </span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- For other cases -->
                        <div class="space-y-6">
                            <div class="p-6 bg-blue-50 border border-blue-200 rounded-xl">
                                <div class="flex items-start gap-4">
                                    <div class="text-blue-600 text-2xl">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-blue-800 mb-2">Verificación Requerida</h3>
                                        <p class="text-sm text-blue-700 mb-3">
                                            Tu email <strong>{{ $user->email }}</strong> necesita ser verificado para acceder al sistema.
                                        </p>
                                        <p class="text-xs text-blue-600">
                                            Contacta con el administrador para más información sobre tu cuenta.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Divider -->
                    <div class="flex items-center gap-4 my-8">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-gray-500 text-sm bg-white px-3">o</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <!-- Logout button -->
                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button 
                            type="submit" 
                            class="text-gray-500 hover:text-orange-500 text-sm font-medium underline transition-colors duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
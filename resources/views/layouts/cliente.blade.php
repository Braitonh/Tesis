<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'FoodDesk') . ' - Cliente' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
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

        .food-item {
            animation: bounce 2s ease-in-out infinite;
        }

        .food-item:nth-child(2) {
            animation-delay: 0.5s;
        }

        .food-item:nth-child(3) {
            animation-delay: 1s;
        }

        .content-container {
            animation: slideUp 0.8s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Animated background -->
    <div class="fixed inset-0 bg-gradient-to-br from-orange-600 to-amber-600 overflow-hidden pointer-events-none">
        <!-- Floating shapes -->
        <div class="bg-shape absolute w-48 h-48 bg-orange-200/20 rounded-full top-[10%] left-[10%]"></div>
        <div class="bg-shape absolute w-36 h-36 bg-amber-200/20 rounded-full top-[60%] right-[15%]"></div>
        <div class="bg-shape absolute w-24 h-24 bg-orange-300/20 rounded-full bottom-[20%] left-[20%]"></div>
        <div class="bg-shape absolute w-20 h-20 bg-amber-300/20 rounded-full top-[30%] right-[30%]"></div>
    </div>

    <!-- Main content -->
    <div class="relative z-10 min-h-screen">
        <!-- Navigation Header -->
        <nav class="bg-white/95 backdrop-blur-md shadow-lg border-b border-orange-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Brand -->
                    <div class="flex items-center space-x-4">
                        <div class="bg-orange-500 p-2 rounded-lg">
                            <i class="fas fa-utensils text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">{{ config('app.name', 'FoodDesk') }}</h1>
                            <p class="text-sm text-orange-600">Panel Cliente</p>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- User Info -->
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-100 p-2 rounded-full">
                                    <i class="fas fa-user text-orange-600"></i>
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Cliente</p>
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden md:flex items-center space-x-4">
                                <a href="{{ route('cliente.bienvenida') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    <i class="fas fa-home mr-2"></i>Inicio
                                </a>
                            </div>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white/95 backdrop-blur-md shadow-sm border-b border-orange-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="text-gray-800 font-semibold text-xl">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="content-container">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/95 backdrop-blur-md border-t border-orange-100 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-2 text-gray-600">
                        <i class="fas fa-utensils text-orange-500"></i>
                        <span class="text-sm">© {{ date('Y') }} {{ config('app.name', 'FoodDesk') }}. Todos los derechos reservados.</span>
                    </div>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <a href="#" class="text-gray-500 hover:text-orange-600 text-sm">
                            <i class="fas fa-phone mr-1"></i>(123) 456-7890
                        </a>
                        <a href="#" class="text-gray-500 hover:text-orange-600 text-sm">
                            <i class="fas fa-envelope mr-1"></i>info@fooddesk.com
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FoodDesk') }}</title>

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
        /* Alpine.js cloak */
        [x-cloak] { display: none !important; }

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
        <!-- Navigation -->
        <x-dashboard-header>
            @isset($navigation)
                <x-slot name="navigation">
                    {{ $navigation }}
                </x-slot>
            @endisset
            
            @isset($mobileNavigation)
                <x-slot name="mobileNavigation">
                    {{ $mobileNavigation }}
                </x-slot>
            @endisset
        </x-dashboard-header>

        <!-- Horizontal Navigation Menu -->
        @isset($horizontalNav)
            <x-dashboard-nav :currentRoute="$currentRoute ?? null">
                {{ $horizontalNav }}
            </x-dashboard-nav>
        @else
            <x-dashboard-nav :currentRoute="$currentRoute ?? null" />
        @endisset

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm border-b border-orange-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="text-gray-800 font-semibold text-xl">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
        </main>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
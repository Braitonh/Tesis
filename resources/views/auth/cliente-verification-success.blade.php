<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Exitosa - FoodDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-orange-50 to-amber-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-6 text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-white rounded-full">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">¡Cuenta Activada!</h1>
            </div>

            <!-- Content -->
            <div class="px-8 py-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 mb-4">{{ $message }}</p>
                    
                    @if(!$alreadyVerified)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <p class="text-green-700 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                Tu cuenta está ahora activa y puedes comenzar a realizar pedidos.
                            </p>
                        </div>
                    @endif

                    <!-- User Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Información de tu cuenta:</h3>
                        <div class="text-left space-y-2 text-sm text-gray-600">
                            <p><strong>Nombre:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Estado:</strong> 
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>Verificado
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    @auth
                        <a href="{{ route('cliente.bienvenida') }}" 
                           class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 px-6 rounded-lg font-medium text-center block transition-all duration-300 hover:from-orange-600 hover:to-orange-700">
                            <i class="fas fa-utensils mr-2"></i>
                            Ir al Menú
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 px-6 rounded-lg font-medium text-center block transition-all duration-300 hover:from-orange-600 hover:to-orange-700">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión
                        </a>
                    @endauth
                    
                    <a href="{{ route('cliente.bienvenida') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium text-center block transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Ir a Inicio
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-utensils mr-1"></i>
                    ¡Gracias por elegir FoodDesk!
                </p>
            </div>
        </div>
    </div>
</body>
</html>




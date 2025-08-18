<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Verificación - FoodDesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-8 py-6 text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-white rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Error de Verificación</h1>
            </div>

            <!-- Content -->
            <div class="px-8 py-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 mb-4">{{ $message }}</p>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <p class="text-red-700 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            El enlace de verificación puede haber expirado o ser inválido.
                        </p>
                    </div>

                    <!-- Possible reasons -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Posibles causas:</h3>
                        <ul class="text-left space-y-1 text-sm text-gray-600">
                            <li><i class="fas fa-clock mr-2 text-red-400"></i>El enlace ha expirado (válido por 24 horas)</li>
                            <li><i class="fas fa-link mr-2 text-red-400"></i>El enlace está malformado o corrupto</li>
                            <li><i class="fas fa-check mr-2 text-green-400"></i>La cuenta ya fue verificada anteriormente</li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('login') }}" 
                       class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 px-6 rounded-lg font-medium text-center block transition-all duration-300 hover:from-orange-600 hover:to-orange-700">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Ir al Login
                    </a>
                    
                    <button onclick="history.back()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium text-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver Atrás
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center">
                <p class="text-sm text-gray-500">
                    <i class="fas fa-envelope mr-1"></i>
                    Contacta al administrador si el problema persiste
                </p>
            </div>
        </div>
    </div>
</body>
</html>
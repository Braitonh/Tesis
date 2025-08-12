<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">
            <i class="fas fa-users mr-3"></i>
            Módulo Cliente
        </h1>
        <p class="text-blue-100">Gestiona la experiencia del cliente y sus pedidos</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <button wire:click="$dispatch('navigateTo', 'cliente', 'pedidos')" class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all text-left">
            <div class="flex items-center">
                <div class="bg-orange-100 p-4 rounded-lg">
                    <i class="fas fa-shopping-cart text-orange-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Gestión de Pedidos</h3>
                    <p class="text-gray-600">Ver y administrar todos los pedidos</p>
                </div>
            </div>
        </button>

        <button wire:click="$dispatch('navigateTo', 'cliente', 'perfil')" class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all text-left">
            <div class="flex items-center">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <i class="fas fa-user text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Perfil de Cliente</h3>
                    <p class="text-gray-600">Gestionar información del cliente</p>
                </div>
            </div>
        </button>
    </div>
</div>
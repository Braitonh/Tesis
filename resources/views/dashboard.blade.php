<x-dashboard-layout>
    @if(auth()->user()->role === 'admin')
        <livewire:dashboard.dashboard-home />
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold mb-2">¡Bienvenido, {{ auth()->user()->name }}!</h1>
                                <p class="text-gray-600">Has iniciado sesión correctamente en FoodDesk.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-dashboard-layout>
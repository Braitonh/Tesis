<div>
    @if($showModal)
        <!-- Backdrop -->
        <div wire:click="toggleModal"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 animate-fade-in">
        </div>

        <!-- Modal Centrado -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col animate-scale-in">

        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        Mi Carrito
                    </h2>
                    <p class="text-orange-100 text-sm mt-1">{{ $count }} {{ $count === 1 ? 'producto' : 'productos' }}</p>
                </div>
                <button wire:click="toggleModal" class="text-white hover:text-orange-100 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            @forelse($items as $item)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-shadow" wire:key="cart-item-{{ $item->producto->id }}">
                    <div class="flex gap-4">
                        <!-- Product Image -->
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
                            <img src="{{ $item->producto->image_url }}"
                                 alt="{{ $item->producto->nombre }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full flex items-center justify-center" style="display: none;">
                                <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $item->producto->nombre }}</h3>
                            <p class="text-orange-600 font-bold text-lg">${{ number_format($item->precio, 2) }}</p>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-2 mt-2">
                                <button wire:click="decrement({{ $item->producto->id }})"
                                        class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-lg flex items-center justify-center transition-colors">
                                    <i class="fas fa-minus text-gray-600 text-xs"></i>
                                </button>

                                <span class="w-12 text-center font-semibold text-gray-800">{{ $item->cantidad }}</span>

                                <button wire:click="increment({{ $item->producto->id }})"
                                        class="w-8 h-8 bg-orange-500 hover:bg-orange-600 text-white rounded-lg flex items-center justify-center transition-colors">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>

                                <button wire:click="remove({{ $item->producto->id }})"
                                        class="ml-auto text-red-500 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-sm text-gray-600 mt-2">
                                Subtotal: <span class="font-semibold text-gray-800">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Tu carrito está vacío</p>
                    <p class="text-gray-400 text-sm mt-2">¡Agrega productos para comenzar!</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($count > 0)
            <div class="border-t border-gray-200 bg-white p-6 space-y-4">
                <!-- Total -->
                <div class="flex justify-between items-center text-lg font-bold">
                    <span class="text-gray-800">Total:</span>
                    <span class="text-orange-600 text-2xl">${{ number_format($total, 2) }}</span>
                </div>

                <!-- Actions -->
                <div class="space-y-2">
                    <a href="{{ route('cliente.checkout') }}"
                       class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Proceder al Checkout
                    </a>

                    <div class="grid grid-cols-2 gap-2">
                        <button wire:click="toggleModal"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Seguir Comprando
                        </button>

                        <button wire:click="confirmClear"
                                class="bg-red-100 hover:bg-red-200 text-red-600 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Vaciar
                        </button>
                    </div>
                </div>
            </div>
        @endif
            </div>
        </div>
    @endif

    <!-- Modal de Confirmación para Vaciar Carrito -->
    @if($showConfirmClear)
        <!-- Backdrop -->
        <div wire:click="cancelClear"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] animate-fade-in">
        </div>

        <!-- Modal de Confirmación Centrado -->
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-scale-in">
                <!-- Contenido del Modal -->
                <div class="p-6">
                    <!-- Icono de Advertencia -->
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                        </div>
                    </div>

                    <!-- Título -->
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">
                        ¿Vaciar carrito?
                    </h3>

                    <!-- Mensaje -->
                    <p class="text-gray-600 text-center mb-6">
                        Esta acción eliminará todos los productos de tu carrito. Esta acción no se puede deshacer.
                    </p>

                    <!-- Información adicional -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-amber-600 mt-0.5 mr-2"></i>
                            <p class="text-sm text-amber-800">
                                Se eliminarán <span class="font-bold">{{ $count }} {{ $count === 1 ? 'producto' : 'productos' }}</span> por un total de <span class="font-bold">${{ number_format($total, 2) }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3">
                        <button wire:click="cancelClear"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-xl font-semibold transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>

                        <button wire:click="clear"
                                class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-trash mr-2"></i>
                            Sí, Vaciar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
             style="display: none;">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif
</div>
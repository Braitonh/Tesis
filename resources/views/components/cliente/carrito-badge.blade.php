@php
    $count = 0;
    if (session()->has('cart')) {
        $cart = session('cart');
        $count = array_sum(array_column($cart, 'cantidad'));
    }
@endphp

<button @click="$dispatch('abrir-carrito')"
        class="relative group">
    <div class="bg-orange-500 hover:bg-orange-600 p-3 rounded-full transition-all duration-300 transform group-hover:scale-110 shadow-lg">
        <i class="fas fa-shopping-cart text-white text-xl"></i>
    </div>

    @if($count > 0)
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center animate-bounce shadow-lg">
            {{ $count > 99 ? '99+' : $count }}
        </span>
    @endif
</button>
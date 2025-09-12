<div x-data="foodStore()" class="w-full mx-auto bg-white p-8 sm:p-12 lg:p-16 rounded-2xl">
    <!-- Hero Section Mejorado -->
    <div class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 rounded-2xl overflow-hidden shadow-2xl mb-12">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-white rounded-full animate-pulse" style="animation-delay: 1s"></div>
            <div class="absolute bottom-20 left-32 w-12 h-12 bg-white rounded-full animate-pulse" style="animation-delay: 2s"></div>
        </div>
        
        <div class="relative z-10 px-8 py-16 md:px-12 md:py-20">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <div class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <span class="text-white text-sm font-medium">
                            <i class="fas fa-fire mr-2"></i>¡Los mejores sabores te esperan!
                        </span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 leading-tight">
                        Hola{{ $usuario->name ? ', ' . $usuario->name : '' }}!
                    </h1>
                    <p class="text-xl text-orange-100 mb-8 leading-relaxed">
                        Descubre nuestra increíble variedad de platillos frescos, preparados con amor y entregados directo a tu mesa.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})" 
                                class="bg-white text-orange-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-orange-50 transform hover:scale-105 transition-all duration-300 shadow-lg">
                            <i class="fas fa-utensils mr-2"></i>Explorar Menú
                        </button>
                        <button class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-orange-600 transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-play mr-2"></i>Ver Especialidades
                        </button>
                    </div>
                </div>
                <div class="relative hidden md:block">
                    <div class="relative z-10">
                        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 transform rotate-3 hover:rotate-6 transition-transform duration-500">
                            <i class="fas fa-hamburger text-8xl text-white mb-4"></i>
                            <div class="bg-white/20 rounded-xl p-4">
                                <p class="text-white font-semibold">Hamburguesa Premium</p>
                                <p class="text-orange-200">¡La más popular!</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-white/10 backdrop-blur-sm rounded-2xl p-6 transform -rotate-6">
                        <i class="fas fa-pizza-slice text-5xl text-white mb-2"></i>
                        <p class="text-white text-sm font-medium">Pizzas Artesanales</p>
                    </div>
                    <div class="absolute -top-4 -right-4 bg-white/10 backdrop-blur-sm rounded-2xl p-6 transform rotate-12">
                        <i class="fas fa-ice-cream text-5xl text-white mb-2"></i>
                        <p class="text-white text-sm font-medium">Postres Únicos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Rápido y Fácil</h3>
                    <p class="text-blue-600 font-medium">15-30 minutos</p>
                </div>
            </div>
            <p class="text-gray-600 leading-relaxed">
                Pedidos listos en tiempo récord. Tu comida favorita cuando la necesites, sin complicaciones.
            </p>
        </div>

        <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-4 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-heart text-white text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors">Calidad Premium</h3>
                    <p class="text-orange-600 font-medium">100% Fresco</p>
                </div>
            </div>
            <p class="text-gray-600 leading-relaxed">
                Ingredientes frescos y preparación cuidadosa en cada platillo. Calidad que puedes saborear.
            </p>
        </div>

        <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-truck text-white text-2xl"></i>
                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-purple-600 transition-colors">Delivery Seguro</h3>
                    <p class="text-purple-600 font-medium">Seguimiento GPS</p>
                </div>
            </div>
            <p class="text-gray-600 leading-relaxed">
                Seguimiento en tiempo real y entrega hasta tu puerta. Tu pedido siempre en buenas manos.
            </p>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-gradient-to-r from-gray-50 to-orange-50 rounded-2xl p-8 border border-orange-100 mb-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">¿Qué te gustaría hacer hoy?</h2>
            <p class="text-gray-600 text-lg">Explora nuestras opciones disponibles y comienza tu experiencia gastronómica</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <button onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})" 
                    class="group bg-gradient-to-br from-red-500 to-red-600 text-white rounded-2xl p-8 hover:from-red-600 hover:to-red-700 transition-all transform hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-hamburger text-4xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-3">Explorar Menú</h3>
                <p class="text-red-100 text-lg leading-relaxed">Descubre nuestra amplia variedad de platillos deliciosos y frescos</p>
                <div class="mt-4 flex items-center justify-center text-red-200">
                    <span class="mr-2">Ver productos</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </div>
            </button>

            <button class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-8 hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-white/20 p-4 rounded-full group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-receipt text-4xl"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-3">Mis Pedidos</h3>
                <p class="text-blue-100 text-lg leading-relaxed">Revisa el estado y historial de todos tus pedidos anteriores</p>
                <div class="mt-4 flex items-center justify-center text-blue-200">
                    <span class="mr-2">Ver historial</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </div>
            </button>
        </div>
    </div>
    <!-- Productos Destacados -->
    <div class="mb-12">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Productos Destacados</h2>
            <p class="text-lg text-gray-600">Los favoritos de nuestros clientes</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <template x-for="product in products.filter(p => p.popular).slice(0, 3)" :key="'featured-' + product.id">
                <div class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                    <!-- Badge destacado -->
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold z-10">
                        <i class="fas fa-star mr-1"></i>Destacado
                    </div>
                    
                    <!-- Imagen -->
                    <div :class="product.bgGradient" class="h-48 flex items-center justify-center relative">
                        <i :class="product.icon" class="text-7xl group-hover:scale-110 transition-transform duration-300"></i>
                        <div class="absolute top-6 right-6 w-10 h-10 bg-white/20 rounded-full animate-pulse"></div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors" x-text="product.name"></h3>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-orange-600" x-text="'$' + product.price"></div>
                                <div x-show="product.oldPrice" class="text-sm text-gray-400 line-through" x-text="'$' + product.oldPrice"></div>
                            </div>
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400 text-sm">
                                <template x-for="i in 5" :key="i">
                                    <i :class="i <= product.rating ? 'fas fa-star' : 'far fa-star'"></i>
                                </template>
                            </div>
                            <span class="ml-2 text-gray-500 text-sm" x-text="'(' + product.rating + ')'"></span>
                        </div>
                        
                        <p class="text-gray-600 mb-4" x-text="product.description"></p>
                        
                        <button @click="addToCart(product)" 
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>Agregar al Carrito
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Menu Section -->
    <div id="menu-section" class="mb-8">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-gray-800 mb-3">Nuestro Delicioso Menú</h2>
            <p class="text-xl text-gray-600">Platillos preparados con ingredientes frescos y mucho amor</p>
        </div>
        
        <!-- Filtros de Categorías Mejorados -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 mb-8">
            <div class="flex flex-wrap gap-3 justify-center">
                <button @click="currentCategory = 'all'" 
                        :class="currentCategory === 'all' ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-list mr-2"></i>Todos los Productos
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs" x-show="currentCategory === 'all'" x-text="products.length"></span>
                </button>
                <button @click="currentCategory = 'hamburguesas'" 
                        :class="currentCategory === 'hamburguesas' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-hamburger mr-2"></i>Hamburguesas
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs" x-show="currentCategory === 'hamburguesas'" x-text="products.filter(p => p.category === 'hamburguesas').length"></span>
                </button>
                <button @click="currentCategory = 'pizzas'" 
                        :class="currentCategory === 'pizzas' ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-pizza-slice mr-2"></i>Pizzas
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs" x-show="currentCategory === 'pizzas'" x-text="products.filter(p => p.category === 'pizzas').length"></span>
                </button>
                <button @click="currentCategory = 'bebidas'" 
                        :class="currentCategory === 'bebidas' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-coffee mr-2"></i>Bebidas
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs" x-show="currentCategory === 'bebidas'" x-text="products.filter(p => p.category === 'bebidas').length"></span>
                </button>
                <button @click="currentCategory = 'postres'" 
                        :class="currentCategory === 'postres' ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-ice-cream mr-2"></i>Postres
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs" x-show="currentCategory === 'postres'" x-text="products.filter(p => p.category === 'postres').length"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Grid de Productos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="products-grid">
        <!-- Hamburguesas -->
        <div class="product-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all transform hover:scale-105" data-category="hamburguesas">
            <div class="h-48 bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                <i class="fas fa-hamburger text-6xl text-red-500"></i>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-xl font-semibold text-gray-800">Hamburguesa Clásica</h3>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-sm font-medium">$12.99</span>
                </div>
                <p class="text-gray-600 mb-4">
                    Carne jugosa, lechuga, tomate, cebolla y nuestra salsa especial en pan artesanal.
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        15-20 min
                    </div>
                    <button class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-2 rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all">
                        <i class="fas fa-plus mr-2"></i>Agregar
                    </button>
                </div>
            </div>
        </div>


    <!-- Carrito flotante mejorado -->
    <div class="fixed bottom-6 right-6 z-50">
        <button @click="showCart = !showCart" 
                class="group bg-gradient-to-r from-orange-500 to-orange-600 text-white p-5 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-300 relative">
            <i class="fas fa-shopping-cart text-2xl group-hover:animate-bounce"></i>
            <span x-show="cartCount > 0" 
                  x-text="cartCount" 
                  class="absolute -top-3 -right-3 bg-red-500 text-white text-sm font-bold rounded-full h-7 w-7 flex items-center justify-center animate-pulse"></span>
            <!-- Efecto de ondas -->
            <div class="absolute inset-0 rounded-full bg-orange-400 opacity-75 animate-ping" x-show="cartCount > 0"></div>
        </button>
    </div>

    <!-- Sidebar del Carrito -->
    <div x-show="showCart" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black bg-opacity-50 z-50" 
         @click="showCart = false">
        
        <div x-show="showCart" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="translate-x-full" 
             class="absolute right-0 top-0 h-full w-96 bg-white shadow-2xl" 
             @click.stop>
            
            <!-- Header del carrito -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">Tu Carrito</h3>
                        <p class="text-orange-100" x-text="cartCount + ' productos'"></p>
                    </div>
                    <button @click="showCart = false" class="hover:bg-white/20 p-2 rounded-full transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Items del carrito -->
            <div class="flex-1 overflow-y-auto p-6" style="max-height: calc(100vh - 200px);">
                <template x-if="cart.length === 0">
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Tu carrito está vacío</p>
                        <p class="text-gray-400">Agrega algunos productos deliciosos</p>
                    </div>
                </template>
                
                <div x-show="cart.length > 0" class="space-y-4">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                            <div :class="item.bgGradient" class="w-16 h-16 rounded-lg flex items-center justify-center mr-4">
                                <i :class="item.icon" class="text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800" x-text="item.name"></h4>
                                <p class="text-orange-600 font-bold" x-text="'$' + item.price"></p>
                            </div>
                            <div class="flex items-center">
                                <button @click="decrementItem(item)" class="bg-gray-200 hover:bg-gray-300 w-8 h-8 rounded-full flex items-center justify-center">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="mx-3 font-bold" x-text="item.quantity"></span>
                                <button @click="incrementItem(item)" class="bg-orange-500 hover:bg-orange-600 text-white w-8 h-8 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Footer del carrito -->
            <div x-show="cart.length > 0" class="border-t bg-gray-50 p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xl font-bold text-gray-800">Total:</span>
                    <span class="text-3xl font-bold text-orange-600" x-text="'$' + cartTotal.toFixed(2)"></span>
                </div>
                <button class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    <i class="fas fa-credit-card mr-2"></i>Proceder al Pago
                </button>
            </div>
        </div>
    </div>
    
    <!-- Sección de contacto mejorada -->
    <div class="bg-gradient-to-r from-gray-50 to-orange-50 rounded-2xl p-8 text-center border border-orange-100 mt-12">
        <div class="max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">¿Necesitas ayuda con tu pedido?</h3>
            <p class="text-gray-600 mb-6">Nuestro equipo de atención al cliente está listo para ayudarte</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:123456790" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center">
                    <i class="fas fa-phone mr-2"></i>(123) 456-7890
                </a>
                <a href="https://wa.me/123456790" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para el manejo del carrito -->
<script>
    function foodStore() {
        return {
            currentCategory: 'all',
            cart: [],
            showCart: false,
            
            products: [
                // Hamburguesas
                {
                    id: 1,
                    name: 'Hamburguesa Clásica',
                    price: 12.99,
                    description: 'Carne jugosa, lechuga, tomate, cebolla y nuestra salsa especial en pan artesanal.',
                    category: 'hamburguesas',
                    time: '15-20 min',
                    rating: 4.5,
                    popular: true,
                    icon: 'fas fa-hamburger text-red-500',
                    bgGradient: 'bg-gradient-to-br from-red-100 to-red-200'
                },
                {
                    id: 2,
                    name: 'Hamburguesa Premium',
                    price: 18.99,
                    oldPrice: 22.99,
                    description: 'Doble carne, queso gourmet, bacon crujiente, aguacate y salsa BBQ.',
                    category: 'hamburguesas',
                    time: '20-25 min',
                    rating: 4.8,
                    popular: false,
                    icon: 'fas fa-hamburger text-red-600',
                    bgGradient: 'bg-gradient-to-br from-red-200 to-red-300'
                },
                // Pizzas
                {
                    id: 3,
                    name: 'Pizza Margarita',
                    price: 16.99,
                    description: 'Salsa de tomate casera, mozzarella fresca, albahaca y aceite de oliva.',
                    category: 'pizzas',
                    time: '25-30 min',
                    rating: 4.6,
                    popular: true,
                    icon: 'fas fa-pizza-slice text-yellow-600',
                    bgGradient: 'bg-gradient-to-br from-yellow-100 to-yellow-200'
                },
                {
                    id: 4,
                    name: 'Pizza Pepperoni',
                    price: 19.99,
                    description: 'Salsa especial, queso mozzarella y abundante pepperoni de primera calidad.',
                    category: 'pizzas',
                    time: '25-30 min',
                    rating: 4.7,
                    popular: false,
                    icon: 'fas fa-pizza-slice text-yellow-700',
                    bgGradient: 'bg-gradient-to-br from-yellow-200 to-yellow-300'
                },
                // Bebidas
                {
                    id: 5,
                    name: 'Café Premium',
                    price: 4.99,
                    description: 'Café de origen 100% colombiano, tostado artesanal con notas dulces.',
                    category: 'bebidas',
                    time: '5-8 min',
                    rating: 4.4,
                    popular: false,
                    icon: 'fas fa-coffee text-blue-600',
                    bgGradient: 'bg-gradient-to-br from-blue-100 to-blue-200'
                },
                {
                    id: 6,
                    name: 'Limonada Natural',
                    price: 3.99,
                    description: 'Refrescante limonada con limones frescos, endulzada naturalmente.',
                    category: 'bebidas',
                    time: '3-5 min',
                    rating: 4.3,
                    popular: false,
                    icon: 'fas fa-glass-water text-green-600',
                    bgGradient: 'bg-gradient-to-br from-green-100 to-green-200'
                },
                // Postres
                {
                    id: 7,
                    name: 'Helado Artesanal',
                    price: 6.99,
                    description: 'Helado casero de vainilla con toppings variados y salsa de chocolate.',
                    category: 'postres',
                    time: '2-3 min',
                    rating: 4.9,
                    popular: true,
                    icon: 'fas fa-ice-cream text-purple-600',
                    bgGradient: 'bg-gradient-to-br from-purple-100 to-purple-200'
                },
                {
                    id: 8,
                    name: 'Torta de Chocolate',
                    price: 8.99,
                    description: 'Deliciosa torta de chocolate húmeda con frosting cremoso y decoración especial.',
                    category: 'postres',
                    time: '5-8 min',
                    rating: 4.8,
                    popular: false,
                    icon: 'fas fa-birthday-cake text-pink-600',
                    bgGradient: 'bg-gradient-to-br from-pink-100 to-pink-200'
                }
            ],
            
            get filteredProducts() {
                if (this.currentCategory === 'all') {
                    return this.products;
                }
                return this.products.filter(product => product.category === this.currentCategory);
            },
            
            get cartCount() {
                return this.cart.reduce((total, item) => total + item.quantity, 0);
            },
            
            get cartTotal() {
                return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            },
            
            addToCart(product) {
                const existingItem = this.cart.find(item => item.id === product.id);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    this.cart.push({ ...product, quantity: 1 });
                }
                
                // Mostrar animación o notificación
                this.showAddedNotification();
            },
            
            incrementItem(item) {
                item.quantity += 1;
            },
            
            decrementItem(item) {
                if (item.quantity > 1) {
                    item.quantity -= 1;
                } else {
                    this.removeFromCart(item.id);
                }
            },
            
            removeFromCart(productId) {
                this.cart = this.cart.filter(item => item.id !== productId);
            },
            
            showAddedNotification() {
                // Aquí podrías agregar una notificación toast
                console.log('Producto agregado al carrito');
            }
        }
    }
</script>

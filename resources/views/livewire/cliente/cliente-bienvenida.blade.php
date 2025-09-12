<div class="">
    <div class="relative z-10">
        <!-- Main Content -->
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <!-- Hero Section -->
            <div class="bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 rounded-3xl overflow-hidden shadow-2xl mb-12 relative">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse"></div>
                    <div class="absolute top-32 right-20 w-16 h-16 bg-white rounded-full animate-pulse" style="animation-delay: 1s"></div>
                    <div class="absolute bottom-20 left-32 w-12 h-12 bg-white rounded-full animate-pulse" style="animation-delay: 2s"></div>
                </div>
                
                <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center p-8 md:p-16">
                    <div class="space-y-6">
                        <div class="bg-white/20 text-white hover:bg-white/30 border-0 inline-block px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-fire mr-2"></i>¡Los mejores sabores te esperan!
                        </div>
                        
                        <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight">
                            ¡Hola{{ $usuario->name ? ', ' . $usuario->name : '' }}!
                        </h1>
                        
                        <p class="text-xl text-orange-100 leading-relaxed max-w-lg">
                            Descubre nuestra increíble variedad de platillos frescos, preparados con amor y entregados directo a tu mesa.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button 
                                onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})" 
                                class="bg-white text-orange-600 hover:bg-gray-50 shadow-lg transform hover:scale-105 font-bold text-lg px-8 py-4 rounded-xl transition-all duration-300"
                            >
                                <i class="fas fa-utensils mr-2"></i>Explorar Menú
                            </button>
                            
                            <button 
                                class="border-2 border-white text-white hover:bg-white hover:text-orange-600 font-bold text-lg px-8 py-4 rounded-xl transition-all duration-300"
                            >
                                <i class="fas fa-play mr-2"></i>Ver Especialidades
                            </button>
                        </div>
                    </div>
                    
                    <div class="hidden md:block relative">
                        <div class="relative">
                            <img 
                                src="{{ asset('images/burgerHero.png') }}" 
                                alt="Hamburguesa Premium" 
                                class="w-full h-96 object-cover rounded-2xl shadow-2xl rotate-3 hover:rotate-6 transition-transform duration-500"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="group bg-white rounded-2xl p-8 shadow-lg border-0 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 bg-gradient-to-br from-white to-gray-50">
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

                <div class="group bg-white rounded-2xl p-8 shadow-lg border-0 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 bg-gradient-to-br from-white to-gray-50">
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

                <div class="group bg-white rounded-2xl p-8 shadow-lg border-0 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 bg-gradient-to-br from-white to-gray-50">
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

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-gray-50 to-orange-50 border border-orange-500/20 rounded-2xl p-8 mb-12 shadow-lg">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">¿Qué te gustaría hacer hoy?</h2>
                    <p class="text-gray-600 text-lg">Explora nuestras opciones disponibles y comienza tu experiencia gastronómica</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button 
                        onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})"
                        class="group bg-gradient-to-br from-red-500 to-red-600 text-white rounded-2xl p-8 hover:from-red-600 hover:to-red-700 hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                    >
                        <div class="text-center">
                            <div class="bg-white/20 p-4 rounded-full mb-4 group-hover:scale-110 transition-transform duration-300 mx-auto w-fit">
                                <i class="fas fa-utensils text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-3">Explorar Menú</h3>
                            <p class="text-red-100 text-lg leading-relaxed mb-4">Descubre nuestra amplia variedad de platillos deliciosos y frescos</p>
                            <div class="flex items-center justify-center text-red-200">
                                <span class="mr-2">Ver productos</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                            </div>
                        </div>
                    </button>

                    <button 
                        class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-8 hover:from-blue-600 hover:to-blue-700 hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                    >
                        <div class="text-center">
                            <div class="bg-white/20 p-4 rounded-full mb-4 group-hover:scale-110 transition-transform duration-300 mx-auto w-fit">
                                <i class="fas fa-clock text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-3">Mis Pedidos</h3>
                            <p class="text-blue-100 text-lg leading-relaxed mb-4">Revisa el estado y historial de todos tus pedidos anteriores</p>
                            <div class="flex items-center justify-center text-blue-200">
                                <span class="mr-2">Ver historial</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                            </div>
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
                    <!-- Hamburguesa Premium -->
                    <div class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Badge destacado -->
                        <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold z-10">
                            <i class="fas fa-star mr-1"></i>Destacado
                        </div>
                        
                        <!-- Imagen -->
                        <div class="bg-gradient-to-br from-red-100 to-red-200 h-48 flex items-center justify-center relative">
                            <i class="fas fa-hamburger text-red-500 text-7xl group-hover:scale-110 transition-transform duration-300"></i>
                            <div class="absolute top-6 right-6 w-10 h-10 bg-white/20 rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors">Hamburguesa Premium</h3>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-orange-600">$15.99</div>
                                    <div class="text-sm text-gray-400 line-through">$18.99</div>
                                </div>
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="ml-2 text-gray-500 text-sm">(5.0)</span>
                            </div>
                            
                            <p class="text-gray-600 mb-4">Doble carne 100% premium, queso cheddar, bacon ahumado, lechuga y tomate en pan artesanal.</p>
                            
                            <button class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>Agregar al Carrito
                            </button>
                        </div>
                    </div>

                    <!-- Pizza Margherita -->
                    <div class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Badge destacado -->
                        <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold z-10">
                            <i class="fas fa-star mr-1"></i>Destacado
                        </div>
                        
                        <!-- Imagen -->
                        <div class="bg-gradient-to-br from-yellow-100 to-orange-200 h-48 flex items-center justify-center relative">
                            <i class="fas fa-pizza-slice text-orange-500 text-7xl group-hover:scale-110 transition-transform duration-300"></i>
                            <div class="absolute top-6 right-6 w-10 h-10 bg-white/20 rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors">Pizza Margherita</h3>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-orange-600">$18.50</div>
                                </div>
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="ml-2 text-gray-500 text-sm">(4.8)</span>
                            </div>
                            
                            <p class="text-gray-600 mb-4">Pizza tradicional italiana con salsa de tomate fresca, mozzarella y albahaca sobre masa artesanal.</p>
                            
                            <button class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>Agregar al Carrito
                            </button>
                        </div>
                    </div>

                    <!-- Bebida Especial -->
                    <div class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Badge destacado -->
                        <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold z-10">
                            <i class="fas fa-star mr-1"></i>Destacado
                        </div>
                        
                        <!-- Imagen -->
                        <div class="bg-gradient-to-br from-blue-100 to-cyan-200 h-48 flex items-center justify-center relative">
                            <i class="fas fa-cocktail text-blue-500 text-7xl group-hover:scale-110 transition-transform duration-300"></i>
                            <div class="absolute top-6 right-6 w-10 h-10 bg-white/20 rounded-full animate-pulse"></div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors">Smoothie Tropical</h3>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-orange-600">$8.99</div>
                                    <div class="text-sm text-gray-400 line-through">$10.99</div>
                                </div>
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="ml-2 text-gray-500 text-sm">(4.6)</span>
                            </div>
                            
                            <p class="text-gray-600 mb-4">Refrescante mezcla de frutas tropicales: mango, piña y maracuyá con yogurt natural.</p>
                            
                            <button class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-bold hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>Agregar al Carrito
                            </button>
                        </div>
                    </div>
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
                <div class="flex flex-wrap gap-5 justify-center">
                    <button class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg scale-105">
                        <i class="fas fa-list mr-2"></i>Todos los Productos
                        <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs">12</span>
                    </button>
                    <button class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-hamburger mr-2"></i>Hamburguesas
                        <span class="ml-2 bg-gray-200 px-2 py-1 rounded-full text-xs">4</span>
                    </button>
                    <button class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-pizza-slice mr-2"></i>Pizzas
                        <span class="ml-2 bg-gray-200 px-2 py-1 rounded-full text-xs">3</span>
                    </button>
                    <button class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-coffee mr-2"></i>Bebidas
                        <span class="ml-2 bg-gray-200 px-2 py-1 rounded-full text-xs">3</span>
                    </button>
                    <button class="category-btn px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-ice-cream mr-2"></i>Postres
                        <span class="ml-2 bg-gray-200 px-2 py-1 rounded-full text-xs">2</span>
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



 
                </main>
            </div>


        </div>
</div>



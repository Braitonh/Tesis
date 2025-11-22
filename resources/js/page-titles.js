/**
 * Mapeo de rutas a títulos de página
 */
const pageTitles = {
    // Autenticación
    'login': 'FoodDesk - Iniciar Sesión',
    'register': 'FoodDesk - Registrarse',
    'password.show': 'FoodDesk - Crear Contraseña',
    'password.create': 'FoodDesk - Crear Contraseña',
    'verification.notice': 'FoodDesk - Verificar Email',
    'verification.resend': 'FoodDesk - Verificar Email',
    
    // Dashboard y administración
    'dashboard': 'FoodDesk - Dashboard',
    'empleados': 'FoodDesk - Empleados',
    'empleado.perfil': 'FoodDesk - Mi Perfil',
    'productos': 'FoodDesk - Productos',
    'promociones': 'FoodDesk - Promociones',
    'clientes': 'FoodDesk - Clientes',
    'pedidos': 'FoodDesk - Pedidos',
    
    // Módulos especializados
    'cocina': 'FoodDesk - Cocina',
    'delivery': 'FoodDesk - Delivery',
    
    // Cliente
    'cliente.bienvenida': 'FoodDesk - Inicio',
    'cliente.perfil': 'FoodDesk - Mi Perfil',
    'cliente.pedidos': 'FoodDesk - Mis Pedidos',
    'cliente.carrito.checkout': 'FoodDesk - Checkout',
    'cliente.checkout': 'FoodDesk - Checkout',
    'cliente.pago.procesando': 'FoodDesk - Procesando Pago',
    'cliente.pedido.confirmacion': 'FoodDesk - Confirmación de Pedido',
};

/**
 * Obtiene el nombre de la ruta actual desde la URL
 */
function getCurrentRouteName() {
    // Obtener la ruta desde la URL
    const path = window.location.pathname;
    
    // Mapeo de paths a nombres de ruta (ordenados de más específico a menos específico)
    const pathToRoute = [
        // Rutas con parámetros dinámicos (deben ir primero)
        { pattern: /^\/cliente\/pedido\/\d+\/confirmacion$/, route: 'cliente.pedido.confirmacion' },
        { pattern: /^\/cliente\/pago\/procesando\/.+$/, route: 'cliente.pago.procesando' },
        { pattern: /^\/create-password\/.+$/, route: 'password.show' },
        
        // Rutas exactas
        { pattern: /^\/login$/, route: 'login' },
        { pattern: /^\/register$/, route: 'register' },
        { pattern: /^\/dashboard$/, route: 'dashboard' },
        { pattern: /^\/empleados$/, route: 'empleados' },
        { pattern: /^\/empleado\/perfil$/, route: 'empleado.perfil' },
        { pattern: /^\/productos$/, route: 'productos' },
        { pattern: /^\/promociones$/, route: 'promociones' },
        { pattern: /^\/clientes$/, route: 'clientes' },
        { pattern: /^\/pedidos$/, route: 'pedidos' },
        { pattern: /^\/cocina$/, route: 'cocina' },
        { pattern: /^\/delivery$/, route: 'delivery' },
        { pattern: /^\/cliente\/bienvenida$/, route: 'cliente.bienvenida' },
        { pattern: /^\/cliente\/perfil$/, route: 'cliente.perfil' },
        { pattern: /^\/cliente\/pedidos$/, route: 'cliente.pedidos' },
        { pattern: /^\/cliente\/carrito\/checkout$/, route: 'cliente.carrito.checkout' },
        { pattern: /^\/cliente\/checkout$/, route: 'cliente.checkout' },
        { pattern: /^\/email\/verify$/, route: 'verification.notice' },
    ];
    
    // Buscar coincidencia
    for (const mapping of pathToRoute) {
        if (mapping.pattern.test(path)) {
            return mapping.route;
        }
    }
    
    return null;
}

/**
 * Actualiza el título de la página según la ruta actual
 */
function updatePageTitle() {
    const routeName = getCurrentRouteName();
    const appName = 'FoodDesk';
    
    if (routeName && pageTitles[routeName]) {
        document.title = pageTitles[routeName];
    } else {
        // Título por defecto
        document.title = appName;
    }
}

/**
 * Inicializa el sistema de títulos dinámicos
 */
export function initPageTitles() {
    // Función auxiliar para actualizar con delay (útil para navegación SPA)
    const updateWithDelay = () => {
        // Pequeño delay para asegurar que la URL se haya actualizado
        setTimeout(updatePageTitle, 50);
    };
    
    // Actualizar título al cargar la página
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updatePageTitle);
    } else {
        updatePageTitle();
    }
    
    // Escuchar eventos de navegación de Livewire 3
    document.addEventListener('livewire:navigated', updateWithDelay);
    
    // Escuchar cambios en el historial del navegador (botones atrás/adelante)
    window.addEventListener('popstate', updatePageTitle);
    
    // Escuchar cambios de ruta de Livewire usando hooks
    if (window.Livewire) {
        // Hook para cuando Livewire actualiza el DOM después de navegación
        Livewire.hook('morph.updated', updateWithDelay);
        
        // Hook para cuando Livewire inicia una navegación
        Livewire.hook('morph.updating', () => {
            // Actualizar inmediatamente si es posible
            updatePageTitle();
        });
    }
    
    // Observar cambios en la URL usando un intervalo como fallback
    // Esto captura cambios de URL que no disparan eventos estándar
    let lastPath = window.location.pathname;
    const pathCheckInterval = setInterval(() => {
        const currentPath = window.location.pathname;
        if (currentPath !== lastPath) {
            lastPath = currentPath;
            updatePageTitle();
        }
    }, 500);
    
    // Limpiar el intervalo cuando la página se descargue
    window.addEventListener('beforeunload', () => {
        clearInterval(pathCheckInterval);
    });
}


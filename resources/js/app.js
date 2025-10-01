import './bootstrap';
import './notifications';

// Importar AlpineJS y sus plugins
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

// Registrar plugins de Alpine
Alpine.plugin(focus);
Alpine.plugin(persist);

// Inicializar Alpine
window.Alpine = Alpine;
Alpine.start();

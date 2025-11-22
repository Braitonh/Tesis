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

// Importar y configurar Chart.js
import {
    Chart,
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    LineController,
    BarController,
    DoughnutController,
    PieController
} from 'chart.js';

// Registrar componentes de Chart.js
Chart.register(
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    LineController,
    BarController,
    DoughnutController,
    PieController
);

// Hacer Chart.js disponible globalmente
window.Chart = Chart;

// Importar y inicializar sistema de títulos dinámicos
import { initPageTitles } from './page-titles';

// Inicializar títulos dinámicos
// Se ejecuta después de que Alpine esté inicializado o cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        // Pequeño delay para asegurar que Livewire esté listo
        setTimeout(initPageTitles, 100);
    });
} else {
    // DOM ya está listo, inicializar con un pequeño delay
    setTimeout(initPageTitles, 100);
}

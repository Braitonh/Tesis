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

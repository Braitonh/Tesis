<div>
    <div class="relative z-10">
        <main class="py-8 px-4 sm:px-6 lg:px-8 max-full mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard Analítico</h1>
                <p class="text-gray-500">Métricas y estadísticas del negocio</p>
            </div>

            <!-- Tarjetas KPI -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Ventas del Mes -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Ventas del Mes</p>
                            <p class="text-3xl font-bold text-gray-800">${{ number_format($this->kpis['ventas_mes'], 0, ',', '.') }}</p>
                            @if($this->kpis['variacion_ventas'] != 0)
                                <p class="text-sm mt-1 {{ $this->kpis['variacion_ventas'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $this->kpis['variacion_ventas'] > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                    {{ abs($this->kpis['variacion_ventas']) }}%
                                </p>
                            @endif
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-dollar-sign text-orange-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pedidos del Mes -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Pedidos del Mes</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $this->kpis['pedidos_mes'] }}</p>
                            @if($this->kpis['variacion_pedidos'] != 0)
                                <p class="text-sm mt-1 {{ $this->kpis['variacion_pedidos'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas {{ $this->kpis['variacion_pedidos'] > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                    {{ abs($this->kpis['variacion_pedidos']) }}%
                                </p>
                            @endif
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Promedio por Pedido -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Promedio por Pedido</p>
                            <p class="text-3xl font-bold text-gray-800">${{ number_format($this->kpis['promedio_por_pedido'], 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tasa de Cancelación -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-semibold uppercase mb-1">Tasa de Cancelación</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $this->tasaCancelacion }}%</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Ventas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Ventas por Día -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Ventas por Día (Últimos 30 días)</h2>
                    <div class="relative w-full" style="height: 300px;">
                        <canvas id="ventasPorDia"></canvas>
                    </div>
                </div>

                <!-- Ventas por Mes -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Ventas por Mes (Últimos 12 meses)</h2>
                    <div class="relative w-full" style="height: 300px;">
                        <canvas id="ventasPorMes"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Pedidos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Pedidos por Estado -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Pedidos por Estado</h2>
                    <div class="relative" style="height: 300px;">
                        <canvas id="pedidosPorEstado"></canvas>
                    </div>
                </div>

                <!-- Pedidos por Día de la Semana -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Pedidos por Día de la Semana</h2>
                    <div class="relative" style="height: 300px;">
                        <canvas id="pedidosPorDiaSemana"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Productos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Top 10 Productos Más Vendidos -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Top 10 Productos Más Vendidos</h2>
                    <div class="relative" style="height: 400px;">
                        <canvas id="topProductos"></canvas>
                    </div>
                </div>

                <!-- Productos por Categoría -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Productos por Categoría</h2>
                    <div class="relative" style="height: 300px;">
                        <canvas id="productosPorCategoria"></canvas>
                    </div>
                </div>
            </div>

            <!-- Productos con Stock Bajo -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Productos con Stock Bajo</h2>
                @if(count($this->productosStockBajo) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($this->productosStockBajo as $producto)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto['nombre'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $producto['stock'] < 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $producto['stock'] }} unidades
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No hay productos con stock bajo</p>
                @endif
            </div>
        </main>
    </div>

    <script>
        let charts = {};
        
        // Datos del componente (se actualizarán cuando Livewire renderice)
        let chartData = {
            ventasPorDia: @json($this->ventasPorDia),
            ventasPorSemana: @json($this->ventasPorSemana),
            ventasPorMes: @json($this->ventasPorMes),
            pedidosPorEstado: @json($this->pedidosPorEstado),
            pedidosPorDiaSemana: @json($this->pedidosPorDiaSemana),
            topProductos: @json($this->topProductos),
            productosPorCategoria: @json($this->productosPorCategoria),
            ingresosPorMetodoPago: @json($this->ingresosPorMetodoPago),
            ingresosDiarios: @json($this->ingresosDiarios),
        };

        function inicializarGraficos() {
            // Destruir gráficos existentes de forma más agresiva
            Object.keys(charts).forEach(key => {
                try {
                    if (charts[key] && typeof charts[key].destroy === 'function') {
                        charts[key].destroy();
                    }
                } catch (e) {
                    console.warn('Error al destruir gráfico:', key, e);
                }
            });
            charts = {};
            
            // También destruir cualquier gráfico que Chart.js pueda tener registrado en los canvas
            const canvasElements = document.querySelectorAll('canvas[id^="ventas"], canvas[id^="pedidos"], canvas[id^="top"], canvas[id^="productos"], canvas[id^="ingresos"]');
            canvasElements.forEach(canvas => {
                const chartInstance = Chart.getChart(canvas);
                if (chartInstance) {
                    try {
                        chartInstance.destroy();
                    } catch (e) {
                        console.warn('Error al destruir gráfico del canvas:', canvas.id, e);
                    }
                }
            });

            // Verificar que Chart.js esté disponible
            if (typeof Chart === 'undefined') {
                console.error('Chart.js no está disponible, reintentando...');
                setTimeout(inicializarGraficos, 100);
                return;
            }

            // Colores para los gráficos
            const colors = {
                primary: '#FF6600',
                secondary: '#3B82F6',
                success: '#10B981',
                danger: '#EF4444',
                warning: '#F59E0B',
                info: '#06B6D4',
                purple: '#8B5CF6',
            };

            // Función para generar colores aleatorios
            function generateColors(count) {
                const colorPalette = [
                    '#FF6600', '#3B82F6', '#10B981', '#EF4444', '#F59E0B',
                    '#06B6D4', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316'
                ];
                return colorPalette.slice(0, count);
            }

            // Ventas por Día
            const ventasPorDiaData = chartData.ventasPorDia || [];
            const ventasPorDiaCtx = document.getElementById('ventasPorDia');
            if (ventasPorDiaCtx && ventasPorDiaData.length > 0) {
                charts.ventasPorDia = new Chart(ventasPorDiaCtx, {
                    type: 'line',
                    data: {
                        labels: ventasPorDiaData.map(item => item.fecha),
                        datasets: [{
                            label: 'Ventas ($)',
                            data: ventasPorDiaData.map(item => item.total),
                            borderColor: colors.primary,
                            backgroundColor: colors.primary + '20',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Ventas por Semana
            const ventasPorSemanaData = chartData.ventasPorSemana || [];
            const ventasPorSemanaCtx = document.getElementById('ventasPorSemana');
            if (ventasPorSemanaCtx && ventasPorSemanaData.length > 0) {
                charts.ventasPorSemana = new Chart(ventasPorSemanaCtx, {
                    type: 'line',
                    data: {
                        labels: ventasPorSemanaData.map(item => item.semana),
                        datasets: [{
                            label: 'Ventas ($)',
                            data: ventasPorSemanaData.map(item => item.total),
                            borderColor: colors.secondary,
                            backgroundColor: colors.secondary + '20',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Ventas por Mes
            const ventasPorMesData = chartData.ventasPorMes || [];
            const ventasPorMesCtx = document.getElementById('ventasPorMes');
            if (ventasPorMesCtx && ventasPorMesData.length > 0) {
                charts.ventasPorMes = new Chart(ventasPorMesCtx, {
                    type: 'bar',
                    data: {
                        labels: ventasPorMesData.map(item => {
                            // Si ya viene el mes sin año, usarlo directamente
                            // Si viene con año (formato "M Y"), extraer solo el mes
                            if (item.mes && !item.mes.includes(' ')) {
                                return item.mes;
                            }
                            return item.mes ? item.mes.split(' ')[0] : item.mes_completo?.split(' ')[0] || '';
                        }),
                        datasets: [{
                            label: 'Ventas ($)',
                            data: ventasPorMesData.map(item => item.total),
                            backgroundColor: colors.primary,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Pedidos por Estado
            const pedidosPorEstadoData = chartData.pedidosPorEstado || [];
            const pedidosPorEstadoCtx = document.getElementById('pedidosPorEstado');
            if (pedidosPorEstadoCtx && pedidosPorEstadoData.length > 0) {
                charts.pedidosPorEstado = new Chart(pedidosPorEstadoCtx, {
                    type: 'doughnut',
                    data: {
                        labels: pedidosPorEstadoData.map(item => item.estado),
                        datasets: [{
                            data: pedidosPorEstadoData.map(item => item.cantidad),
                            backgroundColor: generateColors(pedidosPorEstadoData.length)
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Pedidos por Día de la Semana
            const pedidosPorDiaSemanaData = chartData.pedidosPorDiaSemana || [];
            const pedidosPorDiaSemanaCtx = document.getElementById('pedidosPorDiaSemana');
            if (pedidosPorDiaSemanaCtx && pedidosPorDiaSemanaData.length > 0) {
                charts.pedidosPorDiaSemana = new Chart(pedidosPorDiaSemanaCtx, {
                    type: 'bar',
                    data: {
                        labels: pedidosPorDiaSemanaData.map(item => item.dia),
                        datasets: [{
                            label: 'Cantidad de Pedidos',
                            data: pedidosPorDiaSemanaData.map(item => item.cantidad),
                            backgroundColor: colors.info,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Top 10 Productos Más Vendidos
            const topProductosData = chartData.topProductos || [];
            const topProductosCtx = document.getElementById('topProductos');
            if (topProductosCtx && topProductosData.length > 0) {
                charts.topProductos = new Chart(topProductosCtx, {
                    type: 'bar',
                    data: {
                        labels: topProductosData.map(item => item.nombre.length > 20 ? item.nombre.substring(0, 20) + '...' : item.nombre),
                        datasets: [{
                            label: 'Cantidad Vendida',
                            data: topProductosData.map(item => item.cantidad),
                            backgroundColor: colors.success,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Productos por Categoría
            const productosPorCategoriaData = chartData.productosPorCategoria || [];
            const productosPorCategoriaCtx = document.getElementById('productosPorCategoria');
            if (productosPorCategoriaCtx && productosPorCategoriaData.length > 0) {
                charts.productosPorCategoria = new Chart(productosPorCategoriaCtx, {
                    type: 'pie',
                    data: {
                        labels: productosPorCategoriaData.map(item => item.nombre),
                        datasets: [{
                            data: productosPorCategoriaData.map(item => item.cantidad),
                            backgroundColor: generateColors(productosPorCategoriaData.length)
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Ingresos por Método de Pago
            const ingresosPorMetodoPagoData = chartData.ingresosPorMetodoPago || [];
            const ingresosPorMetodoPagoCtx = document.getElementById('ingresosPorMetodoPago');
            if (ingresosPorMetodoPagoCtx && ingresosPorMetodoPagoData.length > 0) {
                charts.ingresosPorMetodoPago = new Chart(ingresosPorMetodoPagoCtx, {
                    type: 'pie',
                    data: {
                        labels: ingresosPorMetodoPagoData.map(item => item.metodo),
                        datasets: [{
                            data: ingresosPorMetodoPagoData.map(item => item.total),
                            backgroundColor: generateColors(ingresosPorMetodoPagoData.length)
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': $' + context.parsed.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Ingresos Diarios
            const ingresosDiariosData = chartData.ingresosDiarios || [];
            const ingresosDiariosCtx = document.getElementById('ingresosDiarios');
            if (ingresosDiariosCtx && ingresosDiariosData.length > 0) {
                charts.ingresosDiarios = new Chart(ingresosDiariosCtx, {
                    type: 'line',
                    data: {
                        labels: ingresosDiariosData.map(item => item.fecha),
                        datasets: [{
                            label: 'Ingresos ($)',
                            data: ingresosDiariosData.map(item => item.total),
                            borderColor: colors.purple,
                            backgroundColor: colors.purple + '20',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Función para actualizar datos cuando Livewire actualice el componente
        function actualizarDatos() {
            // Los datos se actualizarán desde el servidor en la próxima renderización
            // Por ahora mantenemos los datos iniciales
        }

        // Variable para evitar múltiples inicializaciones simultáneas
        let inicializando = false;
        
        // Función para verificar y inicializar gráficos
        function verificarEInicializar() {
            // Evitar múltiples inicializaciones simultáneas
            if (inicializando) {
                console.log('⏸️ Inicialización ya en curso, esperando...');
                return false;
            }
            
            const ventasPorDiaEl = document.getElementById('ventasPorDia');
            const chartDisponible = typeof Chart !== 'undefined';
            
            if (chartDisponible && ventasPorDiaEl) {
                inicializando = true;
                console.log('✅ Chart.js disponible y elementos encontrados, inicializando gráficos...');
                
                try {
                    inicializarGraficos();
                    inicializando = false;
                    return true;
                } catch (error) {
                    console.error('❌ Error al inicializar gráficos:', error);
                    inicializando = false;
                    return false;
                }
            }
            return false;
        }

        // Inicializar gráficos cuando Livewire esté listo
        document.addEventListener('livewire:init', () => {
            console.log('📊 Livewire inicializado, esperando para inicializar gráficos...');
            setTimeout(() => {
                if (!verificarEInicializar()) {
                    console.log('⏳ Reintentando inicialización...');
                    setTimeout(verificarEInicializar, 500);
                }
            }, 300);
        });

        // Reinicializar gráficos después de navegación de Livewire
        document.addEventListener('livewire:navigated', () => {
            console.log('🔄 Livewire navegado, reinicializando gráficos...');
            setTimeout(() => {
                verificarEInicializar();
            }, 300);
        });

        // También inicializar si el DOM ya está listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                console.log('📄 DOM cargado, inicializando gráficos...');
                setTimeout(() => {
                    if (!verificarEInicializar()) {
                        let intentos = 0;
                        const intervalo = setInterval(() => {
                            intentos++;
                            if (verificarEInicializar() || intentos > 15) {
                                clearInterval(intervalo);
                                if (intentos > 15) {
                                    console.error('❌ No se pudo inicializar después de varios intentos');
                                }
                            }
                        }, 300);
                    }
                }, 500);
            });
        } else {
            // Si el DOM ya está listo
            console.log('📄 DOM ya está listo, verificando inicialización...');
            setTimeout(() => {
                if (!verificarEInicializar()) {
                    let intentos = 0;
                    const intervalo = setInterval(() => {
                        intentos++;
                        if (verificarEInicializar() || intentos > 15) {
                            clearInterval(intervalo);
                            if (intentos > 15) {
                                console.error('❌ No se pudo inicializar Chart.js después de varios intentos');
                                console.log('Chart disponible:', typeof Chart !== 'undefined');
                                console.log('Elemento ventasPorDia:', document.getElementById('ventasPorDia'));
                            }
                        }
                    }, 300);
                }
            }, 500);
        }
    </script>
</div>


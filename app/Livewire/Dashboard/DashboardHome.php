<?php

namespace App\Livewire\Dashboard;

use App\Models\Categoria;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardHome extends Component
{
    public function mount()
    {
        // Verificar que el usuario sea administrador
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo los administradores pueden ver esta sección.');
        }
    }

    /**
     * Obtener KPIs principales
     */
    public function getKpisProperty()
    {
        $mesActual = now()->startOfMonth();
        $mesAnterior = now()->subMonth()->startOfMonth();

        $ventasMesActual = Pedido::where('estado_pago', 'pagado')
            ->where('created_at', '>=', $mesActual)
            ->sum('total');

        $ventasMesAnterior = Pedido::where('estado_pago', 'pagado')
            ->whereBetween('created_at', [$mesAnterior, $mesActual])
            ->sum('total');

        $pedidosMesActual = Pedido::where('created_at', '>=', $mesActual)->count();
        $pedidosMesAnterior = Pedido::whereBetween('created_at', [$mesAnterior, $mesActual])->count();

        $promedioPorPedido = $pedidosMesActual > 0
            ? $ventasMesActual / $pedidosMesActual
            : 0;

        $variacionVentas = $ventasMesAnterior > 0
            ? (($ventasMesActual - $ventasMesAnterior) / $ventasMesAnterior) * 100
            : 0;

        $variacionPedidos = $pedidosMesAnterior > 0
            ? (($pedidosMesActual - $pedidosMesAnterior) / $pedidosMesAnterior) * 100
            : 0;

        return [
            'ventas_mes' => $ventasMesActual,
            'ventas_mes_anterior' => $ventasMesAnterior,
            'variacion_ventas' => round($variacionVentas, 2),
            'pedidos_mes' => $pedidosMesActual,
            'pedidos_mes_anterior' => $pedidosMesAnterior,
            'variacion_pedidos' => round($variacionPedidos, 2),
            'promedio_por_pedido' => round($promedioPorPedido, 2),
        ];
    }

    /**
     * Obtener ventas por día (últimos 30 días)
     */
    public function getVentasPorDiaProperty()
    {
        $fechaInicio = now()->subDays(30)->startOfDay();
        
        $ventas = Pedido::where('estado_pago', 'pagado')
            ->where('created_at', '>=', $fechaInicio)
            ->select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // Crear array completo de 30 días
        $dias = [];
        for ($i = 29; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $venta = $ventas->firstWhere('fecha', $fecha);
            $dias[] = [
                'fecha' => now()->subDays($i)->format('d/m'),
                'total' => $venta ? (float) $venta->total : 0,
            ];
        }

        return $dias;
    }

    /**
     * Obtener ventas por semana (últimas 12 semanas)
     */
    public function getVentasPorSemanaProperty()
    {
        $semanas = [];
        for ($i = 11; $i >= 0; $i--) {
            $inicioSemana = now()->subWeeks($i)->startOfWeek();
            $finSemana = now()->subWeeks($i)->endOfWeek();

            $total = Pedido::where('estado_pago', 'pagado')
                ->whereBetween('created_at', [$inicioSemana, $finSemana])
                ->sum('total');

            $semanas[] = [
                'semana' => 'Sem ' . (now()->subWeeks($i)->week),
                'total' => (float) $total,
            ];
        }

        return $semanas;
    }

    /**
     * Obtener ventas por mes (últimos 12 meses)
     */
    public function getVentasPorMesProperty()
    {
        $meses = [];
        for ($i = 11; $i >= 0; $i--) {
            $inicioMes = now()->subMonths($i)->startOfMonth();
            $finMes = now()->subMonths($i)->endOfMonth();

            $total = Pedido::where('estado_pago', 'pagado')
                ->whereBetween('created_at', [$inicioMes, $finMes])
                ->sum('total');

            $meses[] = [
                'mes' => now()->subMonths($i)->format('M'),
                'mes_completo' => now()->subMonths($i)->format('M Y'),
                'total' => (float) $total,
            ];
        }

        return $meses;
    }

    /**
     * Obtener pedidos por estado
     */
    public function getPedidosPorEstadoProperty()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'en_preparacion' => 'En Preparación',
            'listo' => 'Listo',
            'en_camino' => 'En Camino',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
        ];

        $datos = [];
        foreach ($estados as $estado => $label) {
            $count = Pedido::where('estado', $estado)->count();
            $datos[] = [
                'estado' => $label,
                'cantidad' => $count,
            ];
        }

        return $datos;
    }

    /**
     * Obtener pedidos por día de la semana
     */
    public function getPedidosPorDiaSemanaProperty()
    {
        $diasSemana = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
        ];

        $pedidos = Pedido::select(
            DB::raw('DAYOFWEEK(created_at) as dia_semana'),
            DB::raw('COUNT(*) as cantidad')
        )
            ->groupBy('dia_semana')
            ->get();

        $datos = [];
        foreach ($diasSemana as $index => $dia) {
            // MySQL DAYOFWEEK devuelve 1=Domingo, 2=Lunes, 3=Martes, etc.
            // Mapeamos: Lunes (index 0) = 2, Martes (index 1) = 3, ..., Domingo (index 6) = 1
            $diaMySQL = $index === 6 ? 1 : $index + 2;
            $pedido = $pedidos->firstWhere('dia_semana', $diaMySQL);
            $datos[] = [
                'dia' => $dia,
                'cantidad' => $pedido ? (int) $pedido->cantidad : 0,
            ];
        }

        return $datos;
    }

    /**
     * Obtener tasa de cancelación
     */
    public function getTasaCancelacionProperty()
    {
        $totalPedidos = Pedido::count();
        $pedidosCancelados = Pedido::where('estado', 'cancelado')->count();

        if ($totalPedidos === 0) {
            return 0;
        }

        return round(($pedidosCancelados / $totalPedidos) * 100, 2);
    }

    /**
     * Obtener top 10 productos más vendidos
     */
    public function getTopProductosProperty()
    {
        $productos = DetallePedido::join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->where('pedidos.estado_pago', 'pagado')
            ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->select(
                'productos.id',
                'productos.nombre',
                DB::raw('SUM(detalle_pedidos.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_pedidos.subtotal) as ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return $productos->map(function ($producto) {
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'cantidad' => (int) $producto->total_vendido,
                'ingresos' => (float) $producto->ingresos,
            ];
        })->toArray();
    }

    /**
     * Obtener productos por categoría
     */
    public function getProductosPorCategoriaProperty()
    {
        $categorias = Categoria::withCount('productos')
            ->having('productos_count', '>', 0)
            ->get();

        return $categorias->map(function ($categoria) {
            return [
                'nombre' => $categoria->nombre,
                'cantidad' => $categoria->productos_count,
            ];
        })->toArray();
    }

    /**
     * Obtener productos con stock bajo
     */
    public function getProductosStockBajoProperty()
    {
        return Producto::where('stock', '<', 10)
            ->where('activo', true)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'stock' => $producto->stock,
                ];
            })
            ->toArray();
    }

    /**
     * Obtener ingresos por método de pago
     */
    public function getIngresosPorMetodoPagoProperty()
    {
        $transacciones = Transaccion::where('estado', 'aprobado')
            ->select(
                'metodo_pago',
                DB::raw('SUM(monto) as total')
            )
            ->groupBy('metodo_pago')
            ->get();

        return $transacciones->map(function ($transaccion) {
            return [
                'metodo' => $transaccion->metodo_pago ?? 'No especificado',
                'total' => (float) $transaccion->total,
            ];
        })->toArray();
    }

    /**
     * Obtener ingresos diarios (últimos 30 días)
     */
    public function getIngresosDiariosProperty()
    {
        $fechaInicio = now()->subDays(30)->startOfDay();

        $ingresos = Transaccion::where('estado', 'aprobado')
            ->where('fecha_procesamiento', '>=', $fechaInicio)
            ->select(
                DB::raw('DATE(fecha_procesamiento) as fecha'),
                DB::raw('SUM(monto) as total')
            )
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // Crear array completo de 30 días
        $dias = [];
        for ($i = 29; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $ingreso = $ingresos->firstWhere('fecha', $fecha);
            $dias[] = [
                'fecha' => now()->subDays($i)->format('d/m'),
                'total' => $ingreso ? (float) $ingreso->total : 0,
            ];
        }

        return $dias;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-home');
    }
}


<?php

namespace App\Livewire\Cocina;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.dashboard-layout')]
class Cocina extends Component
{
    // Modal de detalles
    public $showDetailModal = false;
    public $pedidoSeleccionado;

    // Para auto-refresh (polling cada 30 segundos)
    public function mount()
    {
        // Verificar que el usuario esté autenticado y tenga rol adecuado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'cocina'])) {
            abort(403, 'No tienes permisos para acceder a este módulo.');
        }
    }

    /**
     * Escuchar eventos de WebSocket para actualizar en tiempo real
     */
    protected function getListeners()
    {
        return [
            "echo:cocina,.pedido.creado" => '$refresh',
            "echo:cocina,.pedido.cambio-estado" => '$refresh',
            "echo:cocina,.pedido.cancelado" => '$refresh',
        ];
    }

    /**
     * Obtener pedidos pendientes de cocinar (estado: pendiente con pago confirmado)
     */
    public function getPedidosPendientesProperty()
    {
        return Pedido::with(['user', 'detalles.producto'])
            ->where('estado', 'pendiente')
            ->where('estado_pago', 'pagado')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($pedido) {
                $pedido->minutos_transcurridos = Carbon::parse($pedido->created_at)->diffInMinutes(now());
                $pedido->es_urgente = $pedido->minutos_transcurridos > 1;
                return $pedido;
            });
    }

    /**
     * Obtener pedidos en preparación activa
     */
    public function getPedidosEnPreparacionProperty()
    {
        return Pedido::with(['user', 'detalles.producto'])
            ->where('estado', 'en_preparacion')
            ->orderBy('updated_at', 'asc')
            ->get()
            ->map(function ($pedido) {
                $fechaActualizacion = Carbon::parse($pedido->updated_at);
                $minutos = (int) round($fechaActualizacion->diffInMinutes(now()));
                $horas = (int) $fechaActualizacion->diffInHours(now());
                
                // Formatear tiempo de forma amigable
                if ($minutos < 1) {
                    $pedido->tiempo_formateado = 'Hace menos de 1 min';
                } elseif ($minutos < 60) {
                    $pedido->tiempo_formateado = "Hace {$minutos} " . ($minutos == 1 ? 'minuto' : 'minutos');
                } elseif ($horas < 24) {
                    $minutosRestantes = $minutos % 60;
                    if ($minutosRestantes == 0) {
                        $pedido->tiempo_formateado = "Hace {$horas} " . ($horas == 1 ? 'hora' : 'horas');
                    } else {
                        $pedido->tiempo_formateado = "Hace {$horas} " . ($horas == 1 ? 'hora' : 'horas') . " y {$minutosRestantes} " . ($minutosRestantes == 1 ? 'minuto' : 'minutos');
                    }
                } else {
                    $dias = (int) $fechaActualizacion->diffInDays(now());
                    $pedido->tiempo_formateado = "Hace {$dias} " . ($dias == 1 ? 'día' : 'días');
                }
                
                // Mantener minutos_transcurridos para compatibilidad si se usa en otro lugar
                $pedido->minutos_transcurridos = $minutos;
                // Marcar como urgente si lleva más de 15 minutos en preparación
                $pedido->es_urgente = $pedido->minutos_transcurridos > 1;
                return $pedido;
            });
    }

    /**
     * Obtener pedidos listos para entregar
     */
    public function getPedidosListosProperty()
    {
        return Pedido::with(['user', 'detalles.producto'])
            ->where('listo_at', '!=', null)
            ->whereDate('listo_at', today())
            ->orderBy('listo_at', 'desc')
            ->get();
    }

    /**
     * Calcular estadísticas
     */
    public function getStatsProperty()
    {
        $pedidosHoy = Pedido::whereDate('created_at', today())
            ->where('estado_pago', 'pagado');

        // Calcular tiempo promedio de pedidos completados hoy
        $tiempoPromedio = 0;
        $pedidosCompletadosHoy = Pedido::whereDate('listo_at', today())
            ->whereNotNull('listo_at')
            ->get();

        if ($pedidosCompletadosHoy->count() > 0) {
            $totalMinutos = 0;
            foreach ($pedidosCompletadosHoy as $pedido) {
                $totalMinutos += Carbon::parse($pedido->created_at)->diffInMinutes($pedido->listo_at);
            }
            $tiempoPromedio = round($totalMinutos / $pedidosCompletadosHoy->count());
        }

        return [
            'pendientes' => Pedido::where('estado', 'pendiente')
                ->where('estado_pago', 'pagado')
                ->count(),
            'en_preparacion' => Pedido::where('estado', 'en_preparacion')->count(),
            'completados_hoy' => $pedidosCompletadosHoy->count(),
            'tiempo_promedio' => $tiempoPromedio,
        ];
    }

    /**
     * Iniciar preparación de un pedido
     */
    public function iniciarPreparacion($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);

        if ($pedido->estado === 'pendiente' && $pedido->estado_pago === 'pagado') {
            $pedido->update(['estado' => 'en_preparacion']);

            session()->flash('message', "Pedido {$pedido->numero_pedido} iniciado en cocina");
        }
    }

    /**
     * Marcar pedido como listo
     */
    public function marcarComoListo($pedidoId)
    {
        try {
            DB::beginTransaction();

            // Cargar el pedido con todas las relaciones necesarias
            $pedido = Pedido::with([
                'detalles.producto',
                'detalles.promocion.productos'
            ])->findOrFail($pedidoId);

            if ($pedido->estado !== 'en_preparacion') {
                session()->flash('error', "El pedido {$pedido->numero_pedido} no está en preparación");
                DB::rollBack();
                return;
            }

            // Descontar stock de los productos según los detalles del pedido
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->producto_id) {
                    // Producto individual
                    $producto = $detalle->producto;
                    if ($producto) {
                        $cantidadADeducir = $detalle->cantidad;
                        
                        // Verificar que haya stock suficiente
                        if ($producto->stock < $cantidadADeducir) {
                            DB::rollBack();
                            session()->flash('error', "Stock insuficiente para el producto {$producto->nombre}. Stock disponible: {$producto->stock}, requerido: {$cantidadADeducir}");
                            return;
                        }

                        // Descontar stock
                        $producto->decrement('stock', $cantidadADeducir);
                        
                        // Actualizar estado del producto según el nuevo stock
                        $nuevoStock = $producto->fresh()->stock;
                        $nuevoEstado = $this->calcularEstado($nuevoStock);
                        $producto->update(['estado' => $nuevoEstado]);
                    }
                } elseif ($detalle->promocion_id) {
                    // Promoción: descontar stock de cada producto de la promoción
                    $promocion = $detalle->promocion;
                    if ($promocion && $promocion->productos) {
                        foreach ($promocion->productos as $producto) {
                            $cantidadADeducir = $producto->pivot->cantidad * $detalle->cantidad;
                            
                            // Verificar que haya stock suficiente
                            if ($producto->stock < $cantidadADeducir) {
                                DB::rollBack();
                                session()->flash('error', "Stock insuficiente para el producto {$producto->nombre} en la promoción {$promocion->nombre}. Stock disponible: {$producto->stock}, requerido: {$cantidadADeducir}");
                                return;
                            }

                            // Descontar stock
                            $producto->decrement('stock', $cantidadADeducir);
                            
                            // Actualizar estado del producto según el nuevo stock
                            $nuevoStock = $producto->fresh()->stock;
                            $nuevoEstado = $this->calcularEstado($nuevoStock);
                            $producto->update(['estado' => $nuevoEstado]);
                        }
                    }
                }
            }

            // Actualizar el estado del pedido a "listo"
            $pedido->update([
                'estado' => 'listo',
                'listo_at' => now()
            ]);

            DB::commit();

            session()->flash('message', "Pedido {$pedido->numero_pedido} está listo para entregar");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al marcar pedido como listo y descontar stock', [
                'pedido_id' => $pedidoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', "Error al procesar el pedido: {$e->getMessage()}");
        }
    }

    /**
     * Calcular el estado del producto según su stock
     */
    private function calcularEstado($stock)
    {
        if ($stock <= 0) {
            return 'agotado';
        } elseif ($stock <= 5) {
            return 'stock_bajo';
        } else {
            return 'disponible';
        }
    }

    /**
     * Ver detalles del pedido
     */
    public function verDetalles($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with(['user', 'detalles.producto', 'detalles.promocion.productos', 'transaccion'])
            ->findOrFail($pedidoId);
        $this->showDetailModal = true;
    }

    /**
     * Cerrar modal de detalles
     */
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->pedidoSeleccionado = null;
    }

    public function render()
    {
        return view('livewire.cocina.cocina', [
            'pedidosPendientes' => $this->pedidosPendientes,
            'pedidosEnPreparacion' => $this->pedidosEnPreparacion,
            'pedidosListos' => $this->pedidosListos,
            'stats' => $this->stats,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'delivery_id',
        'numero_pedido',
        'estado',
        'estado_pago',
        'metodo_pago_preferido',
        'subtotal',
        'total',
        'direccion_entrega',
        'telefono_contacto',
        'notas',
        'listo_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the user that owns the pedido.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the delivery assigned to the pedido.
     */
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_id');
    }

    /**
     * Get the detalle pedidos for the pedido.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * Get the transaccion for the pedido.
     */
    public function transaccion(): HasOne
    {
        return $this->hasOne(Transaccion::class);
    }

    /**
     * Scope a query to only include pending pedidos.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope a query to only include completed pedidos.
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'entregado');
    }

    /**
     * Scope a query to only include cancelled pedidos.
     */
    public function scopeCancelados($query)
    {
        return $query->where('estado', 'cancelado');
    }

    /**
     * Scope a query to only include in progress pedidos.
     */
    public function scopeEnProceso($query)
    {
        return $query->whereIn('estado', ['en_preparacion', 'listo', 'en_camino']);
    }

    /**
     * Scope a query to only include pedidos ready for delivery.
     */
    public function scopeListo($query)
    {
        return $query->where('estado', 'listo');
    }

    /**
     * Scope a query to only include pedidos en camino.
     */
    public function scopeEnCamino($query)
    {
        return $query->where('estado', 'en_camino');
    }

    /**
     * Generate a unique numero pedido.
     */
    public static function generarNumeroPedido(): string
    {
        $year = date('Y');
        $lastPedido = static::withTrashed()
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastPedido ? intval(substr($lastPedido->numero_pedido, -4)) + 1 : 1;

        return sprintf('PED-%s-%04d', $year, $nextNumber);
    }

    /**
     * Calculate the total from detalle pedidos.
     */
    public function calcularTotal(): void
    {
        $subtotal = $this->detalles->sum('subtotal');

        $this->update([
            'subtotal' => $subtotal,
            'total' => $subtotal, // Aquí se podrían agregar descuentos, impuestos, etc.
        ]);
    }

    /**
     * Get the status badge color.
     */
    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'yellow',
            'en_preparacion' => 'blue',
            'listo' => 'purple',
            'en_camino' => 'indigo',
            'entregado' => 'green',
            'cancelado' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the status text.
     */
    public function getEstadoTextoAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'en_preparacion' => 'En Preparación',
            'listo' => 'Listo',
            'en_camino' => 'En Camino',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => 'Desconocido'
        };
    }

    /**
     * Verificar si el pedido está pagado
     */
    public function isPagado(): bool
    {
        return $this->estado_pago === 'pagado';
    }

    /**
     * Verificar si el pedido tiene pago pendiente
     */
    public function isPagoPendiente(): bool
    {
        return $this->estado_pago === 'pendiente';
    }

    /**
     * Verificar si el pago falló
     */
    public function isPagoFallido(): bool
    {
        return $this->estado_pago === 'fallido';
    }

    /**
     * Scope para pedidos pagados
     */
    public function scopePagados($query)
    {
        return $query->where('estado_pago', 'pagado');
    }

    /**
     * Scope para pedidos con pago pendiente
     */
    public function scopePagoPendiente($query)
    {
        return $query->where('estado_pago', 'pendiente');
    }
}
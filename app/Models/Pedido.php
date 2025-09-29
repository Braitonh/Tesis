<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'numero_pedido',
        'estado',
        'subtotal',
        'total',
        'direccion_entrega',
        'telefono_contacto',
        'notas',
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
     * Get the detalle pedidos for the pedido.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
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
}
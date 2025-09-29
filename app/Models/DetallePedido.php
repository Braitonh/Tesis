<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the pedido that owns the detalle pedido.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Get the producto that owns the detalle pedido.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Calculate and set the subtotal based on cantidad and precio_unitario.
     */
    public function calcularSubtotal(): void
    {
        $this->subtotal = $this->cantidad * $this->precio_unitario;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate subtotal before saving
        static::saving(function ($detalle) {
            if ($detalle->isDirty(['cantidad', 'precio_unitario'])) {
                $detalle->calcularSubtotal();
            }
        });

        // Recalculate pedido total after saving or deleting
        static::saved(function ($detalle) {
            $detalle->pedido->calcularTotal();
        });

        static::deleted(function ($detalle) {
            $detalle->pedido->calcularTotal();
        });
    }
}
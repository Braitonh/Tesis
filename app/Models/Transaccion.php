<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaccion extends Model
{
    protected $table = 'transacciones';

    protected $fillable = [
        'pedido_id',
        'metodo_pago',
        'estado',
        'monto',
        'numero_transaccion',
        'detalles_tarjeta',
        'mensaje_respuesta',
        'fecha_procesamiento',
        'mercado_pago_preference_id',
        'mercado_pago_payment_id',
        'mercado_pago_status',
    ];

    protected $casts = [
        'detalles_tarjeta' => 'array',
        'fecha_procesamiento' => 'datetime',
        'monto' => 'decimal:2',
    ];

    /**
     * Relación con Pedido
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Generar número de transacción único
     */
    public static function generarNumeroTransaccion(): string
    {
        return 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }

    /**
     * Scope para transacciones aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobado');
    }

    /**
     * Scope para transacciones rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazado');
    }

    /**
     * Verificar si la transacción está aprobada
     */
    public function isAprobada(): bool
    {
        return $this->estado === 'aprobado';
    }

    /**
     * Verificar si la transacción está rechazada
     */
    public function isRechazada(): bool
    {
        return $this->estado === 'rechazado';
    }

    /**
     * Verificar si la transacción está procesando
     */
    public function isProcesando(): bool
    {
        return $this->estado === 'procesando';
    }
}

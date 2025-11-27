<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that the activity log belongs to.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get a human-readable description of the action.
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'pedido.estado_cambiado' => 'Cambio de Estado',
            'pedido.asignado_delivery' => 'Asignación de Delivery',
            'pedido.actualizado' => 'Actualización de Pedido',
            'pedido.cancelado' => 'Cancelación de Pedido',
            default => ucfirst(str_replace('.', ' ', $this->action)),
        };
    }

    /**
     * Get the entity name (e.g., "Pedido PED-2025-0001").
     */
    public function getEntityNameAttribute(): string
    {
        if ($this->model_type && $this->model_id) {
            $modelClass = $this->model_type;
            $model = $modelClass::find($this->model_id);
            if ($model) {
                if ($modelClass === Pedido::class || $model instanceof Pedido) {
                    return "Pedido {$model->numero_pedido}";
                }
                return class_basename($this->model_type) . " #{$this->model_id}";
            }
        }
        return 'N/A';
    }
}


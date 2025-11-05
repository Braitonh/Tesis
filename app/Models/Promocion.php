<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_descuento_porcentaje',
        'imagen',
        'activo',
        'destacado',
        'sort_order',
    ];

    protected $casts = [
        'precio_descuento_porcentaje' => 'decimal:2',
        'activo' => 'boolean',
        'destacado' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the productos that belong to the promocion.
     */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'promocion_producto')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    /**
     * Get the detalle pedidos for the promocion.
     */
    public function detallePedidos(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * Scope a query to only include active promociones.
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope a query to only include featured promociones.
     */
    public function scopeDestacado($query)
    {
        return $query->where('destacado', true);
    }

    /**
     * Scope a query to order promociones by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get the precio original (sum of all productos).
     */
    public function getPrecioOriginalAttribute()
    {
        return $this->productos->sum(function ($producto) {
            return $producto->precio_final * $producto->pivot->cantidad;
        });
    }

    /**
     * Get the precio final with discount applied.
     */
    public function getPrecioFinalAttribute()
    {
        $precioOriginal = $this->precio_original;
        $descuento = ($precioOriginal * $this->precio_descuento_porcentaje) / 100;
        return max(0, $precioOriginal - $descuento);
    }

    /**
     * Get the descuento aplicado in money.
     */
    public function getDescuentoAplicadoAttribute()
    {
        return $this->precio_original - $this->precio_final;
    }

    /**
     * Verify if all productos have enough stock.
     */
    public function verificarStock($cantidad = 1)
    {
        foreach ($this->productos as $producto) {
            $cantidadNecesaria = $producto->pivot->cantidad * $cantidad;
            if ($producto->stock < $cantidadNecesaria) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get minimum stock available based on productos.
     */
    public function getStockDisponibleAttribute()
    {
        if ($this->productos->isEmpty()) {
            return 0;
        }

        $minStock = PHP_INT_MAX;
        foreach ($this->productos as $producto) {
            $stockPosible = floor($producto->stock / $producto->pivot->cantidad);
            $minStock = min($minStock, $stockPosible);
        }

        return max(0, $minStock);
    }

    /**
     * Get the full image URL for the promocion.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->imagen) {
            return $this->getDefaultImageUrl();
        }

        // Si es una URL externa (http/https)
        if (filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            return $this->imagen;
        }

        // Si es una ruta local
        if (Storage::disk('public')->exists($this->imagen)) {
            return Storage::url($this->imagen);
        }

        // Fallback al placeholder si el archivo no existe
        return $this->getDefaultImageUrl();
    }

    /**
     * Get default placeholder image URL.
     */
    private function getDefaultImageUrl()
    {
        return 'https://via.placeholder.com/400x300/ff6600/ffffff?text=🎁+Promoción';
    }

    /**
     * Check if promocion has a local image file.
     */
    public function hasLocalImage()
    {
        return $this->imagen &&
               !filter_var($this->imagen, FILTER_VALIDATE_URL) &&
               Storage::disk('public')->exists($this->imagen);
    }

    /**
     * Check if promocion is using external image URL.
     */
    public function hasExternalImage()
    {
        return $this->imagen && filter_var($this->imagen, FILTER_VALIDATE_URL);
    }
}

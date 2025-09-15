<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'precio_descuento',
        'stock',
        'estado',
        'activo',
        'categoria_id',
        'destacado',
        'imagen',
        'sort_order',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_descuento' => 'decimal:2',
        'activo' => 'boolean',
        'destacado' => 'boolean',
        'stock' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the categoria that owns the producto.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Scope a query to only include active productos.
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope a query to only include featured productos.
     */
    public function scopeDestacado($query)
    {
        return $query->where('destacado', true);
    }

    /**
     * Scope a query to only include available productos.
     */
    public function scopeDisponible($query)
    {
        return $query->where('estado', 'disponible');
    }

    /**
     * Scope a query to order productos by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get the precio with discount if available.
     */
    public function getPrecioFinalAttribute()
    {
        return $this->precio_descuento ?? $this->precio;
    }

    /**
     * Check if producto has discount.
     */
    public function getTieneDescuentoAttribute()
    {
        return !is_null($this->precio_descuento);
    }

    /**
     * Get the full image URL for the producto.
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
        if (\Storage::disk('public')->exists($this->imagen)) {
            return \Storage::url($this->imagen);
        }

        // Fallback al placeholder si el archivo no existe
        return $this->getDefaultImageUrl();
    }

    /**
     * Get default placeholder image URL based on category.
     */
    private function getDefaultImageUrl()
    {
        $categoryImages = [
            'Hamburguesas' => 'https://via.placeholder.com/400x300/ff6600/ffffff?text=🍔+Hamburguesa',
            'Pizzas' => 'https://via.placeholder.com/400x300/ff6600/ffffff?text=🍕+Pizza',
            'Bebidas' => 'https://via.placeholder.com/400x300/ff6600/ffffff?text=🥤+Bebida',
            'Postres' => 'https://via.placeholder.com/400x300/ff6600/ffffff?text=🍰+Postre',
        ];

        $categoryName = $this->categoria->nombre ?? 'Productos';
        return $categoryImages[$categoryName] ?? 'https://via.placeholder.com/400x300/ff6600/ffffff?text=🍽️+Producto';
    }

    /**
     * Get the local storage path for categoria.
     */
    public function getCategoriaStoragePathAttribute()
    {
        $categoryPaths = [
            'Hamburguesas' => 'productos/hamburguesas',
            'Pizzas' => 'productos/pizzas',
            'Bebidas' => 'productos/bebidas',
            'Postres' => 'productos/postres',
        ];

        $categoryName = $this->categoria->nombre ?? 'Productos';
        return $categoryPaths[$categoryName] ?? 'productos/otros';
    }

    /**
     * Check if producto has a local image file.
     */
    public function hasLocalImage()
    {
        return $this->imagen &&
               !filter_var($this->imagen, FILTER_VALIDATE_URL) &&
               \Storage::disk('public')->exists($this->imagen);
    }

    /**
     * Check if producto is using external image URL.
     */
    public function hasExternalImage()
    {
        return $this->imagen && filter_var($this->imagen, FILTER_VALIDATE_URL);
    }
}

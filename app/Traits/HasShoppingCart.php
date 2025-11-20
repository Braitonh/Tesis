<?php

namespace App\Traits;

use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Support\Collection;

trait HasShoppingCart
{
    /**
     * Get the cart from session.
     */
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart(int $productoId, int $cantidad = 1): void
    {
        $producto = Producto::find($productoId);

        if (!$producto || !$producto->activo || $producto->estado === 'agotado') {
            return;
        }

        $cart = $this->getCart();

        if (isset($cart[$productoId])) {
            // Si ya existe, incrementar cantidad
            $cart[$productoId]['cantidad'] += $cantidad;
        } else {
            // Agregar nuevo producto
            $cart[$productoId] = [
                'cantidad' => $cantidad,
                'precio' => $producto->precio_descuento ?? $producto->precio,
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Update cart item quantity.
     */
    public function updateCartItem(int $productoId, int $cantidad): void
    {
        if ($cantidad <= 0) {
            $this->removeFromCart($productoId);
            return;
        }

        $cart = $this->getCart();

        if (isset($cart[$productoId])) {
            $cart[$productoId]['cantidad'] = $cantidad;
            session()->put('cart', $cart);
        }
    }

    /**
     * Increment cart item quantity.
     */
    public function incrementCartItem(int $productoId): void
    {
        $cart = $this->getCart();

        if (isset($cart[$productoId])) {
            $cart[$productoId]['cantidad']++;
            session()->put('cart', $cart);
        }
    }

    /**
     * Decrement cart item quantity.
     */
    public function decrementCartItem(int $productoId): void
    {
        $cart = $this->getCart();

        if (isset($cart[$productoId])) {
            $cart[$productoId]['cantidad']--;

            if ($cart[$productoId]['cantidad'] <= 0) {
                $this->removeFromCart($productoId);
            } else {
                session()->put('cart', $cart);
            }
        }
    }

    /**
     * Remove a product from the cart.
     */
    public function removeFromCart(int $productoId): void
    {
        $cart = $this->getCart();
        unset($cart[$productoId]);
        session()->put('cart', $cart);
    }

    /**
     * Clear the entire cart.
     */
    public function clearCart(): void
    {
        session()->forget('cart');
    }

    /**
     * Get cart items with full product data.
     */
    public function getCartItems(): Collection
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return collect();
        }

        $productoIds = array_keys($cart);
        $productos = Producto::with('categoria')
            ->whereIn('id', $productoIds)
            ->get();

        return $productos->map(function ($producto) use ($cart) {
            $cartItem = $cart[$producto->id];

            return (object) [
                'producto' => $producto,
                'cantidad' => $cartItem['cantidad'],
                'precio' => $cartItem['precio'],
                'subtotal' => $cartItem['cantidad'] * $cartItem['precio'],
            ];
        });
    }

    /**
     * Get total number of items in cart.
     */
    public function getCartCount(): int
    {
        $cart = $this->getCart();
        $productsCount = array_sum(array_column($cart, 'cantidad'));

        $promocionesCart = $this->getPromocionesCart();
        $promocionesCount = array_sum(array_column($promocionesCart, 'cantidad'));

        return $productsCount + $promocionesCount;
    }

    /**
     * Get cart total amount.
     */
    public function getCartTotal(): float
    {
        return $this->getCartItems()->sum('subtotal') + $this->getPromocionesItems()->sum('subtotal');
    }

    // ========== MÉTODOS PARA PROMOCIONES ==========

    /**
     * Get the promociones cart from session.
     */
    public function getPromocionesCart(): array
    {
        return session()->get('promociones_cart', []);
    }

    /**
     * Add a promocion to the cart.
     */
    public function addPromocionToCart(int $promocionId, int $cantidad = 1): void
    {
        $promocion = Promocion::with('productos')->find($promocionId);

        if (!$promocion || !$promocion->activo || !$promocion->verificarStock($cantidad)) {
            return;
        }

        $promocionesCart = $this->getPromocionesCart();

        if (isset($promocionesCart[$promocionId])) {
            // Si ya existe, incrementar cantidad
            $promocionesCart[$promocionId]['cantidad'] += $cantidad;
        } else {
            // Agregar nueva promoción
            $promocionesCart[$promocionId] = [
                'cantidad' => $cantidad,
                'precio' => $promocion->precio_final,
            ];
        }

        session()->put('promociones_cart', $promocionesCart);
    }

    /**
     * Update promocion cart item quantity.
     */
    public function updatePromocionCartItem(int $promocionId, int $cantidad): void
    {
        if ($cantidad <= 0) {
            $this->removePromocionFromCart($promocionId);
            return;
        }

        $promocionesCart = $this->getPromocionesCart();

        if (isset($promocionesCart[$promocionId])) {
            $promocionesCart[$promocionId]['cantidad'] = $cantidad;
            session()->put('promociones_cart', $promocionesCart);
        }
    }

    /**
     * Increment promocion cart item quantity.
     */
    public function incrementPromocionCartItem(int $promocionId): void
    {
        $promocionesCart = $this->getPromocionesCart();

        if (isset($promocionesCart[$promocionId])) {
            $promocionesCart[$promocionId]['cantidad']++;
            session()->put('promociones_cart', $promocionesCart);
        }
    }

    /**
     * Decrement promocion cart item quantity.
     */
    public function decrementPromocionCartItem(int $promocionId): void
    {
        $promocionesCart = $this->getPromocionesCart();

        if (isset($promocionesCart[$promocionId])) {
            $promocionesCart[$promocionId]['cantidad']--;

            if ($promocionesCart[$promocionId]['cantidad'] <= 0) {
                $this->removePromocionFromCart($promocionId);
            } else {
                session()->put('promociones_cart', $promocionesCart);
            }
        }
    }

    /**
     * Remove a promocion from the cart.
     */
    public function removePromocionFromCart(int $promocionId): void
    {
        $promocionesCart = $this->getPromocionesCart();
        unset($promocionesCart[$promocionId]);
        session()->put('promociones_cart', $promocionesCart);
    }

    /**
     * Get promociones cart items with full data.
     */
    public function getPromocionesItems(): Collection
    {
        $promocionesCart = $this->getPromocionesCart();

        if (empty($promocionesCart)) {
            return collect();
        }

        $promocionIds = array_keys($promocionesCart);
        $promociones = Promocion::with('productos.categoria')
            ->whereIn('id', $promocionIds)
            ->get();

        return $promociones->map(function ($promocion) use ($promocionesCart) {
            $cartItem = $promocionesCart[$promocion->id];

            return (object) [
                'promocion' => $promocion,
                'cantidad' => $cartItem['cantidad'],
                'precio' => $cartItem['precio'],
                'subtotal' => $cartItem['cantidad'] * $cartItem['precio'],
            ];
        });
    }

    /**
     * Clear the promociones cart.
     */
    public function clearPromocionesCart(): void
    {
        session()->forget('promociones_cart');
    }

    /**
     * Clear both carts (productos and promociones).
     */
    public function clearAllCarts(): void
    {
        $this->clearCart();
        $this->clearPromocionesCart();
    }
}
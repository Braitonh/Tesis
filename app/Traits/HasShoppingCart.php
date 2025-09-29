<?php

namespace App\Traits;

use App\Models\Producto;
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
        return array_sum(array_column($cart, 'cantidad'));
    }

    /**
     * Get cart total amount.
     */
    public function getCartTotal(): float
    {
        return $this->getCartItems()->sum('subtotal');
    }
}
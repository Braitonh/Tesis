<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapeo de productos a imágenes placeholder específicas
        $imagenesProductos = [
            // HAMBURGUESAS
            'Hamburguesa Clásica' => 'https://picsum.photos/400/300?random=1',
            'Hamburguesa Premium' => 'https://picsum.photos/400/300?random=2',
            'Hamburguesa BBQ' => 'https://picsum.photos/400/300?random=3',
            'Hamburguesa Vegana' => 'https://picsum.photos/400/300?random=4',
            'Hamburguesa Doble' => 'https://picsum.photos/400/300?random=5',
            'Hamburguesa Crispy' => 'https://picsum.photos/400/300?random=6',

            // PIZZAS
            'Pizza Margherita' => 'https://picsum.photos/400/300?random=7',
            'Pizza Pepperoni' => 'https://picsum.photos/400/300?random=8',
            'Pizza Hawaiana' => 'https://picsum.photos/400/300?random=9',
            'Pizza Vegetariana' => 'https://picsum.photos/400/300?random=10',
            'Pizza Cuatro Quesos' => 'https://picsum.photos/400/300?random=11',
            'Pizza BBQ' => 'https://picsum.photos/400/300?random=12',

            // BEBIDAS
            'Refresco Cola' => 'https://picsum.photos/400/300?random=13',
            'Smoothie Tropical' => 'https://picsum.photos/400/300?random=14',
            'Jugo de Naranja Natural' => 'https://picsum.photos/400/300?random=15',
            'Agua Mineral' => 'https://picsum.photos/400/300?random=16',
            'Café Americano' => 'https://picsum.photos/400/300?random=17',
            'Milkshake Chocolate' => 'https://picsum.photos/400/300?random=18',

            // POSTRES
            'Cheesecake de Frutos Rojos' => 'https://picsum.photos/400/300?random=19',
            'Brownie de Chocolate' => 'https://picsum.photos/400/300?random=20',
            'Helado de Vainilla' => 'https://picsum.photos/400/300?random=21',
            'Tiramisú' => 'https://picsum.photos/400/300?random=22',
            'Flan Casero' => 'https://picsum.photos/400/300?random=23',
            'Mousse de Chocolate' => 'https://picsum.photos/400/300?random=24',
        ];

        // Actualizar cada producto con su imagen correspondiente
        foreach ($imagenesProductos as $nombreProducto => $urlImagen) {
            DB::table('productos')
                ->where('nombre', $nombreProducto)
                ->update([
                    'imagen' => $urlImagen,
                    'updated_at' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover todas las imágenes (volver a null)
        DB::table('productos')->update([
            'imagen' => null,
            'updated_at' => now()
        ]);
    }
};

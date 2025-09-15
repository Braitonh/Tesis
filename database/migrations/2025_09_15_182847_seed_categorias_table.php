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
        $categorias = [
            [
                'nombre' => 'Hamburguesas',
                'descripcion' => 'Deliciosas hamburguesas con ingredientes frescos y carne de primera calidad',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Pizzas',
                'descripcion' => 'Pizzas artesanales con masa fresca y los mejores ingredientes',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Refrescos, jugos naturales y bebidas variadas para acompañar tu comida',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Postres',
                'descripcion' => 'Dulces y postres caseros para endulzar tu día',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->updateOrInsert(
                ['nombre' => $categoria['nombre']], // Condición para evitar duplicados
                $categoria // Datos a insertar/actualizar
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $categorias = ['Hamburguesas', 'Pizzas', 'Bebidas', 'Postres'];

        DB::table('categorias')->whereIn('nombre', $categorias)->delete();
    }
};

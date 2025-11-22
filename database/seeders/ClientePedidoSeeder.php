<?php

namespace Database\Seeders;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ClientePedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        // Verificar que existan productos
        $productos = Producto::where('activo', true)->get();

        if ($productos->isEmpty()) {
            $this->command->error('No hay productos disponibles. Ejecuta ProductoSeeder primero.');
            return;
        }

        $this->command->info("Creando 20 clientes con 5 pedidos cada uno...");

        // Crear 20 clientes
        for ($i = 1; $i <= 20; $i++) {
            $cliente = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role' => 'cliente',
                'dni' => $faker->numerify('########'),
                'direccion' => $faker->address(),
                'telefono' => $faker->numerify('098#######'),
                'email_verified_at' => now(),
                'password_created' => true,
                'is_blocked' => false,
            ]);

            $this->command->info("Cliente {$i}/20 creado: {$cliente->name}");

            // Crear 5 pedidos para cada cliente
            for ($j = 1; $j <= 5; $j++) {
                // 40% de probabilidad de que el pedido sea del mes actual
                if ($faker->boolean(40)) {
                    // Fecha del mes actual (desde el inicio del mes hasta ahora)
                    $inicioMes = now()->startOfMonth();
                    $fechaCreacion = $faker->dateTimeBetween($inicioMes, 'now');
                } else {
                    // Fecha histórica (últimos 30-60 días)
                    $fechaCreacion = $faker->dateTimeBetween('-60 days', '-30 days');
                }

                $pedido = Pedido::create([
                    'user_id' => $cliente->id,
                    'numero_pedido' => Pedido::generarNumeroPedido(),
                    'estado' => 'entregado',
                    'estado_pago' => 'pagado',
                    'metodo_pago_preferido' => 'efectivo',
                    'direccion_entrega' => $cliente->direccion,
                    'telefono_contacto' => $cliente->telefono,
                    'notas' => $faker->optional(0.3)->sentence(),
                    'subtotal' => 0, // Se calculará automáticamente
                    'total' => 0, // Se calculará automáticamente
                    'created_at' => $fechaCreacion,
                    'updated_at' => $fechaCreacion,
                ]);

                // Crear detalles de pedido con productos aleatorios
                $cantidadProductos = $faker->numberBetween(1, 5);
                $productosSeleccionados = $productos->random(min($cantidadProductos, $productos->count()));

                foreach ($productosSeleccionados as $producto) {
                    $cantidad = $faker->numberBetween(1, 3);
                    $precioUnitario = $producto->precio_final ?? $producto->precio;

                    DetallePedido::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $cantidad * $precioUnitario, // Se calculará automáticamente pero lo establecemos aquí
                    ]);
                }

                // Recalcular el total del pedido
                // Guardar la fecha de creación antes de calcularTotal() para restaurarla después
                $fechaOriginal = $pedido->created_at;
                $pedido->calcularTotal();
                // Restaurar updated_at al mismo valor que created_at
                $pedido->updateQuietly(['updated_at' => $fechaOriginal]);
            }

            $this->command->info("  → 5 pedidos creados para {$cliente->name}");
        }

        $this->command->info('');
        $this->command->info('✓ Seeder de clientes y pedidos completado exitosamente');
        $this->command->info('  → 20 clientes creados');
        $this->command->info('  → 100 pedidos creados (5 por cliente)');
        $this->command->info('  → Todos los pedidos están en estado "entregado" y pagados en "efectivo"');
    }
}


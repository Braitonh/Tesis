<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class ProductoSeeder extends Seeder
{
    /**
     * Mapeo de tipos de productos a categorías
     */
    private array $tipoCategoriaMap = [
        'cafe' => 'Bebidas',
        'donas' => 'Postres',
        'galletas' => 'Postres',
        'hamburguesas' => 'Hamburguesas',
        'pastel' => 'Postres',
        'pizzas' => 'Pizzas',
    ];

    /**
     * Nombres de productos por tipo
     */
    private array $nombresProductos = [
        'cafe' => ['Café', 'Café Especial', 'Café Premium', 'Café Express', 'Café Americano', 'Café Latte', 'Café Cappuccino', 'Café Mocha'],
        'donas' => ['Dona', 'Dona Glaseada', 'Dona de Chocolate', 'Dona de Vainilla', 'Dona Rellena', 'Dona Especial', 'Dona Premium'],
        'galletas' => ['Galleta', 'Galleta de Chocolate', 'Galleta de Vainilla', 'Galleta de Mantequilla', 'Galleta Premium', 'Galleta Artesanal'],
        'hamburguesas' => ['Hamburguesa', 'Hamburguesa Clásica', 'Hamburguesa Especial', 'Hamburguesa Premium', 'Hamburguesa Doble', 'Hamburguesa BBQ', 'Hamburguesa Deluxe'],
        'pastel' => ['Pastel', 'Pastel de Chocolate', 'Pastel de Vainilla', 'Pastel de Fresa', 'Pastel Premium', 'Pastel Artesanal', 'Torta Especial'],
        'pizzas' => ['Pizza', 'Pizza Margarita', 'Pizza Pepperoni', 'Pizza Hawaiana', 'Pizza Especial', 'Pizza Premium', 'Pizza Deluxe', 'Pizza Familiar'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $comidasPath = base_path('Comidas');

        // Verificar que la carpeta Comidas existe
        if (!File::exists($comidasPath)) {
            $this->command->error("La carpeta Comidas no existe en: {$comidasPath}");
            return;
        }

        // Obtener todas las imágenes .jpg
        $imagenes = File::glob($comidasPath . '/*.jpg');

        if (empty($imagenes)) {
            $this->command->warn('No se encontraron imágenes .jpg en la carpeta Comidas');
            return;
        }

        $this->command->info("Se encontraron " . count($imagenes) . " imágenes para procesar");

        // Contador de sort_order por categoría
        $sortOrderPorCategoria = [];

        foreach ($imagenes as $imagenPath) {
            $nombreArchivo = File::basename($imagenPath);
            
            // Extraer tipo de producto del nombre del archivo (ej: cafe_01.jpg -> cafe)
            if (!preg_match('/^([a-z]+)_\d+\.jpg$/i', $nombreArchivo, $matches)) {
                $this->command->warn("No se pudo extraer el tipo del archivo: {$nombreArchivo}");
                continue;
            }

            $tipo = strtolower($matches[1]);

            // Verificar que el tipo tiene una categoría asignada
            if (!isset($this->tipoCategoriaMap[$tipo])) {
                $this->command->warn("Tipo de producto no mapeado: {$tipo}");
                continue;
            }

            $nombreCategoria = $this->tipoCategoriaMap[$tipo];

            // Obtener la categoría de la base de datos
            $categoria = Categoria::where('nombre', $nombreCategoria)->first();

            if (!$categoria) {
                $this->command->error("Categoría no encontrada: {$nombreCategoria}");
                continue;
            }

            // Obtener ruta de almacenamiento para la categoría
            $storagePath = $this->getCategoryStoragePath($nombreCategoria);

            // Crear directorio si no existe
            Storage::disk('public')->makeDirectory($storagePath);

            // Generar nombre único para la imagen
            $nombreImagen = uniqid() . '_' . $nombreArchivo;
            $rutaDestino = $storagePath . '/' . $nombreImagen;

            // Copiar imagen al storage
            try {
                $contenido = File::get($imagenPath);
                Storage::disk('public')->put($rutaDestino, $contenido);
            } catch (\Exception $e) {
                $this->command->error("Error al copiar imagen {$nombreArchivo}: " . $e->getMessage());
                continue;
            }

            // Generar datos aleatorios para el producto
            $precio = $faker->randomFloat(2, 5.00, 50.00);
            $stock = $faker->numberBetween(0, 50);
            
            // Calcular estado según stock
            $estado = $this->calcularEstado($stock);

            // Precio con descuento (30% probabilidad)
            $precioDescuento = null;
            if ($faker->boolean(30)) {
                $porcentajeDescuento = $faker->numberBetween(10, 30);
                $precioDescuento = round($precio * (1 - $porcentajeDescuento / 100), 2);
            }

            // Nombre del producto
            $nombresDisponibles = $this->nombresProductos[$tipo] ?? [$tipo];
            $nombreBase = $faker->randomElement($nombresDisponibles);
            $numero = preg_replace('/.*_(\d+)\.jpg$/i', '$1', $nombreArchivo);
            $nombre = "{$nombreBase} #{$numero}";

            // Descripción aleatoria
            $descripcion = $faker->sentence(10);

            // Destacado (20% probabilidad)
            $destacado = $faker->boolean(20);

            // Sort order incremental por categoría
            if (!isset($sortOrderPorCategoria[$categoria->id])) {
                $sortOrderPorCategoria[$categoria->id] = 1;
            } else {
                $sortOrderPorCategoria[$categoria->id]++;
            }

            // Crear producto
            Producto::create([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'precio_descuento' => $precioDescuento,
                'stock' => $stock,
                'estado' => $estado,
                'activo' => true,
                'categoria_id' => $categoria->id,
                'destacado' => $destacado,
                'imagen' => $rutaDestino,
                'sort_order' => $sortOrderPorCategoria[$categoria->id],
            ]);

            $this->command->info("Producto creado: {$nombre} (Categoría: {$nombreCategoria})");
        }

        $this->command->info('Seeder de productos completado exitosamente');
    }

    /**
     * Obtener la ruta de almacenamiento según la categoría
     */
    private function getCategoryStoragePath(string $categoryName): string
    {
        $categoryPaths = [
            'Hamburguesas' => 'productos/hamburguesas',
            'Pizzas' => 'productos/pizzas',
            'Bebidas' => 'productos/bebidas',
            'Postres' => 'productos/postres',
        ];

        return $categoryPaths[$categoryName] ?? 'productos/otros';
    }

    /**
     * Calcular el estado del producto según el stock
     */
    private function calcularEstado(int $stock): string
    {
        if ($stock <= 0) {
            return 'agotado';
        } elseif ($stock <= 5) {
            return 'stock_bajo';
        } else {
            return 'disponible';
        }
    }
}


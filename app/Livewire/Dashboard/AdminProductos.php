<?php

namespace App\Livewire\Dashboard;

use App\Models\Categoria;
use App\Models\Producto;
use App\Traits\HasSortableItems;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
#[Layout('components.dashboard-layout')]
class AdminProductos extends Component
{
    use WithFileUploads, HasSortableItems;
    // Filtros
    public $filtroCategoria = '';
    public $filtroEstado = '';
    public $filtroDestacado = '';
    public $busqueda = '';

    // Delete confirmation modal
    public $showDeleteModal = false;
    public $productoToDelete;

    // Detail modal
    public $showDetailModal = false;
    public $productoToView;

    // Edit modal
    public $showEditModal = false;
    public $modalTitle = '';
    public $productoId;

    // Form fields
    public $nombre = '';
    public $descripcion = '';
    public $precio = '';
    public $precio_descuento = '';
    public $stock = '';
    public $estado = 'disponible';
    public $categoria_id = '';
    public $destacado = false;
    public $activo = true;
    public $imagen = '';
    public $imagen_file;

    // Para el reordenamiento (ya está en el trait como itemOrderJson)
    public $productOrderJson = '';

    // Propiedades para query string
    protected $updatesQueryString = [
        'busqueda' => ['except' => '', 'as' => 'q'],
        'filtroCategoria' => ['except' => '', 'as' => 'categoria'],
        'filtroEstado' => ['except' => '', 'as' => 'estado'],
        'filtroDestacado' => ['except' => '', 'as' => 'destacado']
    ];

    public function limpiarFiltros()
    {
        $this->filtroCategoria = '';
        $this->filtroEstado = '';
        $this->filtroDestacado = '';
        $this->busqueda = '';

        // Forzar re-render para asegurar que los event listeners se vinculen correctamente
        $this->dispatch('$refresh');
    }

    public function updatedFiltroCategoria()
    {
        // Asegurar que el componente se actualice correctamente
        $this->resetPage();
    }

    public function updatedFiltroEstado()
    {
        // Asegurar que el componente se actualice correctamente
        $this->resetPage();
    }

    public function updatedBusqueda()
    {
        // Asegurar que el componente se actualice correctamente
        $this->resetPage();
    }

    public function updatedFiltroDestacado()
    {
        // Asegurar que el componente se actualice correctamente
        $this->resetPage();
    }

    public function updatedProductOrderJson()
    {
        // Compatibilidad con el nombre existente - delegar al trait
        $this->itemOrderJson = $this->productOrderJson;
        $this->updatedItemOrderJson();
    }

    private function resetPage()
    {
        // Si se usa paginación, resetear a la primera página
        // $this->resetPage(); // Descomentar si se implementa paginación
    }

    public function crearProducto()
    {
        // Limpiar todos los campos del formulario
        $this->reset([
            'productoId', 'nombre', 'descripcion', 'precio', 'precio_descuento',
            'stock', 'categoria_id', 'destacado', 'activo', 'imagen', 'imagen_file'
        ]);

        // Establecer valores por defecto
        $this->estado = 'disponible';
        $this->destacado = false;
        $this->activo = true;
        $this->modalTitle = 'Nuevo Producto';

        // Limpiar errores de validación
        $this->resetValidation();

        // Mostrar el modal
        $this->showEditModal = true;
    }

    public function editarProducto($id)
    {
        // Simulate loading time
        usleep(1000000); // 1 second

        $producto = Producto::findOrFail($id);
        $this->productoId = $id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->precio = $producto->precio;
        $this->precio_descuento = $producto->precio_descuento;
        $this->stock = $producto->stock;
        $this->estado = $producto->estado;
        $this->categoria_id = $producto->categoria_id;
        $this->destacado = $producto->destacado;
        $this->activo = $producto->activo;
        $this->imagen = $producto->imagen;
        $this->modalTitle = 'Editar Producto';
        $this->showEditModal = true;
    }

    public function saveProducto()
    {
        // Simulate loading time
        usleep(1500000); // 1.5 seconds

        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'precio_descuento' => 'nullable|numeric|min:0|lt:precio',
            'stock' => 'required|integer|min:0',
            'estado' => 'required|in:disponible,stock_bajo,agotado',
            'categoria_id' => 'required|exists:categorias,id',
            'destacado' => 'boolean',
            'activo' => 'boolean',
            'imagen' => 'nullable|string',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        $this->validate($rules);

        if ($this->productoId) {
            // Handle image upload if new file is provided
            $imagePath = $this->imagen; // Keep current image by default

            if ($this->imagen_file) {
                // Get the product to determine storage path
                $producto = Producto::with('categoria')->findOrFail($this->productoId);
                $categoryPath = $this->getCategoryStoragePath($producto->categoria->nombre);

                // Store the new image
                $imagePath = $this->imagen_file->store($categoryPath, 'public');

                // Delete old image if it was stored locally
                if ($producto->imagen && !filter_var($producto->imagen, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($producto->imagen);
                }
            }

            // Update existing product
            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'precio_descuento' => $this->precio_descuento ?: null,
                'stock' => $this->stock,
                'estado' => $this->calcularEstado($this->stock),
                'categoria_id' => $this->categoria_id,
                'destacado' => $this->destacado,
                'activo' => $this->activo,
                'imagen' => $imagePath ?: null,
            ];

            Producto::findOrFail($this->productoId)->update($data);
            session()->flash('message', 'Producto actualizado correctamente.');
        } else {
            // Create new product
            $imagePath = null;

            if ($this->imagen_file) {
                // Get category to determine storage path
                $categoria = Categoria::findOrFail($this->categoria_id);
                $categoryPath = $this->getCategoryStoragePath($categoria->nombre);

                // Store the new image
                $imagePath = $this->imagen_file->store($categoryPath, 'public');
            }

            // Get next sort order using trait method
            $nextSortOrder = $this->getNextSortOrder();

            // Create new product
            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'precio_descuento' => $this->precio_descuento ?: null,
                'stock' => $this->stock,
                'estado' => $this->calcularEstado($this->stock),
                'categoria_id' => $this->categoria_id,
                'destacado' => $this->destacado,
                'activo' => $this->activo,
                'imagen' => $imagePath,
                'sort_order' => $nextSortOrder,
            ];

            Producto::create($data);
            session()->flash('message', 'Producto creado correctamente.');
        }

        $this->closeEditModal();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['productoId', 'nombre', 'descripcion', 'precio', 'precio_descuento', 'stock', 'estado', 'categoria_id', 'destacado', 'activo', 'imagen', 'imagen_file', 'modalTitle']);
        $this->resetValidation();
    }

    private function getCategoryStoragePath($categoryName)
    {
        $categoryPaths = [
            'Hamburguesas' => 'productos/hamburguesas',
            'Pizzas' => 'productos/pizzas',
            'Bebidas' => 'productos/bebidas',
            'Postres' => 'productos/postres',
        ];

        return $categoryPaths[$categoryName] ?? 'productos/otros';
    }

    private function calcularEstado($stock)
    {
        if ($stock <= 0) {
            return 'agotado';
        } elseif ($stock <= 5) {
            return 'stock_bajo';
        } else {
            return 'disponible';
        }
    }

    public function verProducto($id)
    {
        // Simulate loading time
        usleep(800000); // 0.8 seconds

        $producto = Producto::with('categoria')->findOrFail($id);
        $this->productoToView = $producto;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->productoToView = null;
    }

    public function confirmDeleteProducto($id)
    {
        // Simulate loading time
        usleep(600000); // 0.6 seconds

        $producto = Producto::findOrFail($id);
        $this->productoToDelete = $producto;
        $this->showDeleteModal = true;
    }

    public function eliminarProducto()
    {
        // Simulate loading time
        usleep(1200000); // 1.2 seconds

        if ($this->productoToDelete) {
            try {
                $this->productoToDelete->delete();
                session()->flash('message', 'Producto eliminado correctamente');
                $this->closeDeleteModal();
            } catch (\Exception $e) {
                session()->flash('error', 'Error al eliminar el producto');
                $this->closeDeleteModal();
            }
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->productoToDelete = null;
    }

    public function toggleActivo($productoId)
    {
        try {
            $producto = Producto::findOrFail($productoId);
            $producto->activo = !$producto->activo;
            $producto->save();

            $estado = $producto->activo ? 'activado' : 'desactivado';
            session()->flash('message', "Producto {$estado} correctamente");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado del producto');
        }
    }

    // Método updateProductOrder ahora está en el trait HasSortableItems

    public function getProductos()
    {
        $query = Producto::with('categoria')
            // Administradores pueden ver todos los productos
            ->when($this->busqueda, function ($query) {
                $query->where('nombre', 'like', '%' . $this->busqueda . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->busqueda . '%');
            })
            ->when($this->filtroCategoria, function ($query) {
                $query->whereHas('categoria', function ($q) {
                    $q->where('nombre', $this->filtroCategoria);
                });
            })
            ->when($this->filtroEstado, function ($query) {
                if ($this->filtroEstado === 'Disponible') {
                    $query->where('estado', 'disponible');
                } elseif ($this->filtroEstado === 'Agotado') {
                    $query->where('estado', 'agotado');
                } elseif ($this->filtroEstado === 'Stock Bajo') {
                    $query->where('estado', 'stock_bajo');
                }
            })
            ->when($this->filtroDestacado !== '', function ($query) {
                $query->where('destacado', $this->filtroDestacado === '1');
            })
            ->ordered();

        return $query->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Producto::active()->count(),
            'disponibles' => Producto::active()->where('estado', 'disponible')->count(),
            'stock_bajo' => Producto::active()->where('estado', 'stock_bajo')->count(),
            'agotados' => Producto::active()->where('estado', 'agotado')->count(),
        ];
    }

    public function getCategoriasProperty()
    {
        return Categoria::active()->orderBy('nombre')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.admin-productos', [
            'productos' => $this->getProductos(),
            'stats' => $this->stats,
            'categorias' => $this->categorias,
        ]);
    }
}

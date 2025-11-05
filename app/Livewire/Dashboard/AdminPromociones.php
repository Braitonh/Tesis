<?php

namespace App\Livewire\Dashboard;

use App\Models\Promocion;
use App\Models\Producto;
use App\Traits\HasSortableItems;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('components.dashboard-layout')]
class AdminPromociones extends Component
{
    use WithFileUploads, HasSortableItems;

    // Filtros
    public $filtroDestacado = '';
    public $busqueda = '';

    // Delete confirmation modal
    public $showDeleteModal = false;
    public $promocionToDelete;

    // Detail modal
    public $showDetailModal = false;
    public $promocionToView;

    // Edit modal
    public $showEditModal = false;
    public $modalTitle = '';
    public $promocionId;

    // Form fields
    public $nombre = '';
    public $descripcion = '';
    public $precio_descuento_porcentaje = 10;
    public $destacado = false;
    public $activo = true;
    public $imagen = '';
    public $imagen_file;

    // Selected products for the promocion
    public $selectedProductos = [];
    public $productoCantidades = [];

    // Para el reordenamiento
    public $promocionOrderJson = '';

    // Propiedades para query string
    protected $updatesQueryString = [
        'busqueda' => ['except' => '', 'as' => 'q'],
        'filtroDestacado' => ['except' => '', 'as' => 'destacado']
    ];

    public function limpiarFiltros()
    {
        $this->filtroDestacado = '';
        $this->busqueda = '';
        $this->dispatch('$refresh');
    }

    public function updatedBusqueda()
    {
        $this->resetPage();
    }

    public function updatedFiltroDestacado()
    {
        $this->resetPage();
    }

    public function updatedPromocionOrderJson()
    {
        $this->itemOrderJson = $this->promocionOrderJson;
        $this->updatedItemOrderJson();
    }

    private function resetPage()
    {
        // Si se usa paginación, resetear a la primera página
    }

    public function crearPromocion()
    {
        // Limpiar todos los campos del formulario
        $this->reset([
            'promocionId', 'nombre', 'descripcion', 'precio_descuento_porcentaje',
            'destacado', 'activo', 'imagen', 'imagen_file', 'selectedProductos', 'productoCantidades'
        ]);

        // Establecer valores por defecto
        $this->precio_descuento_porcentaje = 10;
        $this->destacado = false;
        $this->activo = true;
        $this->modalTitle = 'Nueva Promoción';

        // Limpiar errores de validación
        $this->resetValidation();

        // Mostrar el modal
        $this->showEditModal = true;
    }

    public function editarPromocion($id)
    {
        usleep(1000000); // 1 second

        $promocion = Promocion::with('productos')->findOrFail($id);
        $this->promocionId = $id;
        $this->nombre = $promocion->nombre;
        $this->descripcion = $promocion->descripcion;
        $this->precio_descuento_porcentaje = $promocion->precio_descuento_porcentaje;
        $this->destacado = $promocion->destacado;
        $this->activo = $promocion->activo;
        $this->imagen = $promocion->imagen;

        // Load selected productos
        $this->selectedProductos = $promocion->productos->pluck('id')->toArray();
        $this->productoCantidades = [];
        foreach ($promocion->productos as $producto) {
            $this->productoCantidades[$producto->id] = $producto->pivot->cantidad;
        }

        $this->modalTitle = 'Editar Promoción';
        $this->showEditModal = true;
    }

    public function toggleProductoSelection($productoId)
    {
        if (in_array($productoId, $this->selectedProductos)) {
            // Remove from selection
            $this->selectedProductos = array_diff($this->selectedProductos, [$productoId]);
            unset($this->productoCantidades[$productoId]);
        } else {
            // Add to selection
            $this->selectedProductos[] = $productoId;
            $this->productoCantidades[$productoId] = 1; // Default quantity
        }
    }

    public function savePromocion()
    {
        usleep(1500000); // 1.5 seconds

        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio_descuento_porcentaje' => 'required|numeric|min:0|max:100',
            'destacado' => 'boolean',
            'activo' => 'boolean',
            'imagen' => 'nullable|string',
            'imagen_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'selectedProductos' => 'required|array|min:1',
            'selectedProductos.*' => 'exists:productos,id',
        ];

        $this->validate($rules);

        // Handle image upload
        $imagePath = $this->imagen;

        if ($this->imagen_file) {
            $imagePath = $this->imagen_file->store('promociones', 'public');

            // Delete old image if it was stored locally and we're updating
            if ($this->promocionId) {
                $promocion = Promocion::findOrFail($this->promocionId);
                if ($promocion->imagen && !filter_var($promocion->imagen, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($promocion->imagen);
                }
            }
        }

        if ($this->promocionId) {
            // Update existing promocion
            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio_descuento_porcentaje' => $this->precio_descuento_porcentaje,
                'destacado' => $this->destacado,
                'activo' => $this->activo,
                'imagen' => $imagePath ?: null,
            ];

            $promocion = Promocion::findOrFail($this->promocionId);
            $promocion->update($data);

            // Sync productos with cantidades
            $syncData = [];
            foreach ($this->selectedProductos as $productoId) {
                $syncData[$productoId] = ['cantidad' => $this->productoCantidades[$productoId] ?? 1];
            }
            $promocion->productos()->sync($syncData);

            session()->flash('message', 'Promoción actualizada correctamente.');
        } else {
            // Create new promocion
            $nextSortOrder = $this->getNextSortOrder('sort_order', Promocion::class);

            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio_descuento_porcentaje' => $this->precio_descuento_porcentaje,
                'destacado' => false,
                'activo' => true,
                'imagen' => $imagePath,
                'sort_order' => $nextSortOrder,
            ];

            $promocion = Promocion::create($data);

            // Attach productos with cantidades
            $attachData = [];
            foreach ($this->selectedProductos as $productoId) {
                $attachData[$productoId] = ['cantidad' => $this->productoCantidades[$productoId] ?? 1];
            }
            $promocion->productos()->attach($attachData);

            session()->flash('message', 'Promoción creada correctamente.');
        }

        $this->closeEditModal();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset([
            'promocionId', 'nombre', 'descripcion', 'precio_descuento_porcentaje',
            'destacado', 'activo', 'imagen', 'imagen_file', 'selectedProductos',
            'productoCantidades', 'modalTitle'
        ]);
        $this->resetValidation();
    }

    public function verPromocion($id)
    {
        usleep(800000); // 0.8 seconds

        $promocion = Promocion::with('productos.categoria')->findOrFail($id);
        $this->promocionToView = $promocion;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->promocionToView = null;
    }

    public function confirmDeletePromocion($id)
    {
        usleep(600000); // 0.6 seconds

        $promocion = Promocion::findOrFail($id);
        $this->promocionToDelete = $promocion;
        $this->showDeleteModal = true;
    }

    public function eliminarPromocion()
    {
        usleep(1200000); // 1.2 seconds

        if ($this->promocionToDelete) {
            try {
                // Delete image if stored locally
                if ($this->promocionToDelete->imagen && !filter_var($this->promocionToDelete->imagen, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($this->promocionToDelete->imagen);
                }

                $this->promocionToDelete->delete();
                session()->flash('message', 'Promoción eliminada correctamente');
                $this->closeDeleteModal();
            } catch (\Exception $e) {
                session()->flash('error', 'Error al eliminar la promoción');
                $this->closeDeleteModal();
            }
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->promocionToDelete = null;
    }

    public function toggleActivo($promocionId)
    {
        try {
            $promocion = Promocion::findOrFail($promocionId);
            $promocion->activo = !$promocion->activo;
            $promocion->save();

            $estado = $promocion->activo ? 'activada' : 'desactivada';
            session()->flash('message', "Promoción {$estado} correctamente");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado de la promoción');
        }
    }

    public function getPromociones()
    {
        $query = Promocion::with('productos')
            ->when($this->busqueda, function ($query) {
                $query->where('nombre', 'like', '%' . $this->busqueda . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->busqueda . '%');
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
            'total' => Promocion::count(),
            'activas' => Promocion::active()->count(),
            'destacadas' => Promocion::active()->destacado()->count(),
            'inactivas' => Promocion::where('activo', false)->count(),
        ];
    }

    public function getProductosDisponiblesProperty()
    {
        return Producto::active()->disponible()->with('categoria')->orderBy('nombre')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.admin-promociones', [
            'promociones' => $this->getPromociones(),
            'stats' => $this->stats,
            'productosDisponibles' => $this->productosDisponibles,
        ]);
    }
}

<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSortableItems
{
    /**
     * Propiedad para manejar el JSON de orden de items
     */
    public $itemOrderJson = '';

    /**
     * Listener para cuando cambia el JSON de orden
     */
    public function updatedItemOrderJson()
    {
        if (!empty($this->itemOrderJson)) {
            $itemIds = json_decode($this->itemOrderJson, true);
            if (is_array($itemIds)) {
                $this->reorderItems($itemIds);
            }
            $this->itemOrderJson = ''; // Reset after processing
        }
    }

    /**
     * Método público para reordenar items (para mostrar spinner)
     *
     * @param array $itemIds
     * @return void
     */
    public function reorderItems($itemIds)
    {
        // Simular tiempo de procesamiento para mostrar el spinner
        if (config('app.debug')) {
            usleep(800000); // 0.8 segundos solo en debug
        }

        $this->updateItemOrder($itemIds);
    }

    /**
     * Actualizar el orden de los items en la base de datos
     *
     * @param array $itemIds Array de IDs en el nuevo orden
     * @param string $orderField Campo de orden en la base de datos (default: 'sort_order')
     * @param string|null $modelClass Clase del modelo (si es null, se inferirá)
     * @return bool
     */
    public function updateItemOrder($itemIds, $orderField = 'sort_order', $modelClass = null)
    {
        try {
            // Inferir la clase del modelo si no se proporciona
            if (!$modelClass) {
                $modelClass = $this->getModelClass();
            }

            if (!$modelClass || !class_exists($modelClass)) {
                throw new \Exception("No se pudo determinar la clase del modelo");
            }

            foreach ($itemIds as $index => $itemId) {
                $modelClass::where('id', $itemId)
                    ->update([$orderField => $index + 1]);
            }

            $this->flashSuccessMessage('Orden actualizado correctamente');
            return true;

        } catch (\Exception $e) {
            $this->flashErrorMessage('Error al actualizar el orden: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Agregar sort_order a un nuevo item
     *
     * @param Model $item
     * @param string $orderField
     * @param string|null $modelClass
     * @return void
     */
    public function addSortOrderToNewItem($item, $orderField = 'sort_order', $modelClass = null)
    {
        if (!$modelClass) {
            $modelClass = get_class($item);
        }

        $nextSortOrder = $modelClass::max($orderField) + 1;
        $item->update([$orderField => $nextSortOrder]);
    }

    /**
     * Obtener la siguiente posición disponible
     *
     * @param string $orderField
     * @param string|null $modelClass
     * @return int
     */
    public function getNextSortOrder($orderField = 'sort_order', $modelClass = null)
    {
        if (!$modelClass) {
            $modelClass = $this->getModelClass();
        }

        return $modelClass::max($orderField) + 1;
    }

    /**
     * Reorganizar items después de eliminar uno
     *
     * @param int $deletedPosition
     * @param string $orderField
     * @param string|null $modelClass
     * @return void
     */
    public function reorderAfterDelete($deletedPosition, $orderField = 'sort_order', $modelClass = null)
    {
        if (!$modelClass) {
            $modelClass = $this->getModelClass();
        }

        // Actualizar posiciones de items que están después del eliminado
        $modelClass::where($orderField, '>', $deletedPosition)
            ->decrement($orderField);
    }

    /**
     * Inicializar sort_order para items existentes que no lo tengan
     *
     * @param string $orderField
     * @param string|null $modelClass
     * @param string $sortBy Campo para ordenar inicialmente (default: 'created_at')
     * @return int Número de items actualizados
     */
    public function initializeSortOrder($orderField = 'sort_order', $modelClass = null, $sortBy = 'created_at')
    {
        if (!$modelClass) {
            $modelClass = $this->getModelClass();
        }

        $items = $modelClass::whereNull($orderField)
            ->orWhere($orderField, 0)
            ->orderBy($sortBy, 'asc')
            ->get();

        foreach ($items as $index => $item) {
            $item->update([$orderField => $index + 1]);
        }

        return $items->count();
    }

    /**
     * Obtener items ordenados por sort_order
     *
     * @param string $orderField
     * @param string|null $modelClass
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getOrderedItems($orderField = 'sort_order', $modelClass = null, $direction = 'asc')
    {
        if (!$modelClass) {
            $modelClass = $this->getModelClass();
        }

        return $modelClass::orderBy($orderField, $direction)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Inferir la clase del modelo basado en el nombre del componente
     *
     * @return string|null
     */
    protected function getModelClass()
    {
        // Obtener el nombre de la clase del componente
        $componentClass = get_class($this);

        // Extraer el nombre base (ej: AdminProductos -> Producto)
        $baseName = class_basename($componentClass);

        // Remover prefijos comunes
        $modelName = preg_replace('/^(Admin|Manage|List|Index)/', '', $baseName);

        // Convertir plural a singular (simple)
        if (str_ends_with($modelName, 's')) {
            $modelName = rtrim($modelName, 's');
        }

        // Construir el nombre completo de la clase del modelo
        $modelClass = "App\\Models\\{$modelName}";

        return class_exists($modelClass) ? $modelClass : null;
    }

    /**
     * Mostrar mensaje de éxito
     *
     * @param string $message
     * @return void
     */
    protected function flashSuccessMessage($message)
    {
        session()->flash('message', $message);
    }

    /**
     * Mostrar mensaje de error
     *
     * @param string $message
     * @return void
     */
    protected function flashErrorMessage($message)
    {
        session()->flash('error', $message);
    }

    /**
     * Verificar si un modelo tiene el campo sort_order
     *
     * @param string|null $modelClass
     * @param string $orderField
     * @return bool
     */
    public function modelHasSortOrder($modelClass = null, $orderField = 'sort_order')
    {
        if (!$modelClass) {
            $modelClass = $this->getModelClass();
        }

        if (!$modelClass || !class_exists($modelClass)) {
            return false;
        }

        try {
            $instance = new $modelClass;
            return in_array($orderField, $instance->getFillable()) ||
                   $instance->getConnection()
                           ->getSchemaBuilder()
                           ->hasColumn($instance->getTable(), $orderField);
        } catch (\Exception $e) {
            return false;
        }
    }
}
/**
 * SortableHelper - Helper reutilizable para funcionalidad de drag and drop
 * Utiliza SortableJS para crear grids arrastrables con integración Livewire
 */
class SortableHelper {
    constructor() {
        this.instances = new Map();
    }

    /**
     * Inicializar drag and drop en un grid
     * @param {string} gridId - ID del contenedor grid
     * @param {Object} options - Opciones de configuración
     */
    initSortable(gridId, options = {}) {
        const defaults = {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            filter: 'button, a, input, select, textarea',
            preventOnFilter: false,
            spinnerId: null,
            onReorder: null,
            livewireComponent: null,
            idAttribute: 'data-product-id',
            idSelectors: ['data-product-id', 'wire:key']
        };

        const config = { ...defaults, ...options };
        const grid = document.getElementById(gridId);

        if (!grid || typeof Sortable === 'undefined') {
            console.warn(`SortableHelper: Grid '${gridId}' not found or Sortable not loaded`);
            return null;
        }

        // Destruir instancia anterior si existe
        if (this.instances.has(gridId)) {
            this.instances.get(gridId).destroy();
        }

        const sortableInstance = Sortable.create(grid, {
            animation: config.animation,
            ghostClass: config.ghostClass,
            chosenClass: config.chosenClass,
            dragClass: config.dragClass,
            filter: config.filter,
            preventOnFilter: config.preventOnFilter,
            onEnd: (evt) => {
                const itemIds = this.extractItemIds(grid, config.idSelectors);

                if (itemIds.length > 0) {
                    // Mostrar spinner si está configurado
                    if (config.spinnerId) {
                        this.showSpinner(config.spinnerId);
                    }

                    // Llamar callback de reordenamiento
                    if (config.onReorder) {
                        this.handleReorder(itemIds, config);
                    }
                }
            }
        });

        this.instances.set(gridId, sortableInstance);
        return sortableInstance;
    }

    /**
     * Extraer IDs de los elementos del grid
     * @param {HTMLElement} container - Contenedor del grid
     * @param {Array} selectors - Array de selectores para buscar IDs
     * @returns {Array} Array de IDs extraídos
     */
    extractItemIds(container, selectors = ['data-product-id']) {
        return Array.from(container.children).map(item => {
            // Método 1: Buscar directamente en el elemento
            for (const selector of selectors) {
                if (selector.startsWith('data-')) {
                    const dataKey = selector.replace('data-', '').replace(/-([a-z])/g, (g) => g[1].toUpperCase());
                    const id = item.dataset[dataKey];
                    if (id) return id;
                } else if (selector.startsWith('wire:')) {
                    const wireKey = item.getAttribute(selector);
                    if (wireKey && wireKey.includes('-')) {
                        const id = wireKey.split('-').pop();
                        if (id) return id;
                    }
                }
            }

            // Método 2: Buscar en elementos hijos
            for (const selector of selectors) {
                const childElement = item.querySelector(`[${selector}]`);
                if (childElement) {
                    if (selector.startsWith('data-')) {
                        const dataKey = selector.replace('data-', '').replace(/-([a-z])/g, (g) => g[1].toUpperCase());
                        const id = childElement.dataset[dataKey];
                        if (id) return id;
                    }
                }
            }

            return null;
        }).filter(id => id !== null && id !== undefined);
    }

    /**
     * Manejar el reordenamiento
     * @param {Array} itemIds - Array de IDs en el nuevo orden
     * @param {Object} config - Configuración
     */
    handleReorder(itemIds, config) {
        if (config.livewireComponent && config.onReorder) {
            // Buscar componente Livewire específico
            const component = window.Livewire?.find?.(config.livewireComponent);
            if (component) {
                component.call(config.onReorder, itemIds);
                return;
            }
        }

        // Método alternativo: usar input hidden
        if (config.onReorder) {
            const hiddenInput = document.getElementById('product-order-input');
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(itemIds);
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }

    /**
     * Mostrar spinner
     * @param {string} spinnerId - ID del spinner
     */
    showSpinner(spinnerId) {
        const spinner = document.getElementById(spinnerId);
        if (spinner) {
            spinner.style.display = 'flex';
        }
    }

    /**
     * Ocultar spinner
     * @param {string} spinnerId - ID del spinner
     */
    hideSpinner(spinnerId) {
        const spinner = document.getElementById(spinnerId);
        if (spinner) {
            spinner.style.display = 'none';
        }
    }

    /**
     * Configurar listeners de Livewire para reinicializar y ocultar spinners
     * @param {Object} options - Opciones de configuración
     */
    setupLivewireListeners(options = {}) {
        const {
            onUpdated = null,
            onNavigated = null,
            spinnerId = null,
            reinitCallbacks = []
        } = options;

        // Listener para actualizaciones de Livewire
        document.addEventListener('livewire:updated', () => {
            if (spinnerId) {
                setTimeout(() => this.hideSpinner(spinnerId), 200);
            }

            // Reinicializar instancias
            reinitCallbacks.forEach(callback => {
                if (typeof callback === 'function') {
                    setTimeout(callback, 100);
                }
            });

            if (onUpdated) onUpdated();
        });

        // Listener para navegación de Livewire
        document.addEventListener('livewire:navigated', () => {
            if (spinnerId) {
                this.hideSpinner(spinnerId);
            }

            // Reinicializar instancias
            reinitCallbacks.forEach(callback => {
                if (typeof callback === 'function') {
                    setTimeout(callback, 100);
                }
            });

            if (onNavigated) onNavigated();
        });
    }

    /**
     * Destruir instancia específica
     * @param {string} gridId - ID del grid
     */
    destroy(gridId) {
        if (this.instances.has(gridId)) {
            this.instances.get(gridId).destroy();
            this.instances.delete(gridId);
        }
    }

    /**
     * Destruir todas las instancias
     */
    destroyAll() {
        this.instances.forEach(instance => instance.destroy());
        this.instances.clear();
    }
}

// Crear instancia global
window.SortableHelper = new SortableHelper();

// Funciones de conveniencia para compatibilidad hacia atrás
window.initSortable = (gridId, options) => window.SortableHelper.initSortable(gridId, options);
window.showSpinner = (spinnerId) => window.SortableHelper.showSpinner(spinnerId);
window.hideSpinner = (spinnerId) => window.SortableHelper.hideSpinner(spinnerId);
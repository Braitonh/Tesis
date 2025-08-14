@props(['show' => false, 'item' => null, 'title' => 'Confirmar Eliminación', 'message' => '¿Estás seguro de que quieres eliminar este elemento?', 'onCancel' => 'closeDeleteModal', 'onConfirm' => 'deleteItem'])

@if($show && $item)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Container -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 relative" style="animation: slideUp 0.3s ease forwards;">
            
            <!-- Modal Content -->
            <div class="p-6">
                <!-- Icon -->
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">
                    {{ $title }}
                </h3>

                <!-- Message -->
                <div class="text-gray-600 text-center mb-6">
                    {!! $message !!}
                    <br><span class="text-sm text-gray-500">Esta acción no se puede deshacer.</span>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-3">
                    <button wire:click="{{ $onCancel }}" 
                            wire:loading.attr="disabled" 
                            wire:target="{{ $onCancel }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="{{ $onCancel }}">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </span>
                        <span wire:loading wire:target="{{ $onCancel }}" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Cerrando...
                        </span>
                    </button>
                    <button wire:click="{{ $onConfirm }}" 
                            wire:loading.attr="disabled" 
                            wire:target="{{ $onConfirm }}"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="{{ $onConfirm }}">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar
                        </span>
                        <span wire:loading wire:target="{{ $onConfirm }}" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Eliminando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
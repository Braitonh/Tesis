@props(['pedido'])

<tr>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">{{ $pedido->numero_pedido }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $pedido->user->name }}</div>
        <div class="text-sm text-gray-500">{{ $pedido->user->email }}</div>
    </td>
    <td class="px-6 py-4">
        @foreach($pedido->detalles->take(2) as $detalle)
            <div class="text-sm text-gray-900">
                @if($detalle->promocion_id)
                    <i class="fas fa-gift text-orange-500 mr-1"></i>
                    {{ $detalle->cantidad }}x {{ $detalle->promocion->nombre }}
                @else
                    {{ $detalle->cantidad }}x {{ $detalle->producto->nombre }}
                @endif
            </div>
        @endforeach
        @if($pedido->detalles->count() > 2)
            <div class="text-sm text-gray-500">+{{ $pedido->detalles->count() - 2 }} más</div>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-semibold text-gray-900">$. {{ number_format($pedido->total, 0, ',', '.') }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
            @if($pedido->estado === 'pendiente') bg-yellow-100 text-yellow-800
            @elseif($pedido->estado === 'en_preparacion') bg-blue-100 text-blue-800
            @elseif($pedido->estado === 'listo') bg-purple-100 text-purple-800
            @elseif($pedido->estado === 'en_camino') bg-indigo-100 text-indigo-800
            @elseif($pedido->estado === 'entregado') bg-green-100 text-green-800
            @elseif($pedido->estado === 'cancelado') bg-red-100 text-red-800
            @endif">
            {{ $pedido->estado_texto }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
            @if($pedido->estado_pago === 'pagado') bg-emerald-100 text-emerald-800
            @elseif($pedido->estado_pago === 'pendiente') bg-amber-100 text-amber-800
            @elseif($pedido->estado_pago === 'fallido') bg-red-100 text-red-800
            @endif">
            {{ ucfirst($pedido->estado_pago) }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $pedido->created_at->format('d/m/Y H:i') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex items-center justify-end space-x-2">
            <button wire:click="verDetalles({{ $pedido->id }})" 
                    class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                    title="Ver detalles">
                <span wire:loading.remove wire:target="verDetalles({{ $pedido->id }})">
                    <i class="fas fa-eye"></i>
                </span>
                <span wire:loading wire:target="verDetalles({{ $pedido->id }})">
                    <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
            
            <button wire:click="abrirModalEstado({{ $pedido->id }})" 
                    class="text-orange-600 hover:text-orange-900 p-2 rounded-lg hover:bg-orange-50 transition-colors" 
                    title="Editar pedido">
                <span wire:loading.remove wire:target="abrirModalEstado({{ $pedido->id }})">
                    <i class="fas fa-edit"></i>
                </span>
                <span wire:loading wire:target="abrirModalEstado({{ $pedido->id }})">
                    <svg class="animate-spin h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
            
            @if($pedido->estado !== 'cancelado' && $pedido->estado !== 'entregado')
                <button wire:click="confirmarCancelar({{ $pedido->id }})" 
                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" 
                        title="Cancelar pedido">
                    <span wire:loading.remove wire:target="confirmarCancelar({{ $pedido->id }})">
                        <i class="fas fa-trash"></i>
                    </span>
                    <span wire:loading wire:target="confirmarCancelar({{ $pedido->id }})">
                        <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            @endif
        </div>
    </td>
</tr>
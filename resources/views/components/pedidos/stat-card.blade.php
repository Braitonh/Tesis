@props(['icon', 'label', 'value', 'bgColor' => 'bg-gray-100', 'iconColor' => 'text-gray-600'])

<div class="bg-white rounded-lg p-6 shadow-sm border border-orange-100">
    <div class="flex items-center">
        <div class="{{ $bgColor }} p-3 rounded-lg">
            <i class="fas fa-{{ $icon }} {{ $iconColor }}"></i>
        </div>
        <div class="ml-4">
            <p class="text-gray-500 text-sm">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
        </div>
    </div>
</div>
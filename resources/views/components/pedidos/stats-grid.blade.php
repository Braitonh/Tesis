@props(['stats'])

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
    <x-pedidos.stat-card
        icon="list"
        label="Total"
        :value="$stats['total']"
        bgColor="bg-gray-100"
        iconColor="text-gray-600" />

    <x-pedidos.stat-card
        icon="clock"
        label="Pendientes"
        :value="$stats['pendientes']"
        bgColor="bg-yellow-100"
        iconColor="text-yellow-600" />

    <x-pedidos.stat-card
        icon="spinner"
        label="En Proceso"
        :value="$stats['en_proceso']"
        bgColor="bg-blue-100"
        iconColor="text-blue-600" />

    <x-pedidos.stat-card
        icon="check-circle"
        label="Entregados"
        :value="$stats['entregados']"
        bgColor="bg-green-100"
        iconColor="text-green-600" />

    <x-pedidos.stat-card
        icon="money-check-alt"
        label="Pagados"
        :value="$stats['pagados']"
        bgColor="bg-emerald-100"
        iconColor="text-emerald-600" />
</div>
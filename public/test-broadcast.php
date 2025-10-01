<?php
// Script de prueba para disparar evento de broadcasting

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Test de Broadcasting\n\n";

// Obtener el último pedido
$pedido = App\Models\Pedido::latest()->first();

if (!$pedido) {
    echo "❌ No hay pedidos en la base de datos\n";
    exit(1);
}

echo "📦 Pedido encontrado: {$pedido->numero_pedido}\n";
echo "📡 Configuración de Broadcasting:\n";
echo "   - Driver: " . config('broadcasting.default') . "\n";
echo "   - Host: " . config('broadcasting.connections.reverb.options.host') . "\n";
echo "   - Port: " . config('broadcasting.connections.reverb.options.port') . "\n\n";

echo "🔥 Disparando evento PedidoCreado...\n";

try {
    $event = new App\Events\PedidoCreado($pedido);
    event($event);
    echo "✅ Evento disparado correctamente\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n📝 Revisa los logs de Reverb con:\n";
echo "   docker-compose logs --tail=20 reverb\n";



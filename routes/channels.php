<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Canal privado para usuarios específicos
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal privado para pedidos específicos (solo el dueño puede escuchar)
Broadcast::channel('pedidos.{pedidoId}', function ($user, $pedidoId) {
    $pedido = \App\Models\Pedido::find($pedidoId);
    return $pedido && (int) $user->id === (int) $pedido->user_id;
});

// Canal público para cocina (todos los empleados de cocina pueden escuchar)
Broadcast::channel('cocina', function ($user) {
    // Aquí puedes agregar lógica para verificar si el usuario tiene rol de cocina
    // Por ahora permitimos a todos los usuarios autenticados
    return auth()->check();
});

// Canal público para administración (solo admins pueden escuchar)
Broadcast::channel('admin', function ($user) {
    // Verificar si el usuario es admin
    // Por ahora permitimos a todos los usuarios autenticados
    return auth()->check();
});

// Canal público para delivery (solo delivery pueden escuchar)
Broadcast::channel('delivery', function ($user) {
    // Verificar si el usuario tiene rol de delivery
    // Por ahora permitimos a todos los usuarios autenticados
    return auth()->check();
});

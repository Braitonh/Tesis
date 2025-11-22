<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{
    public function generarPDF(Pedido $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        // Cargar todas las relaciones necesarias
        $pedido->load(['detalles.producto', 'detalles.promocion', 'user', 'transaccion']);

        // Generar el PDF con configuración optimizada
        $pdf = Pdf::loadView('facturas.pedido', [
            'pedido' => $pedido,
        ])->setPaper('a4', 'portrait')
          ->setOption('margin-top', 10)
          ->setOption('margin-bottom', 10)
          ->setOption('margin-left', 10)
          ->setOption('margin-right', 10)
          ->setOption('enable-local-file-access', true);

        // Retornar el PDF para visualización en el navegador
        return $pdf->stream('factura-' . $pedido->numero_pedido . '.pdf');
    }
}


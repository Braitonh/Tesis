<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    /**
     * Procesar webhook de Mercado Pago
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        try {
            $data = $request->all();

            Log::info('Webhook recibido de Mercado Pago', [
                'data' => $data,
            ]);

            $mercadoPagoService = new MercadoPagoService();
            $resultado = $mercadoPagoService->procesarWebhook($data);

            if ($resultado['success']) {
                return response()->json([
                    'status' => 'ok',
                    'message' => $resultado['message'],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $resultado['message'],
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar webhook de Mercado Pago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar webhook',
            ], 500);
        }
    }
}


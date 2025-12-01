<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Transaccion;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoCallbackController extends Controller
{
    /**
     * Manejar callback de Mercado Pago
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        // Mercado Pago puede enviar preference_id como query parameter o en el body
        $status = $request->query('status') ?? $request->input('status');
        $preferenceId = $request->query('preference_id') ?? $request->input('preference_id');
        $paymentId = $request->query('payment_id') ?? $request->input('payment_id');

        // Si no tenemos preference_id, intentar obtenerlo de collection_id (alternativa de MP)
        if (!$preferenceId) {
            $collectionId = $request->query('collection_id') ?? $request->input('collection_id');
            if ($collectionId) {
                // collection_id puede ser el payment_id, pero necesitamos el preference_id
                // Buscar por payment_id si no tenemos preference_id
            }
        }

        Log::info('Callback recibido de Mercado Pago', [
            'status' => $status,
            'preference_id' => $preferenceId,
            'payment_id' => $paymentId,
            'all_params' => $request->all(),
        ]);

        if (!$preferenceId) {
            // Si no tenemos preference_id pero tenemos payment_id, buscar la transacción por payment_id
            if ($paymentId) {
                $transaccion = Transaccion::where('mercado_pago_payment_id', $paymentId)
                    ->where('metodo_pago', 'mercado_pago')
                    ->first();
                
                if ($transaccion) {
                    $preferenceId = $transaccion->mercado_pago_preference_id;
                }
            }
            
            if (!$preferenceId) {
                return redirect()->route('cliente.bienvenida')
                    ->with('error', 'Parámetros de callback inválidos');
            }
        }

        try {
            // Buscar transacción por preference_id
            $transaccion = Transaccion::where('mercado_pago_preference_id', $preferenceId)
                ->where('metodo_pago', 'mercado_pago')
                ->first();

            if (!$transaccion) {
                Log::warning('Transacción no encontrada para callback de Mercado Pago', [
                    'preference_id' => $preferenceId,
                ]);

                return redirect()->route('cliente.bienvenida')
                    ->with('error', 'No se encontró la transacción de pago');
            }

            $pedido = $transaccion->pedido;
            if (!$pedido) {
                return redirect()->route('cliente.bienvenida')
                    ->with('error', 'No se encontró el pedido asociado');
            }

            // Si el pago fue aprobado, verificar estado actual
            if ($status === 'success' || $status === 'approved') {
                // Consultar estado actual del pago en Mercado Pago
                $mercadoPagoService = new MercadoPagoService();
                
                // Si ya tenemos payment_id, consultar estado
                if ($transaccion->mercado_pago_payment_id) {
                    $paymentInfo = $mercadoPagoService->obtenerPago($transaccion->mercado_pago_payment_id);
                    
                    if ($paymentInfo && $paymentInfo['status'] === 'approved') {
                        // El webhook debería haber actualizado esto, pero verificamos por si acaso
                        if ($transaccion->estado !== 'aprobado') {
                            $transaccion->update([
                                'estado' => 'aprobado',
                                'mensaje_respuesta' => 'Pago aprobado exitosamente',
                                'fecha_procesamiento' => now(),
                            ]);
                            $pedido->update(['estado_pago' => 'pagado']);
                        }
                    }
                } elseif ($paymentId) {
                    // Si tenemos payment_id del callback, consultarlo
                    $paymentInfo = $mercadoPagoService->obtenerPago($paymentId);
                    
                    if ($paymentInfo && $paymentInfo['status'] === 'approved') {
                        $transaccion->update([
                            'mercado_pago_payment_id' => $paymentId,
                            'mercado_pago_status' => $paymentInfo['status'],
                            'estado' => 'aprobado',
                            'mensaje_respuesta' => 'Pago aprobado exitosamente',
                            'fecha_procesamiento' => now(),
                        ]);
                        $pedido->update(['estado_pago' => 'pagado']);
                    }
                }

                return redirect()->route('cliente.pedido.confirmacion', $pedido->id)
                    ->with('success', '¡Pago realizado con éxito!');
            } elseif ($status === 'failure' || $status === 'rejected') {
                // Pago rechazado
                if ($transaccion->estado !== 'rechazado') {
                    $transaccion->update([
                        'estado' => 'rechazado',
                        'mensaje_respuesta' => 'Pago rechazado por Mercado Pago',
                        'fecha_procesamiento' => now(),
                    ]);
                    $pedido->update(['estado_pago' => 'fallido']);
                }

                return redirect()->route('cliente.carrito.checkout')
                    ->with('error', 'El pago fue rechazado. Por favor, intenta con otro método de pago.');
            } else {
                // Pago pendiente
                return redirect()->route('cliente.pago.procesando', $transaccion->id);
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar callback de Mercado Pago', [
                'preference_id' => $preferenceId,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('cliente.bienvenida')
                ->with('error', 'Error al procesar el resultado del pago');
        }
    }
}


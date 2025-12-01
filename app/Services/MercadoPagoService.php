<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Transaccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;
use Exception;

class MercadoPagoService
{
    private PreferenceClient $preferenceClient;
    private PaymentClient $paymentClient;
    private string $accessToken;
    private bool $isTestMode;

    public function __construct()
    {
        $this->isTestMode = config('services.mercadopago.mode') === 'test';
        $this->accessToken = $this->isTestMode
            ? config('services.mercadopago.access_token_test')
            : config('services.mercadopago.access_token_production');

        if (empty($this->accessToken)) {
            throw new Exception('Mercado Pago access token no configurado');
        }

        MercadoPagoConfig::setAccessToken($this->accessToken);
        $this->preferenceClient = new PreferenceClient();
        $this->paymentClient = new PaymentClient();
    }

    /**
     * Crear una preferencia de pago en Mercado Pago
     *
     * @param Pedido $pedido
     * @param Transaccion $transaccion
     * @return array ['success' => bool, 'preference_id' => string|null, 'init_point' => string|null, 'error' => string|null]
     */
    public function crearPreferencia(Pedido $pedido, Transaccion $transaccion): array
    {
        try {
            $user = $pedido->user;
            
            if (!$user) {
                return [
                    'success' => false,
                    'error' => 'Usuario no encontrado para el pedido',
                ];
            }

            $items = [];

            // Agregar productos al array de items
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->producto) {
                    $items[] = [
                        'title' => $detalle->producto->nombre,
                        'quantity' => (int) $detalle->cantidad,
                        'unit_price' => (float) $detalle->precio_unitario,
                    ];
                } elseif ($detalle->promocion) {
                    $items[] = [
                        'title' => $detalle->promocion->nombre,
                        'quantity' => (int) $detalle->cantidad,
                        'unit_price' => (float) $detalle->precio_unitario,
                    ];
                }
            }

            // Validar que haya items
            if (empty($items)) {
                return [
                    'success' => false,
                    'error' => 'El pedido no tiene items para procesar',
                ];
            }

            // URLs de callback
            $baseUrl = rtrim(config('app.url'), '/');
            if (empty($baseUrl)) {
                return [
                    'success' => false,
                    'error' => 'La URL de la aplicación no está configurada. Verifica APP_URL en .env',
                ];
            }
            
            // Validar que la URL sea válida (debe empezar con http:// o https://)
            if (!preg_match('/^https?:\/\//', $baseUrl)) {
                return [
                    'success' => false,
                    'error' => 'La URL de la aplicación debe empezar con http:// o https://. Verifica APP_URL en .env',
                ];
            }
            
            $callbackBase = $baseUrl . '/cliente/pago/mercado-pago/callback';
            // URLs simples sin placeholders - Mercado Pago agregará preference_id automáticamente
            $successUrl = $callbackBase . '?status=success';
            $failureUrl = $callbackBase . '?status=failure';
            $pendingUrl = $callbackBase . '?status=pending';
            
            // Validar que las URLs sean válidas
            if (!filter_var($successUrl, FILTER_VALIDATE_URL)) {
                return [
                    'success' => false,
                    'error' => 'La URL de callback no es válida. Verifica APP_URL en .env',
                ];
            }

            // Preparar datos del pagador
            $payerData = [
                'name' => $user->name ?? 'Cliente',
                'email' => $user->email,
            ];

            // Agregar teléfono solo si está disponible
            $telefono = $pedido->telefono_contacto ?? $user->telefono ?? null;
            if ($telefono) {
                // Limpiar teléfono (solo números)
                $telefonoLimpio = preg_replace('/\D/', '', $telefono);
                if (!empty($telefonoLimpio)) {
                    $payerData['phone'] = [
                        'number' => $telefonoLimpio,
                    ];
                }
            }

            // Crear preferencia
            $preferenceData = [
                'items' => $items,
                'payer' => $payerData,
                'back_urls' => [
                    'success' => $successUrl,
                    'failure' => $failureUrl,
                    'pending' => $pendingUrl,
                ],
                // Omitimos auto_return para evitar problemas de validación
                // El usuario podrá hacer clic en el botón de retorno después del pago
                // 'auto_return' => 'approved', // Comentado para evitar errores de validación
                'external_reference' => (string) $pedido->id,
                'notification_url' => $baseUrl . '/api/mercado-pago/webhook',
                'statement_descriptor' => 'COMBATE MBORORE',
            ];

            // Agregar metadata solo si no está vacío
            $metadata = [
                'pedido_id' => $pedido->id,
                'transaccion_id' => $transaccion->id,
                'numero_pedido' => $pedido->numero_pedido,
            ];
            if (!empty($metadata)) {
                $preferenceData['metadata'] = $metadata;
            }

            // Log de datos antes de enviar (sin información sensible)
            Log::info('Creando preferencia de Mercado Pago', [
                'pedido_id' => $pedido->id,
                'items_count' => count($items),
                'total' => $pedido->total,
                'base_url' => $baseUrl,
            ]);

            $preference = $this->preferenceClient->create($preferenceData);

            if ($preference && isset($preference->id)) {
                // Actualizar transacción con preference_id
                $transaccion->update([
                    'mercado_pago_preference_id' => $preference->id,
                    'estado' => 'procesando',
                ]);

                return [
                    'success' => true,
                    'preference_id' => $preference->id,
                    'init_point' => $preference->init_point ?? null,
                    'sandbox_init_point' => $preference->sandbox_init_point ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => 'No se pudo crear la preferencia de pago',
            ];

        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            $statusCode = $apiResponse ? $apiResponse->getStatusCode() : null;
            $content = $apiResponse ? $apiResponse->getContent() : null;
            
            // Intentar extraer mensaje más específico del contenido
            $errorMessage = $e->getMessage();
            $detailedError = null;
            
            if ($content) {
                try {
                    $contentArray = is_string($content) ? json_decode($content, true) : $content;
                    if (is_array($contentArray)) {
                        // Buscar mensajes de error comunes en la respuesta
                        if (isset($contentArray['message'])) {
                            $detailedError = $contentArray['message'];
                        } elseif (isset($contentArray['error'])) {
                            $detailedError = is_string($contentArray['error']) 
                                ? $contentArray['error'] 
                                : ($contentArray['error']['message'] ?? null);
                        } elseif (isset($contentArray['cause'])) {
                            $causes = $contentArray['cause'];
                            if (is_array($causes) && !empty($causes)) {
                                $firstCause = $causes[0];
                                $detailedError = $firstCause['description'] ?? $firstCause['code'] ?? null;
                            }
                        }
                    }
                } catch (\Exception $parseException) {
                    // Si no se puede parsear, usar el contenido como string
                    $detailedError = is_string($content) ? $content : json_encode($content);
                }
            }

            Log::error('Error al crear preferencia de Mercado Pago', [
                'error' => $errorMessage,
                'detailed_error' => $detailedError,
                'status_code' => $statusCode,
                'content' => $content,
                'pedido_id' => $pedido->id,
            ]);

            // Usar el mensaje detallado si está disponible, sino el genérico
            $userMessage = $detailedError 
                ? 'Error al crear la preferencia de pago: ' . $detailedError
                : 'Error al crear la preferencia de pago. Verifica tu configuración de Mercado Pago.';

            return [
                'success' => false,
                'error' => $userMessage,
            ];

        } catch (Exception $e) {
            Log::error('Error inesperado al crear preferencia de Mercado Pago', [
                'error' => $e->getMessage(),
                'pedido_id' => $pedido->id,
            ]);

            return [
                'success' => false,
                'error' => 'Error inesperado: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Obtener información de un pago de Mercado Pago
     *
     * @param string $paymentId
     * @return array|null
     */
    public function obtenerPago(string $paymentId): ?array
    {
        try {
            $payment = $this->paymentClient->get($paymentId);

            if ($payment) {
                return [
                    'id' => $payment->id,
                    'status' => $payment->status,
                    'status_detail' => $payment->status_detail,
                    'transaction_amount' => $payment->transaction_amount,
                    'currency_id' => $payment->currency_id,
                    'payment_method_id' => $payment->payment_method_id,
                    'payment_type_id' => $payment->payment_type_id,
                    'date_approved' => $payment->date_approved,
                    'date_created' => $payment->date_created,
                    'external_reference' => $payment->external_reference,
                ];
            }

            return null;

        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            Log::error('Error al obtener pago de Mercado Pago', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'status' => $apiResponse ? $apiResponse->getStatusCode() : null,
            ]);

            return null;

        } catch (Exception $e) {
            Log::error('Error inesperado al obtener pago de Mercado Pago', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Procesar webhook de Mercado Pago
     *
     * @param array $data
     * @return array ['success' => bool, 'message' => string]
     */
    public function procesarWebhook(array $data): array
    {
        try {
            $type = $data['type'] ?? null;
            $dataId = $data['data']['id'] ?? null;

            if ($type !== 'payment' || !$dataId) {
                return [
                    'success' => false,
                    'message' => 'Tipo de notificación no válido',
                ];
            }

            // Obtener información del pago
            $paymentInfo = $this->obtenerPago($dataId);

            if (!$paymentInfo) {
                return [
                    'success' => false,
                    'message' => 'No se pudo obtener información del pago',
                ];
            }

            // Buscar pedido por external_reference
            $pedidoId = $paymentInfo['external_reference'] ?? null;
            if (!$pedidoId) {
                return [
                    'success' => false,
                    'message' => 'No se encontró referencia del pedido',
                ];
            }

            $pedido = Pedido::find($pedidoId);
            if (!$pedido) {
                return [
                    'success' => false,
                    'message' => 'Pedido no encontrado',
                ];
            }

            // Buscar transacción asociada
            $transaccion = Transaccion::where('pedido_id', $pedido->id)
                ->where('metodo_pago', 'mercado_pago')
                ->first();

            if (!$transaccion) {
                return [
                    'success' => false,
                    'message' => 'Transacción no encontrada',
                ];
            }

            // Verificar si ya fue procesado (idempotencia)
            if ($transaccion->mercado_pago_payment_id === $dataId && $transaccion->estado !== 'pendiente') {
                return [
                    'success' => true,
                    'message' => 'Webhook ya procesado anteriormente',
                ];
            }

            DB::beginTransaction();

            // Actualizar transacción
            $estado = $this->mapearEstadoMercadoPago($paymentInfo['status']);
            $transaccion->update([
                'mercado_pago_payment_id' => $dataId,
                'mercado_pago_status' => $paymentInfo['status'],
                'estado' => $estado,
                'mensaje_respuesta' => $this->obtenerMensajeEstado($paymentInfo['status'], $paymentInfo['status_detail'] ?? ''),
                'fecha_procesamiento' => now(),
            ]);

            // Actualizar pedido
            if ($estado === 'aprobado') {
                $pedido->update(['estado_pago' => 'pagado']);
            } elseif ($estado === 'rechazado') {
                $pedido->update(['estado_pago' => 'fallido']);
            }

            DB::commit();

            Log::info('Webhook de Mercado Pago procesado exitosamente', [
                'payment_id' => $dataId,
                'pedido_id' => $pedido->id,
                'estado' => $estado,
            ]);

            return [
                'success' => true,
                'message' => 'Webhook procesado correctamente',
            ];

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error al procesar webhook de Mercado Pago', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'message' => 'Error al procesar webhook: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mapear estado de Mercado Pago a estado interno
     *
     * @param string $mpStatus
     * @return string
     */
    private function mapearEstadoMercadoPago(string $mpStatus): string
    {
        return match($mpStatus) {
            'approved' => 'aprobado',
            'rejected', 'cancelled', 'refunded', 'charged_back' => 'rechazado',
            'pending', 'in_process', 'in_mediation' => 'procesando',
            default => 'pendiente',
        };
    }

    /**
     * Obtener mensaje descriptivo del estado
     *
     * @param string $status
     * @param string $statusDetail
     * @return string
     */
    private function obtenerMensajeEstado(string $status, string $statusDetail): string
    {
        $mensajes = [
            'approved' => 'Pago aprobado exitosamente',
            'rejected' => 'Pago rechazado',
            'cancelled' => 'Pago cancelado',
            'refunded' => 'Pago reembolsado',
            'charged_back' => 'Pago revertido',
            'pending' => 'Pago pendiente de confirmación',
            'in_process' => 'Pago en proceso',
            'in_mediation' => 'Pago en mediación',
        ];

        $mensaje = $mensajes[$status] ?? 'Estado desconocido';

        if ($statusDetail) {
            $mensaje .= ' - ' . $statusDetail;
        }

        return $mensaje;
    }

    /**
     * Verificar si está en modo test
     *
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->isTestMode;
    }
}


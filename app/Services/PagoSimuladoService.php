<?php

namespace App\Services;

use App\Models\Transaccion;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Exception;

class PagoSimuladoService
{
    /**
     * Tarjetas de prueba con comportamiento predefinido
     */
    private array $tarjetasPrueba = [
        // Siempre aprobadas
        '4111111111111111' => ['estado' => 'aprobado', 'mensaje' => 'Pago aprobado exitosamente'],
        '5555555555554444' => ['estado' => 'aprobado', 'mensaje' => 'Pago aprobado exitosamente'],
        '378282246310005' => ['estado' => 'aprobado', 'mensaje' => 'Pago aprobado exitosamente'],

        // Siempre rechazadas
        '4000000000000002' => ['estado' => 'rechazado', 'mensaje' => 'Tarjeta rechazada por el banco'],

        // Fondos insuficientes
        '4000000000009995' => ['estado' => 'rechazado', 'mensaje' => 'Fondos insuficientes'],

        // Tarjeta vencida
        '4000000000000069' => ['estado' => 'rechazado', 'mensaje' => 'Tarjeta vencida'],

        // Tarjeta bloqueada
        '4000000000000119' => ['estado' => 'rechazado', 'mensaje' => 'Tarjeta bloqueada. Contacte a su banco'],
    ];

    /**
     * Procesar pago simulado
     *
     * @param array $datos [numero_tarjeta, nombre_tarjeta, fecha_vencimiento, cvv, monto, metodo_pago]
     * @return array ['success' => bool, 'transaccion' => Transaccion|null, 'mensaje' => string]
     */
    public function procesarPago(array $datos): array
    {
        try {
            DB::beginTransaction();

            // Crear transacción en estado pendiente
            $transaccion = Transaccion::create([
                'pedido_id' => $datos['pedido_id'] ?? null,
                'metodo_pago' => $datos['metodo_pago'],
                'estado' => 'pendiente',
                'monto' => $datos['monto'],
                'numero_transaccion' => Transaccion::generarNumeroTransaccion(),
            ]);
            // Si es efectivo, aprobar directamente
            if ($datos['metodo_pago'] === 'efectivo') {
                $transaccion->update([
                    'estado' => 'aprobado',
                    'mensaje_respuesta' => 'Pago en efectivo - Pendiente de recibir',
                    'fecha_procesamiento' => now(),
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'transaccion' => $transaccion,
                    'mensaje' => 'Pedido confirmado. Pago en efectivo al recibir.',
                ];
            }

            // Cambiar estado a procesando
            $transaccion->update(['estado' => 'procesando']);

            // Simular delay de procesamiento (2 segundos)
            sleep(2);

            // Validar número de tarjeta (algoritmo Luhn)
            if (!$this->validarNumeroTarjeta($datos['numero_tarjeta'])) {
                $transaccion->update([
                    'estado' => 'rechazado',
                    'mensaje_respuesta' => 'Número de tarjeta inválido',
                    'fecha_procesamiento' => now(),
                ]);

                DB::commit();

                return [
                    'success' => false,
                    'transaccion' => $transaccion,
                    'mensaje' => 'Número de tarjeta inválido',
                ];
            }

            // Simular respuesta de pasarela
            $resultado = $this->simularRespuestaPasarela($datos['numero_tarjeta']);

            // Guardar últimos 4 dígitos de la tarjeta
            $ultimosCuatroDigitos = substr($datos['numero_tarjeta'], -4);
            $tipoTarjeta = $this->detectarTipoTarjeta($datos['numero_tarjeta']);

            $transaccion->update([
                'estado' => $resultado['estado'],
                'mensaje_respuesta' => $resultado['mensaje'],
                'detalles_tarjeta' => [
                    'ultimos_digitos' => $ultimosCuatroDigitos,
                    'tipo' => $tipoTarjeta,
                    'nombre' => strtoupper($datos['nombre_tarjeta']),
                ],
                'fecha_procesamiento' => now(),
            ]);

            DB::commit();

            return [
                'success' => $resultado['estado'] === 'aprobado',
                'transaccion' => $transaccion,
                'mensaje' => $resultado['mensaje'],
            ];

        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'transaccion' => null,
                'mensaje' => 'Error procesando el pago: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validar número de tarjeta usando algoritmo de Luhn
     *
     * @param string $numero
     * @return bool
     */
    private function validarNumeroTarjeta(string $numero): bool
    {
        $numero = preg_replace('/\D/', '', $numero);

        if (strlen($numero) < 13 || strlen($numero) > 19) {
            return false;
        }

        $sum = 0;
        $length = strlen($numero);
        $parity = $length % 2;

        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $numero[$i];

            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        return ($sum % 10 === 0);
    }

    /**
     * Simular respuesta de pasarela de pago
     *
     * @param string $numeroTarjeta
     * @return array ['estado' => string, 'mensaje' => string]
     */
    private function simularRespuestaPasarela(string $numeroTarjeta): array
    {
        $numeroLimpio = preg_replace('/\D/', '', $numeroTarjeta);

        // Verificar si es una tarjeta de prueba
        if (isset($this->tarjetasPrueba[$numeroLimpio])) {
            return $this->tarjetasPrueba[$numeroLimpio];
        }

        // Para tarjetas no predefinidas: 90% de éxito, 10% de fallo
        $random = rand(1, 100);

        if ($random <= 90) {
            return [
                'estado' => 'aprobado',
                'mensaje' => 'Pago aprobado exitosamente',
            ];
        } else {
            $mensajesError = [
                'Tarjeta rechazada por el banco',
                'Fondos insuficientes',
                'Error en la transacción. Por favor intente nuevamente',
            ];

            return [
                'estado' => 'rechazado',
                'mensaje' => $mensajesError[array_rand($mensajesError)],
            ];
        }
    }

    /**
     * Detectar tipo de tarjeta por el número
     *
     * @param string $numero
     * @return string
     */
    private function detectarTipoTarjeta(string $numero): string
    {
        $numero = preg_replace('/\D/', '', $numero);

        if (preg_match('/^4/', $numero)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $numero)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]/', $numero)) {
            return 'American Express';
        } elseif (preg_match('/^6(?:011|5)/', $numero)) {
            return 'Discover';
        } elseif (preg_match('/^35/', $numero)) {
            return 'JCB';
        }

        return 'Desconocida';
    }

    /**
     * Obtener tarjetas de prueba disponibles
     *
     * @return array
     */
    public function getTarjetasPrueba(): array
    {
        return $this->tarjetasPrueba;
    }

    /**
     * Verificar estado de una transacción
     *
     * @param int $transaccionId
     * @return Transaccion|null
     */
    public function verificarEstadoTransaccion(int $transaccionId): ?Transaccion
    {
        return Transaccion::find($transaccionId);
    }
}
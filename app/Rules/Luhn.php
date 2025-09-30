<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Luhn implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passesLuhnCheck($value)) {
            $fail('El número de tarjeta no es válido.');
        }
    }

    /**
     * Validar número de tarjeta usando el algoritmo de Luhn
     *
     * @param mixed $value
     * @return bool
     */
    private function passesLuhnCheck($value): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        // Remover espacios y caracteres no numéricos
        $numero = preg_replace('/\D/', '', $value);

        // Validar longitud (13-19 dígitos para tarjetas)
        if (strlen($numero) < 13 || strlen($numero) > 19) {
            return false;
        }

        $sum = 0;
        $length = strlen($numero);
        $parity = $length % 2;

        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $numero[$i];

            // Duplicar cada segundo dígito desde la derecha
            if ($i % 2 === $parity) {
                $digit *= 2;
                // Si el resultado es mayor a 9, restar 9
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        // El número es válido si la suma es divisible por 10
        return ($sum % 10 === 0);
    }
}

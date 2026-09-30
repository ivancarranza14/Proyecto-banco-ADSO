<?php

declare(strict_types=1);

namespace App\Servicios\Excepciones;

use RuntimeException;

/**
 * Se lanza cuando el saldo disponible de la cuenta origen no alcanza
 * para cubrir un retiro (RN5) o una transferencia (RN6).
 */
final class SaldoInsuficienteException extends RuntimeException
{
}

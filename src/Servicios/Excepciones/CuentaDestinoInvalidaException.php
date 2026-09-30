<?php

declare(strict_types=1);

namespace App\Servicios\Excepciones;

use RuntimeException;

/**
 * Se lanza cuando la cuenta destino de una transferencia es la misma
 * cuenta origen (RN6, RF6 punto 8: "la cuenta destino sea diferente a la
 * cuenta origen").
 */
final class CuentaDestinoInvalidaException extends RuntimeException
{
}

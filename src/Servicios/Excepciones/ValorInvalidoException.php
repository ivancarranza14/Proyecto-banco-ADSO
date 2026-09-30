<?php

declare(strict_types=1);

namespace App\Servicios\Excepciones;

use RuntimeException;

/**
 * Se lanza cuando el valor de un retiro o transferencia no es numérico o
 * no es mayor que 0 (RN5/RN6: vacío, negativo, cero o texto no numérico
 * se rechazan).
 */
final class ValorInvalidoException extends RuntimeException
{
}

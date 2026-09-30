<?php

declare(strict_types=1);

namespace App\Servicios\Excepciones;

use RuntimeException;

/**
 * Se lanza cuando la cuenta destino de una transferencia no existe (RN6),
 * o cuando la cuenta de la sesión activa ya no existe en base de datos
 * (caso defensivo, no debería ocurrir en operación normal).
 */
final class CuentaNoEncontradaException extends RuntimeException
{
}

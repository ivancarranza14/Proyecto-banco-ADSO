<?php

declare(strict_types=1);

namespace App\Servicios\Excepciones;

use RuntimeException;

/**
 * Se lanza cuando el número de cuenta no existe o la contraseña no
 * coincide (login), o cuando la reconfirmación de contraseña de un
 * retiro/transferencia falla — es la misma regla de negocio ("¿esta
 * contraseña es correcta para esta cuenta?") aplicada en puntos
 * distintos del flujo, por eso reutilizan la misma excepción
 * (Buenas Practicas S1-5).
 */
final class CredencialesInvalidasException extends RuntimeException
{
}

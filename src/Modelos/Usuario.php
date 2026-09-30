<?php

declare(strict_types=1);

namespace App\Modelos;

/**
 * Representa el mecanismo de acceso a la aplicacion (tabla `usuarios`),
 * separado de Cliente y de Cuenta: la clave_hash depende unicamente del
 * id de este registro, no de la persona ni de la cuenta (3FN, seccion 7.1
 * del Taller Practico — es la dependencia transitiva que se evita
 * separando esta tabla).
 */
final class Usuario
{
    public function __construct(
        private readonly int $id,
        private readonly int $cuentaId,
        private readonly string $claveHash,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerCuentaId(): int
    {
        return $this->cuentaId;
    }

    public function obtenerClaveHash(): string
    {
        return $this->claveHash;
    }
}

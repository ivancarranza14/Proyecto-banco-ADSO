<?php

declare(strict_types=1);

namespace App\Modelos;

use DateTimeImmutable;

/**
 * Representa un retiro ya registrado (tabla `retiros`): sobre que cuenta,
 * por cuanto y cuando. Es el resultado de una operacion ya validada y
 * aplicada — la validacion en si (RF4/RN5) vive en ServicioCuenta, no
 * aqui (Buenas Practicas S1-1: el modelo no decide reglas de negocio).
 */
final class Retiro
{
    public function __construct(
        private readonly int $id,
        private readonly int $cuentaId,
        private readonly string $valor,
        private readonly DateTimeImmutable $fecha,
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

    public function obtenerValor(): string
    {
        return $this->valor;
    }

    public function obtenerFecha(): DateTimeImmutable
    {
        return $this->fecha;
    }
}

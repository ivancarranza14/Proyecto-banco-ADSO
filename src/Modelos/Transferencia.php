<?php

declare(strict_types=1);

namespace App\Modelos;

use DateTimeImmutable;

/**
 * Representa una transferencia ya registrada (tabla `transferencias`):
 * cuenta origen, cuenta destino, valor y fecha. Al igual que Retiro, es
 * el resultado de una operacion ya validada — la atomicidad y las
 * validaciones (RF6/RN6/RN7) viven en ServicioCuenta, no aqui.
 */
final class Transferencia
{
    public function __construct(
        private readonly int $id,
        private readonly int $cuentaOrigenId,
        private readonly int $cuentaDestinoId,
        private readonly string $valor,
        private readonly DateTimeImmutable $fecha,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerCuentaOrigenId(): int
    {
        return $this->cuentaOrigenId;
    }

    public function obtenerCuentaDestinoId(): int
    {
        return $this->cuentaDestinoId;
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

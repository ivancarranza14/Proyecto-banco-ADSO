<?php

declare(strict_types=1);

namespace App\Modelos;

/**
 * Representa el producto bancario (tabla `cuentas`): numero de cuenta,
 * saldo y a que cliente pertenece. Solo lo que depende del producto
 * bancario en si, no de la persona ni del acceso (3FN, seccion 7.1).
 *
 * $saldo se guarda como string, no float: el DECIMAL(12,2) de MySQL viene
 * de PDO como texto, y evitamos sumar/restar dinero con floats por los
 * errores de precision de coma flotante (Buenas Practicas S1-7). La
 * aritmetica real de un retiro o transferencia la hace la consulta SQL
 * (UPDATE ... SET saldo = saldo - :valor), no este objeto: por eso todas
 * las propiedades son readonly, esta clase es solo una fotografia de la
 * fila que se leyo de base de datos en ese instante.
 */
final class Cuenta
{
    public function __construct(
        private readonly int $id,
        private readonly string $numeroCuenta,
        private readonly string $saldo,
        private readonly int $clienteId,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerNumeroCuenta(): string
    {
        return $this->numeroCuenta;
    }

    public function obtenerSaldo(): string
    {
        return $this->saldo;
    }

    public function obtenerClienteId(): int
    {
        return $this->clienteId;
    }
}

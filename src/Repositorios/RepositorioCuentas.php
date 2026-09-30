<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Cuenta;
use App\Nucleo\Conexion;
use PDO;

/**
 * Acceso a la tabla `cuentas` vía PDO con consultas preparadas. No decide
 * reglas de negocio (Buenas Practicas S1-1): valida saldo suficiente,
 * por ejemplo, es trabajo de ServicioCuenta, no de este repositorio.
 *
 * descontarSaldo() y abonarSaldo() dejan que la propia consulta SQL haga
 * la aritmética (Buenas Practicas S1-7); cuando se usan como parte de una
 * transferencia, ambas deben ejecutarse dentro de la misma transacción
 * PDO que abre y cierra ServicioCuenta::transferir() (Buenas Practicas
 * S2-5) — este repositorio no abre transacciones por su cuenta, solo
 * conoce su propia tabla.
 */
final class RepositorioCuentas
{
    private readonly PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::obtener();
    }

    public function buscarPorNumeroCuenta(string $numeroCuenta): ?Cuenta
    {
        $consulta = $this->pdo->prepare(
            'SELECT id, numero_cuenta, saldo, cliente_id
             FROM cuentas
             WHERE numero_cuenta = :numero_cuenta'
        );
        $consulta->execute(['numero_cuenta' => $numeroCuenta]);
        $fila = $consulta->fetch();

        return $fila === false ? null : $this->hidratar($fila);
    }

    public function buscarPorId(int $id): ?Cuenta
    {
        $consulta = $this->pdo->prepare(
            'SELECT id, numero_cuenta, saldo, cliente_id
             FROM cuentas
             WHERE id = :id'
        );
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();

        return $fila === false ? null : $this->hidratar($fila);
    }

    public function descontarSaldo(int $cuentaId, string $valor): void
    {
        $consulta = $this->pdo->prepare(
            'UPDATE cuentas SET saldo = saldo - :valor WHERE id = :id'
        );
        $consulta->execute(['valor' => $valor, 'id' => $cuentaId]);
    }

    public function abonarSaldo(int $cuentaId, string $valor): void
    {
        $consulta = $this->pdo->prepare(
            'UPDATE cuentas SET saldo = saldo + :valor WHERE id = :id'
        );
        $consulta->execute(['valor' => $valor, 'id' => $cuentaId]);
    }

    /**
     * @param array<string, mixed> $fila
     */
    private function hidratar(array $fila): Cuenta
    {
        return new Cuenta(
            id: (int) $fila['id'],
            numeroCuenta: (string) $fila['numero_cuenta'],
            saldo: (string) $fila['saldo'],
            clienteId: (int) $fila['cliente_id'],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Transferencia;
use App\Nucleo\Conexion;
use DateTimeImmutable;
use PDO;

/**
 * Acceso a la tabla `transferencias`. registrar() inserta una
 * transferencia ya validada — dentro de la transaccion que abre y cierra
 * ServicioCuenta::transferir() (Buenas Practicas S2-5), no aqui.
 */
final class RepositorioTransferencias
{
    private readonly PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::obtener();
    }

    public function registrar(int $cuentaOrigenId, int $cuentaDestinoId, string $valor): void
    {
        $consulta = $this->pdo->prepare(
            'INSERT INTO transferencias (cuenta_origen_id, cuenta_destino_id, valor, fecha)
             VALUES (:origen, :destino, :valor, NOW())'
        );
        $consulta->execute([
            'origen' => $cuentaOrigenId,
            'destino' => $cuentaDestinoId,
            'valor' => $valor,
        ]);
    }

    /**
     * RF7: solo las enviadas por esta cuenta (recibidas es opcional).
     *
     * @return Transferencia[]
     */
    public function listarEnviadasPorCuenta(int $cuentaId): array
    {
        $consulta = $this->pdo->prepare(
            'SELECT id, cuenta_origen_id, cuenta_destino_id, valor, fecha
             FROM transferencias
             WHERE cuenta_origen_id = :cuenta_id
             ORDER BY fecha DESC'
        );
        $consulta->execute(['cuenta_id' => $cuentaId]);

        $transferencias = [];
        foreach ($consulta->fetchAll() as $fila) {
            $transferencias[] = new Transferencia(
                id: (int) $fila['id'],
                cuentaOrigenId: (int) $fila['cuenta_origen_id'],
                cuentaDestinoId: (int) $fila['cuenta_destino_id'],
                valor: (string) $fila['valor'],
                fecha: new DateTimeImmutable((string) $fila['fecha']),
            );
        }

        return $transferencias;
    }
}

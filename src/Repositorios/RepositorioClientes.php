<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Cliente;
use App\Nucleo\Conexion;
use PDO;

/**
 * Acceso a la tabla `clientes`. Solo lo que depende de la identidad de
 * la persona (Taller Practico, seccion 7.1).
 */
final class RepositorioClientes
{
    private readonly PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::obtener();
    }

    public function buscarPorId(int $id): ?Cliente
    {
        $consulta = $this->pdo->prepare('SELECT id, nombre FROM clientes WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $fila = $consulta->fetch();

        if ($fila === false) {
            return null;
        }

        return new Cliente(
            id: (int) $fila['id'],
            nombre: (string) $fila['nombre'],
        );
    }
}

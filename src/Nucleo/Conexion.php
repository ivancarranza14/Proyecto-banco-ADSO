<?php

declare(strict_types=1);

namespace App\Nucleo;

use PDO;
use PDOException;

/**
 * Conexion PDO unica por request (Singleton).
 *
 * Reservamos el patron singleton para este caso concreto porque
 * efectivamente necesitamos UNA sola conexion compartida durante toda
 * la peticion (Buenas Practicas, Sesion 1, punto 6). No se debe copiar
 * este patron a Modelos ni Servicios "por costumbre".
 */
final class Conexion
{
    private static ?PDO $instancia = null;

    private function __construct()
    {
    }

    public static function obtener(): PDO
    {
        if (self::$instancia === null) {
            $config = require dirname(__DIR__, 2) . '/config/basedatos.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['puerto'],
                $config['nombre'],
                $config['charset']
            );

            try {
                self::$instancia = new PDO(
                    $dsn,
                    $config['usuario'],
                    $config['clave'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                // Aqui SI se deja ver un mensaje tecnico porque es un fallo de
                // infraestructura (no hay conexion a la base de datos), no una
                // regla de negocio incumplida.
                throw new PDOException('No fue posible conectar a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }
}

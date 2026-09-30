<?php

declare(strict_types=1);

namespace App\Nucleo;

/**
 * Controlador base con la verificacion de sesion centralizada
 * (Buenas Practicas, Sesion 2, punto 6). Los controladores que requieren
 * sesion activa extienden esta clase y llaman a requerirSesion() al
 * inicio de cada accion protegida, en vez de repetir el chequeo.
 */
abstract class ControladorBase
{
    protected function requerirSesion(): void
    {
        if (!isset($_SESSION['cuenta_id'])) {
            header('Location: /index.php?ruta=sesion/login');
            exit;
        }
    }

    protected function idCuentaActual(): int
    {
        $this->requerirSesion();

        return (int) $_SESSION['cuenta_id'];
    }
}

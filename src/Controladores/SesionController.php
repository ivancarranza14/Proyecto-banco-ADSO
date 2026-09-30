<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Nucleo\ControladorBase;
use App\Nucleo\Vista;
use App\Servicios\Excepciones\CredencialesInvalidasException;
use App\Servicios\ServicioAutenticacion;

/**
 * RF1 — inicio de sesión. Es la única acción pública (no extiende
 * requerirSesion en loginAccion, porque todavía no hay sesión que
 * verificar).
 */
final class SesionController extends ControladorBase
{
    public function loginAccion(): void
    {
        $esPost = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

        if (!$esPost) {
            Vista::render('sesion/login', ['error' => null]);
            return;
        }

        $numeroCuenta = trim((string) ($_POST['numero_cuenta'] ?? ''));
        $clave = (string) ($_POST['clave'] ?? '');

        try {
            $cuenta = (new ServicioAutenticacion())->autenticar($numeroCuenta, $clave);
        } catch (CredencialesInvalidasException $error) {
            Vista::render('sesion/login', ['error' => $error->getMessage()]);
            return;
        }

        // Solo se guarda el id de la cuenta autenticada — nunca la
        // contraseña (Requisitos tecnicos, RF1).
        $_SESSION['cuenta_id'] = $cuenta->obtenerId();

        header('Location: /index.php?ruta=cuenta/panel');
        exit;
    }

    public function logoutAccion(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: /index.php?ruta=sesion/login');
        exit;
    }
}

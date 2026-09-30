<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Nucleo\ControladorBase;
use App\Nucleo\Vista;
use App\Servicios\ServicioCuenta;

/**
 * RF2/RF3 — panel principal. El id de cuenta sale siempre de la sesión
 * (idCuentaActual(), heredado de ControladorBase), nunca de un parámetro
 * de la URL o del formulario.
 */
final class CuentaController extends ControladorBase
{
    public function panelAccion(): void
    {
        $cuentaId = $this->idCuentaActual();
        $saldo = (new ServicioCuenta())->obtenerSaldo($cuentaId);

        Vista::render('cuenta/panel', ['saldo' => $saldo]);
    }
}

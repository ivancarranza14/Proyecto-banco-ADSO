<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Nucleo\ControladorBase;
use App\Nucleo\Vista;
use App\Repositorios\RepositorioTransferencias;
use App\Servicios\Excepciones\CredencialesInvalidasException;
use App\Servicios\Excepciones\CuentaDestinoInvalidaException;
use App\Servicios\Excepciones\CuentaNoEncontradaException;
use App\Servicios\Excepciones\SaldoInsuficienteException;
use App\Servicios\Excepciones\ValorInvalidoException;
use App\Servicios\ServicioCuenta;

/**
 * RF6/RF7 — transferencia e historial. El formulario solo pide la
 * cuenta destino: la cuenta origen ya se conoce por la sesión
 * (idCuentaActual()), nunca se lee de un campo oculto ni de la URL.
 */
final class TransferenciaController extends ControladorBase
{
    public function formularioAccion(): void
    {
        $this->requerirSesion();
        Vista::render('transferencias/formulario', []);
    }

    public function registrarAccion(): void
    {
        $cuentaOrigenId = $this->idCuentaActual();
        $numeroCuentaDestino = trim((string) ($_POST['numero_cuenta_destino'] ?? ''));
        $valor = trim((string) ($_POST['valor'] ?? ''));
        $clave = (string) ($_POST['clave'] ?? '');

        try {
            (new ServicioCuenta())->transferir($cuentaOrigenId, $numeroCuentaDestino, $valor, $clave);
            $_SESSION['transferencia_mensaje'] = 'Transferencia realizada con éxito.';
        } catch (
            CredencialesInvalidasException
            | ValorInvalidoException
            | SaldoInsuficienteException
            | CuentaNoEncontradaException
            | CuentaDestinoInvalidaException $error
        ) {
            $_SESSION['transferencia_error'] = $error->getMessage();
        }

        header('Location: /index.php?ruta=transferencia/resultado');
        exit;
    }

    public function resultadoAccion(): void
    {
        $this->requerirSesion();

        $mensaje = $_SESSION['transferencia_mensaje'] ?? null;
        $error = $_SESSION['transferencia_error'] ?? null;
        unset($_SESSION['transferencia_mensaje'], $_SESSION['transferencia_error']);

        Vista::render('transferencias/resultado', ['mensaje' => $mensaje, 'error' => $error]);
    }

    /**
     * RF7: solo las transferencias enviadas por la cuenta de sesión.
     */
    public function historialAccion(): void
    {
        $cuentaId = $this->idCuentaActual();
        $transferencias = (new RepositorioTransferencias())->listarEnviadasPorCuenta($cuentaId);

        $total = 0.0;
        foreach ($transferencias as $transferencia) {
            $total += (float) $transferencia->obtenerValor();
        }

        Vista::render('transferencias/historial', [
            'transferencias' => $transferencias,
            'cantidad' => count($transferencias),
            'total' => number_format($total, 2, '.', ''),
        ]);
    }
}

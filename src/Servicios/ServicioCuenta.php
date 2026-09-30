<?php

declare(strict_types=1);

namespace App\Servicios;

use App\Nucleo\Conexion;
use App\Repositorios\RepositorioCuentas;
use App\Repositorios\RepositorioRetiros;
use App\Repositorios\RepositorioTransferencias;
use App\Servicios\Excepciones\CuentaDestinoInvalidaException;
use App\Servicios\Excepciones\CuentaNoEncontradaException;
use App\Servicios\Excepciones\SaldoInsuficienteException;
use App\Servicios\Excepciones\ValorInvalidoException;
use Throwable;

/**
 * Reglas de negocio que tocan la cuenta: saldo, retiro y transferencia
 * (Buenas Practicas S1-1). No sabe nada de HTTP ni de HTML — el
 * Controlador es quien atrapa las excepciones que lanza y decide el
 * mensaje que ve el usuario.
 */
final class ServicioCuenta
{
    private readonly RepositorioCuentas $repositorioCuentas;
    private readonly RepositorioRetiros $repositorioRetiros;
    private readonly RepositorioTransferencias $repositorioTransferencias;
    private readonly ServicioAutenticacion $servicioAutenticacion;

    public function __construct()
    {
        $this->repositorioCuentas = new RepositorioCuentas();
        $this->repositorioRetiros = new RepositorioRetiros();
        $this->repositorioTransferencias = new RepositorioTransferencias();
        $this->servicioAutenticacion = new ServicioAutenticacion();
    }

    /**
     * RF3/RN8: siempre en vivo contra base de datos, nunca desde caché
     * de sesión.
     */
    public function obtenerSaldo(int $cuentaId): string
    {
        $cuenta = $this->repositorioCuentas->buscarPorId($cuentaId);

        if ($cuenta === null) {
            throw new CuentaNoEncontradaException('La cuenta de la sesión ya no existe.');
        }

        return $cuenta->obtenerSaldo();
    }

    /**
     * RF4/RN5: valida, EN ESTE ORDEN, contraseña -> valor -> saldo. Si
     * alguna falla no se toca la base de datos. Si todas pasan, descuenta
     * el saldo e inserta el registro en retiros (RN9) — ambas cosas
     * quedan completas juntas porque son dos sentencias sueltas sobre la
     * misma conexión, sin nada intermedio que pueda fallar entre ellas.
     */
    public function registrarRetiro(int $cuentaId, string $valor, string $claveConfirmacion): void
    {
        $this->servicioAutenticacion->reconfirmarClave($cuentaId, $claveConfirmacion);
        $this->validarValor($valor);

        $cuenta = $this->repositorioCuentas->buscarPorId($cuentaId);
        if ($cuenta === null) {
            throw new CuentaNoEncontradaException('La cuenta de la sesión ya no existe.');
        }

        if ((float) $cuenta->obtenerSaldo() < (float) $valor) {
            throw new SaldoInsuficienteException('El saldo disponible no alcanza para este retiro.');
        }

        $this->repositorioCuentas->descontarSaldo($cuentaId, $valor);
        $this->repositorioRetiros->registrar($cuentaId, $valor);
    }

    /**
     * RF6/RN6/RN7: valida, EN ESTE ORDEN, contraseña -> cuenta destino
     * existe -> valor -> saldo suficiente -> destino distinto al origen.
     *
     * Regla clave (Buenas Practicas S2-5): la transacción vive aquí, no
     * en el Controlador ni en un Repositorio. Débito y crédito se
     * ejecutan dentro del mismo beginTransaction()/commit(), y si algo
     * falla a mitad de camino se hace rollBack() — el descuento en origen
     * se revierte por completo, nunca queda "a medias".
     */
    public function transferir(
        int $cuentaOrigenId,
        string $numeroCuentaDestino,
        string $valor,
        string $claveConfirmacion,
    ): void {
        $this->servicioAutenticacion->reconfirmarClave($cuentaOrigenId, $claveConfirmacion);

        $cuentaDestino = $this->repositorioCuentas->buscarPorNumeroCuenta($numeroCuentaDestino);
        if ($cuentaDestino === null) {
            throw new CuentaNoEncontradaException('La cuenta destino no existe.');
        }

        $this->validarValor($valor);

        $cuentaOrigen = $this->repositorioCuentas->buscarPorId($cuentaOrigenId);
        if ($cuentaOrigen === null) {
            throw new CuentaNoEncontradaException('La cuenta de la sesión ya no existe.');
        }

        if ((float) $cuentaOrigen->obtenerSaldo() < (float) $valor) {
            throw new SaldoInsuficienteException('El saldo disponible no alcanza para esta transferencia.');
        }

        if ($cuentaDestino->obtenerId() === $cuentaOrigen->obtenerId()) {
            throw new CuentaDestinoInvalidaException('La cuenta destino debe ser diferente a la cuenta origen.');
        }

        $pdo = Conexion::obtener();
        $pdo->beginTransaction();

        try {
            $this->repositorioCuentas->descontarSaldo($cuentaOrigenId, $valor);
            $this->repositorioCuentas->abonarSaldo($cuentaDestino->obtenerId(), $valor);
            $this->repositorioTransferencias->registrar($cuentaOrigenId, $cuentaDestino->obtenerId(), $valor);

            $pdo->commit();
        } catch (Throwable $error) {
            $pdo->rollBack();

            throw $error;
        }
    }

    private function validarValor(string $valor): void
    {
        if (!is_numeric($valor) || (float) $valor <= 0) {
            throw new ValorInvalidoException('El valor debe ser numérico y mayor que 0.');
        }
    }
}

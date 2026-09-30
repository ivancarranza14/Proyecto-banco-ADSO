<?php

declare(strict_types=1);

namespace App\Servicios;

use App\Modelos\Cuenta;
use App\Repositorios\RepositorioCuentas;
use App\Repositorios\RepositorioUsuarios;
use App\Servicios\Excepciones\CredencialesInvalidasException;

/**
 * Regla de negocio de acceso a la aplicación (RF1). No sabe nada de HTTP
 * ni de HTML (Buenas Practicas S1-1): recibe numero de cuenta y clave en
 * texto plano, y devuelve la Cuenta autenticada o lanza una excepción de
 * dominio — es el Controlador quien decide qué hacer con eso.
 */
final class ServicioAutenticacion
{
    private readonly RepositorioCuentas $repositorioCuentas;
    private readonly RepositorioUsuarios $repositorioUsuarios;

    public function __construct()
    {
        $this->repositorioCuentas = new RepositorioCuentas();
        $this->repositorioUsuarios = new RepositorioUsuarios();
    }

    /**
     * RF1: el mensaje debe ser idéntico tanto si la cuenta no existe como
     * si existe pero la clave no coincide — nunca reveles cuál de las dos
     * falló (eso sería una fuga de información).
     */
    public function autenticar(string $numeroCuenta, string $clave): Cuenta
    {
        $cuenta = $this->repositorioCuentas->buscarPorNumeroCuenta($numeroCuenta);

        if ($cuenta === null) {
            throw new CredencialesInvalidasException('Número de cuenta o contraseña incorrectos.');
        }

        $usuario = $this->repositorioUsuarios->buscarPorCuentaId($cuenta->obtenerId());

        if ($usuario === null || !password_verify($clave, $usuario->obtenerClaveHash())) {
            throw new CredencialesInvalidasException('Número de cuenta o contraseña incorrectos.');
        }

        return $cuenta;
    }

    /**
     * Reconfirmación de contraseña que piden el retiro y la transferencia
     * (RN4) sobre la cuenta que YA está en sesión — aquí sí sabemos que
     * la cuenta existe, así que no hay ambigüedad que ocultar.
     */
    public function reconfirmarClave(int $cuentaId, string $clave): void
    {
        $usuario = $this->repositorioUsuarios->buscarPorCuentaId($cuentaId);

        if ($usuario === null || !password_verify($clave, $usuario->obtenerClaveHash())) {
            throw new CredencialesInvalidasException('Contraseña incorrecta.');
        }
    }
}

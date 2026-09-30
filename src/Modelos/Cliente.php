<?php

declare(strict_types=1);

namespace App\Modelos;

/**
 * Representa a la persona dueña de una o mas cuentas (tabla `clientes`).
 * Solo contiene lo que depende exclusivamente de la identidad de la
 * persona (Taller Practico, seccion 7.1) — nada de saldo ni de acceso.
 *
 * No sabe nada de SQL ni de HTML (Buenas Practicas S1-1). Es readonly
 * porque el nombre de un cliente no se edita desde esta aplicacion: no
 * hay setNombre() "por si acaso" (Buenas Practicas S1-2).
 */
final class Cliente
{
    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerNombre(): string
    {
        return $this->nombre;
    }
}

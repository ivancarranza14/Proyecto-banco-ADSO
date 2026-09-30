<?php

declare(strict_types=1);

/**
 * Funcion de apoyo para escapar salida hacia el HTML.
 * Se carga vía "autoload files" de Composer (ver composer.json), no como
 * metodo estatico de una clase "utilidades" generica (ver Buenas Practicas,
 * Sesion 1, punto 9).
 *
 * Toda variable que venga de datos del usuario o de base de datos debe
 * imprimirse en las vistas a traves de esta funcion (Buenas Practicas,
 * Sesion 2, punto 4).
 */
function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

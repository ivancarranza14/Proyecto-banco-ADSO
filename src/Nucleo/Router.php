<?php

declare(strict_types=1);

namespace App\Nucleo;

/**
 * Router artesanal (sin frameworks).
 *
 * Convencion: ?ruta=controlador/accion/parametros
 *   -> clase   App\Controladores\{Controlador}Controlador
 *   -> metodo  {accion}Accion(...$parametros)
 *
 * Si la clase o el metodo no existen, se responde con la vista de error
 * 404 (Buenas Practicas, Sesion 2, punto 2) — nunca con un error de PHP
 * sin controlar visible al usuario.
 */
final class Router
{
    public function despachar(string $ruta): void
    {
        $segmentos = array_filter(explode('/', trim($ruta, '/')), fn ($s) => $s !== '');
        $segmentos = array_values($segmentos);

        $nombreControlador = $segmentos[0] ?? 'sesion';
        $nombreAccion = $segmentos[1] ?? 'login';
        $parametros = array_slice($segmentos, 2);

        $claseControlador = 'App\\Controladores\\' . ucfirst($nombreControlador) . 'Controlador';
        $metodoAccion = $nombreAccion . 'Accion';

        if (!class_exists($claseControlador) || !method_exists($claseControlador, $metodoAccion)) {
            http_response_code(404);
            Vista::render('errores/404');
            return;
        }

        $controlador = new $claseControlador();
        $controlador->$metodoAccion(...$parametros);
    }
}

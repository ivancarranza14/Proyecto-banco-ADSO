<?php

declare(strict_types=1);

/**
 * Front controller unico (Buenas Practicas, Sesion 2, punto 1).
 * Toda peticion entra por aqui y se delega en el Router. Ninguna otra
 * pagina en public/ debe manejar logica de aplicacion por su cuenta.
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Nucleo\Router;

session_start();

$ruta = $_GET['ruta'] ?? 'sesion/login';

$router = new Router();
$router->despachar((string) $ruta);

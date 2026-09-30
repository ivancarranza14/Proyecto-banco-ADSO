<?php

declare(strict_types=1);

namespace App\Nucleo;

/**
 * Renderiza una vista dentro del layout compartido (Buenas Practicas,
 * Sesion 2, punto 4). Solo presentacion: no consulta la base de datos ni
 * contiene reglas de negocio.
 */
final class Vista
{
    private const CARPETA_VISTAS = __DIR__ . '/../../vistas/';

    public static function render(string $vista, array $datos = []): void
    {
        $rutaVista = self::CARPETA_VISTAS . $vista . '.php';

        if (!is_file($rutaVista)) {
            http_response_code(500);
            echo 'Vista no encontrada: ' . e($vista);
            return;
        }

        // extract() expone cada clave de $datos como variable local,
        // igual que en Vista::render() de 06_mvc-php. El controlador ya
        // dejo estos datos listos para mostrar; la vista solo los imprime.
        extract($datos);

        $contenidoVista = function () use ($rutaVista, $datos): void {
            extract($datos);
            require $rutaVista;
        };

        require self::CARPETA_VISTAS . 'layout.php';
    }
}

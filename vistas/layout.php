<?php
/**
 * Layout compartido por todas las vistas (Buenas Practicas, Sesion 2,
 * punto 4): centraliza el CSS basico y la estructura HTML para que no se
 * repita en cada vista. Vista::render() define $contenidoVista y lo
 * invoca aqui adentro, en el lugar donde debe aparecer el contenido
 * propio de cada pantalla.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco ADSO</title>
    <link rel="stylesheet" href="/css/estilo.css">
</head>
<body>
    <header class="sitio">
        <h1>Banco ADSO</h1>
    </header>
    <main class="contenido">
        <?php $contenidoVista(); ?>
    </main>
</body>
</html>

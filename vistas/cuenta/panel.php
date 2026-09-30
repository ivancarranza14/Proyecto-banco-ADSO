<h2>Tu cuenta</h2>

<p>Saldo actual: <strong>$<?= e(number_format((float) $saldo, 2)) ?></strong></p>

<ul>
    <li><a href="/index.php?ruta=retiro/formulario">Hacer un retiro</a></li>
    <li><a href="/index.php?ruta=transferencia/formulario">Hacer una transferencia</a></li>
    <li><a href="/index.php?ruta=retiro/historial">Historial de retiros</a></li>
    <li><a href="/index.php?ruta=transferencia/historial">Historial de transferencias</a></li>
    <li><a href="/index.php?ruta=sesion/logout">Cerrar sesión</a></li>
</ul>

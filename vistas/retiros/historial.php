<h2>Historial de retiros</h2>

<p>Total de retiros: <?= e((string) $cantidad) ?> — Suma total: $<?= e($total) ?></p>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($retiros as $retiro): ?>
        <tr>
            <td><?= e($retiro->obtenerFecha()->format('Y-m-d H:i')) ?></td>
            <td>$<?= e(number_format((float) $retiro->obtenerValor(), 2)) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if ($cantidad === 0): ?>
        <tr>
            <td colspan="2">Todavía no tienes retiros registrados.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<p><a href="/index.php?ruta=cuenta/panel">Volver al panel</a></p>

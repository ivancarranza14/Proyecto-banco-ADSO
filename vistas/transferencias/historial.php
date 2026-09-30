<h2>Historial de transferencias enviadas</h2>

<p>Total de transferencias: <?= e((string) $cantidad) ?> — Suma total: $<?= e($total) ?></p>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Cuenta destino (id)</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transferencias as $transferencia): ?>
        <tr>
            <td><?= e($transferencia->obtenerFecha()->format('Y-m-d H:i')) ?></td>
            <td><?= e((string) $transferencia->obtenerCuentaDestinoId()) ?></td>
            <td>$<?= e(number_format((float) $transferencia->obtenerValor(), 2)) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if ($cantidad === 0): ?>
        <tr>
            <td colspan="3">Todavía no has hecho transferencias.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<p><a href="/index.php?ruta=cuenta/panel">Volver al panel</a></p>

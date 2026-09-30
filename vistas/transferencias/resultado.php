<h2>Resultado de la transferencia</h2>

<?php if (!empty($mensaje)): ?>
    <div class="mensaje-exito"><?= e($mensaje) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="mensaje-error"><?= e($error) ?></div>
<?php endif; ?>

<p><a href="/index.php?ruta=cuenta/panel">Volver al panel</a></p>

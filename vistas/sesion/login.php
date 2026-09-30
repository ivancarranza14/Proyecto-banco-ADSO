<h2>Iniciar sesión</h2>

<?php if (!empty($error)): ?>
    <div class="mensaje-error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" action="/index.php?ruta=sesion/login">
    <div>
        <label for="numero_cuenta">Número de cuenta</label>
        <input type="text" id="numero_cuenta" name="numero_cuenta" required autofocus>
    </div>
    <div>
        <label for="clave">Contraseña</label>
        <input type="password" id="clave" name="clave" required>
    </div>
    <button type="submit">Entrar</button>
</form>

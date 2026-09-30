<h2>Realizar retiro</h2>

<form method="post" action="/index.php?ruta=retiro/registrar">
    <div>
        <label for="valor">Valor a retirar</label>
        <input type="number" step="0.01" min="0.01" id="valor" name="valor" required>
    </div>
    <div>
        <label for="clave">Confirma tu contraseña</label>
        <input type="password" id="clave" name="clave" required>
    </div>
    <button type="submit">Retirar</button>
</form>

<p><a href="/index.php?ruta=cuenta/panel">Volver al panel</a></p>

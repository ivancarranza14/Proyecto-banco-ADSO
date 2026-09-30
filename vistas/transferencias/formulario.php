<h2>Realizar transferencia</h2>

<form method="post" action="/index.php?ruta=transferencia/registrar">
    <div>
        <label for="numero_cuenta_destino">Número de cuenta destino</label>
        <input type="text" id="numero_cuenta_destino" name="numero_cuenta_destino" required>
    </div>
    <div>
        <label for="valor">Valor a transferir</label>
        <input type="number" step="0.01" min="0.01" id="valor" name="valor" required>
    </div>
    <div>
        <label for="clave">Confirma tu contraseña</label>
        <input type="password" id="clave" name="clave" required>
    </div>
    <button type="submit">Transferir</button>
</form>

<p><a href="/index.php?ruta=cuenta/panel">Volver al panel</a></p>

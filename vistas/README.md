# Vistas

Solo presentación: reciben los datos que el Controlador ya preparó
(`extract($datos)` vía `Vista::render()`) y los muestran dentro de
`layout.php` (ya creado). Toda variable que venga del usuario o de base de
datos se imprime con `e()`.

Ya existen: `layout.php`, `errores/404.php`.

Por construir:

- [x] `sesion/login.php` — formulario con número de cuenta y contraseña
  (RF1). Si hay mensaje de error, mostrarlo con la clase `.mensaje-error`
  del CSS.
- [x] `cuenta/panel.php` — muestra el saldo recibido del controlador (no lo
  calcula), con enlaces a retiro, transferencia e historiales.
- [x] `retiros/formulario.php` — campos: valor a retirar, contraseña
  (reconfirmación).
- [x] `retiros/resultado.php` — vista de solo lectura tras el POST/Redirect/GET
  (RF4).
- [x] `retiros/historial.php` — tabla con cada retiro (valor, fecha) y el
  total, ya calculados por el controlador (RF5).
- [x] `transferencias/formulario.php` — campos: número de cuenta destino,
  valor, contraseña.
- [x] `transferencias/resultado.php` — vista de solo lectura tras el
  POST/Redirect/GET (RF6).
- [x] `transferencias/historial.php` — tabla con cada transferencia enviada
  (destino, valor, fecha) y el total (RF7).

Ninguna vista debe hacer `require` de `Conexion` ni consultar la base de
datos directamente.

# Controladores

"Thin controllers": verifican sesión, leen/sanean entrada, llaman al
Servicio/Repositorio, atrapan excepciones de dominio y deciden el mensaje,
renderizan la vista (Buenas Prácticas S2-5). Extienden `App\Nucleo\ControladorBase`
cuando la acción requiere sesión activa.

Por construir (`App\Controladores\{Nombre}Controlador`, cada método público
termina en `Accion`, según la convención del Router: `?ruta=controlador/accion`):

- [x] `SesionController`
  - `loginAccion()` — GET muestra el formulario; POST llama a
    `ServicioAutenticacion::autenticar()`, guarda `$_SESSION['cuenta_id']`
    (nunca la contraseña) y redirige al panel. Si falla, mismo mensaje de
    error genérico (RF1).
  - `logoutAccion()` — destruye la sesión y redirige al login.

- [x] `CuentaController` (requiere sesión, extiende `ControladorBase`)
  - `panelAccion()` — lee `idCuentaActual()` de la sesión (nunca de GET/POST),
    consulta el saldo en vivo (RF3) y renderiza el panel.

- [x] `RetiroController` (requiere sesión)
  - `formularioAccion()` — muestra el formulario de retiro.
  - `registrarAccion()` — POST: lee valor y clave, llama a
    `ServicioCuenta::registrarRetiro()`, atrapa las excepciones de dominio
    y redirige (**POST/Redirect/GET**, S2-8) a una vista de solo lectura
    con el resultado.
  - `historialAccion()` — RF5: total de retiros, valor de cada uno, suma
    total, calculados contra la tabla `retiros` de la cuenta de sesión.

- [x] `TransferenciaController` (requiere sesión)
  - `formularioAccion()` — solo pide cuenta destino y valor; la cuenta
    origen viene de la sesión (RF6).
  - `registrarAccion()` — POST/Redirect/GET igual que el retiro.
  - `historialAccion()` — RF7: transferencias **enviadas** por la cuenta
    de sesión (recibidas es opcional).

Ninguna de estas clases debe abrir transacciones PDO ni comparar saldos
directamente con `if` anidados — esa lógica vive en `ServicioCuenta`.

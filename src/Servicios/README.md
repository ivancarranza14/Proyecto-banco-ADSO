# Servicios

Reglas de negocio que no corresponden ni al Modelo ni al Controlador:
validar saldo, coordinar una transferencia atómica, autenticar. No saben
nada de HTTP ni de HTML (Buenas Prácticas S1-1).

Por construir (`App\Servicios\{Nombre}`):

- [x] `ServicioAutenticacion.php`
  - `autenticar(string $numeroCuenta, string $clave): Cuenta` — busca la
    cuenta y su `Usuario`, valida con `password_verify()`. Si algo no
    coincide, lanza `CredencialesInvalidasException` con **el mismo
    mensaje genérico** tanto si la cuenta no existe como si la clave es
    incorrecta (RF1 — nunca reveles cuál de las dos falló).
  - `reconfirmarClave(int $cuentaId, string $clave): void` — reutiliza la
    **misma** `CredencialesInvalidasException`, para retiro y
    transferencia (Buenas Prácticas S1-5).

- [x] `ServicioCuenta.php`
  - `obtenerSaldo(int $cuentaId): string` — SELECT en vivo (RF3, RN8).
  - `registrarRetiro(int $cuentaId, string $valor, string $claveConfirmacion): void`
    — valida en este orden (RF4/RN5): clave correcta → valor numérico y
    mayor que 0 → saldo suficiente. Si algo falla, lanza la excepción de
    dominio correspondiente y **no toca la base de datos**. Si todo pasa,
    descuenta el saldo e inserta el registro en `retiros` — ambas
    operaciones deben quedar completas juntas.
  - `transferir(int $cuentaOrigenId, string $numeroCuentaDestino, string $valor, string $claveConfirmacion): void`
    — valida en este orden (RF6/RN6): clave correcta → cuenta destino
    existe → valor > 0 → saldo suficiente en origen → destino ≠ origen.
    **Regla clave (S2-5):** esta transacción vive aquí, no en el
    Controlador ni en un Repositorio: `beginTransaction()`, débito en
    origen, crédito en destino (vía `RepositorioCuentas`), inserta el
    registro (vía `RepositorioTransferencias`), `commit()` si todo salió
    bien o `rollBack()` si algo falló a mitad de camino.

Excepciones de dominio a crear (terminan en `Exception`, describen el
problema no la solución — decide en qué carpeta viven, por ejemplo
`src/Servicios/Excepciones/`, ya que el documento no fija una ubicación
exacta):

- [x] `CredencialesInvalidasException`
- [x] `SaldoInsuficienteException`
- [x] `CuentaNoEncontradaException`
- [x] `ValorInvalidoException` (agregada: RN5/RN6 piden validar que el valor sea numérico y mayor que 0)
- [x] `CuentaDestinoInvalidaException` (agregada: RN6 pide que la cuenta destino sea distinta a la origen)

Estas excepciones las atrapa el **Controlador**, nunca el Servicio ni el
Repositorio, que no saben nada de HTML ni de cómo se presenta el error.

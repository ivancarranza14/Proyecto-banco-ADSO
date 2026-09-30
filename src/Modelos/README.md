# Modelos

Clases de entidad: **solo datos y reglas propias de esa entidad**. No saben
que existe HTTP, HTML ni SQL (Buenas Prácticas S1-1 y S2-3).

Por construir (una clase por archivo, `App\Modelos\{Nombre}`):

- [x] `Cliente.php` — `id`, `nombre` (tabla `clientes`).
- [x] `Cuenta.php` — `id`, `numeroCuenta`, `saldo`, `clienteId` (tabla `cuentas`).
- [x] `Usuario.php` — `id`, `cuentaId`, `claveHash` (tabla `usuarios`; acceso a la app).
- [x] `Retiro.php` — `id`, `cuentaId`, `valor`, `fecha` (tabla `retiros`).
- [x] `Transferencia.php` — `id`, `cuentaOrigenId`, `cuentaDestinoId`, `valor`, `fecha` (tabla `transferencias`).

Puntos a aplicar en cada una (Buenas Prácticas S1):

- `declare(strict_types=1);` al inicio del archivo.
- Propiedades tipadas, `private`/`protected` (nunca `public` salvo constantes).
- Preferir promoción de propiedades en el constructor (son básicamente
  contenedores de datos tipados).
- `readonly` en lo que no cambia tras construir el objeto (p. ej. el número
  de cuenta una vez cargado desde base de datos).
- Solo getters de lo que otra capa realmente necesita — nada "por si acaso"
  (un `Cliente` que nunca cambia de nombre no necesita `setNombre()`).
- Nombres de clase en PascalCase singular y en español; métodos en
  camelCase con verbos en español (`obtenerSaldo()`, etc.).

Ninguna regla que involucre más de una entidad a la vez (como una
transferencia, que toca dos cuentas) vive aquí — eso va en un **Servicio**.

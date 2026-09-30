# Repositorios

Acceso a datos aislado detrás de un repositorio por entidad. Saben
leer/escribir en su tabla vía PDO con consultas preparadas; **no deciden
reglas de negocio** (eso es de un Servicio) y un repositorio individual
**no maneja transacciones que toquen otra tabla** (Buenas Prácticas S1-1 y S2-5).

Por construir (`App\Repositorios\{Nombre}`), todos usando `Conexion::obtener()`:

- [x] `RepositorioClientes.php`
- [x] `RepositorioCuentas.php` — necesitas al menos:
  - `buscarPorNumeroCuenta(string $numeroCuenta): ?Cuenta` (RF1, login)
  - `buscarPorId(int $id): ?Cuenta` (RF3, saldo en vivo; nunca cache de sesión)
  - `descontarSaldo(int $cuentaId, string $valor): void` — vía
    `UPDATE cuentas SET saldo = saldo - :valor WHERE id = :id`, dejando la
    aritmética a la base de datos (Buenas Prácticas S1-7).
  - `abonarSaldo(int $cuentaId, string $valor): void` — mismo enfoque, para el crédito.
- [x] `RepositorioUsuarios.php` — `buscarPorCuentaId(int $cuentaId): ?Usuario` (para validar `clave_hash` con `password_verify()`).
- [x] `RepositorioRetiros.php` — `registrar(int $cuentaId, string $valor): void`, `listarPorCuenta(int $cuentaId): array` (para RF5, con fecha y valor).
- [x] `RepositorioTransferencias.php` — `registrar(int $origenId, int $destinoId, string $valor): void`, `listarEnviadasPorCuenta(int $cuentaId): array` (para RF7).

Ninguno de estos métodos abre ni cierra transacciones que combinen varias
tablas — eso lo coordina `ServicioCuenta` (ver `src/Servicios/README.md`,
regla clave de la Sesión 2).

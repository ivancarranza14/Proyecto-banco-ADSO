# Banco ADSO

Aplicación bancaria simulada — POO, Composer/PSR-4, MVC artesanal y PDO
sobre MySQL. Entrega y sustentación: **28 de septiembre**.

## Estado: implementación completa

Las 5 capas están construidas: Núcleo, Modelos, Repositorios, Servicios y
Controladores, más todas las vistas. Cada carpeta tiene su propio
`README.md` con el detalle y el checklist marcado.

- `src/Nucleo/` — `Conexion`, `Router`, `Vista`, `ControladorBase`.
- `src/Modelos/` — `Cliente`, `Cuenta`, `Usuario`, `Retiro`, `Transferencia`.
- `src/Repositorios/` — un repositorio por tabla (5).
- `src/Servicios/` — `ServicioAutenticacion`, `ServicioCuenta`, y 5
  excepciones de dominio en `src/Servicios/Excepciones/`
  (`CredencialesInvalidasException`, `SaldoInsuficienteException`,
  `CuentaNoEncontradaException`, `ValorInvalidoException`,
  `CuentaDestinoInvalidaException` — las dos últimas no estaban nombradas
  en el documento de buenas prácticas, las agregué siguiendo el mismo
  patrón para las validaciones de RF4/RF6 que sí exige el enunciado).
- `src/Controladores/` — `SesionController`, `CuentaController`,
  `RetiroController`, `TransferenciaController`.
- `vistas/` — login, panel, formularios, resultados e historiales.

## Antes de la primera ejecución

1. **Genera el autoload de Composer** (necesario porque `public/index.php`
   hace `require .../vendor/autoload.php`):
   ```bash
   composer install
   ```
   o, si no tienes ninguna dependencia externa que instalar:
   ```bash
   composer dump-autoload
   ```
2. **Revisa `config/basedatos.php`** — ajusta usuario/clave si tu MySQL no
   es `root` sin contraseña.
3. **Siembra los 5 clientes/cuentas/usuarios de prueba** (entregable
   obligatorio, sección 9): ejecuta `sql/siembra_datos.sql` una sola vez
   contra tu base `db_banco_adso` (phpMyAdmin, HeidiSQL, DBeaver, o
   `mysql db_banco_adso < sql/siembra_datos.sql`). Las contraseñas en
   texto plano para probar el login están en `sql/credenciales_prueba.md`
   — bórralo antes de subir el proyecto a un repositorio público.
4. **Corre el servidor embebido:**
   ```bash
   php -S localhost:8000 -t public
   ```
   y abre `http://localhost:8000/index.php?ruta=sesion/login`.

## Checklist de requerimientos funcionales (RF1–RF7)

- [x] RF1 — Login con número de cuenta + contraseña, mensaje de error idéntico si cuenta o clave fallan.
- [x] RF2 — Ninguna pantalla vuelve a preguntar la cuenta; todo sale de `$_SESSION` (`ControladorBase::idCuentaActual()`).
- [x] RF3 — Saldo consultado en vivo en cada carga del panel (`ServicioCuenta::obtenerSaldo()`).
- [x] RF4 — Retiro: valida contraseña, valor > 0 y saldo suficiente, en ese orden (`ServicioCuenta::registrarRetiro()`).
- [x] RF5 — Historial de retiros: total, valor de cada uno, suma total, calculados contra la tabla.
- [x] RF6 — Transferencia: valida contraseña, cuenta destino, valor > 0, saldo suficiente, destino ≠ origen; atómica con transacción (`ServicioCuenta::transferir()`).
- [x] RF7 — Historial de transferencias enviadas.

Implementado y verificado por lint/autoload; **falta que TÚ lo pruebes en
vivo** contra tu base de datos ya sembrada — sigue los 4 pasos de arriba y
recorre cada pantalla antes del 28.

## Puntos clave para la sustentación (40% de la nota)

1. **Normalización a 3FN** — por qué `clientes`, `cuentas` y `usuarios`
   están en tablas separadas: el saldo depende de la cuenta (no de la
   persona), y la `clave_hash` depende del registro de acceso (no de la
   cuenta ni de la persona). Cada separación evita una dependencia
   transitiva escondida.
2. **Aislamiento de cuentas (RF2)** — el punto exacto donde el id de
   cuenta se lee de `$_SESSION` es `App\Nucleo\ControladorBase::idCuentaActual()`;
   todos los controladores lo usan en vez de leer `$_GET`/`$_POST`.
3. **Atomicidad de la transferencia (RF6)** — `beginTransaction()` /
   `commit()` / `rollBack()` están en `App\Servicios\ServicioCuenta::transferir()`.
   Si el proceso falla después de `descontarSaldo()` pero antes de
   `abonarSaldo()` o de registrar la transferencia, el `rollBack()`
   deshace el descuento — el dinero nunca desaparece ni se duplica.

## Estructura completa

```
BancoADSO/
├── composer.json
├── config/basedatos.php
├── sql/
│   ├── referencia_esquema.sql   (solo referencia, tu BD ya existe)
│   ├── siembra_datos.sql        (ejecutar una vez)
│   └── credenciales_prueba.md   (borrar antes de compartir el proyecto)
├── public/
│   ├── index.php
│   └── css/estilo.css
├── src/
│   ├── Nucleo/        Conexion, Router, Vista, ControladorBase
│   ├── Modelos/        Cliente, Cuenta, Usuario, Retiro, Transferencia
│   ├── Repositorios/   RepositorioClientes/Cuentas/Usuarios/Retiros/Transferencias
│   ├── Servicios/      ServicioAutenticacion, ServicioCuenta, Excepciones/
│   └── Controladores/  Sesion, Cuenta, Retiro, Transferencia
└── vistas/
    ├── layout.php, errores/404.php
    ├── sesion/login.php
    ├── cuenta/panel.php
    ├── retiros/{formulario,resultado,historial}.php
    └── transferencias/{formulario,resultado,historial}.php
```

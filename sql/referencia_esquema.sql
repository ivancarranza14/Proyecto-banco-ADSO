-- Referencia del esquema esperado (seccion 7 del Taller Practico).
-- Tu base de datos db_banco_adso YA existe: este script NO se ejecuta,
-- es solo para que compares tu propio esquema contra el esperado antes
-- de la sustentacion (normalizacion 3FN, tipos DECIMAL(12,2), FKs).
--
-- Motor InnoDB, charset utf8mb4, dinero siempre DECIMAL(12,2) (nunca FLOAT),
-- llaves foraneas con ON DELETE RESTRICT.

-- clientes: solo lo que depende de la identidad de la persona
CREATE TABLE IF NOT EXISTS clientes (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- cuentas: solo lo que depende del producto bancario
CREATE TABLE IF NOT EXISTS cuentas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    numero_cuenta  VARCHAR(30) NOT NULL UNIQUE,
    saldo          DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    cliente_id     INT NOT NULL,
    CONSTRAINT fk_cuentas_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- usuarios: mecanismo de acceso, separado de persona y de cuenta
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    cuenta_id   INT NOT NULL UNIQUE,
    clave_hash  VARCHAR(255) NOT NULL,
    CONSTRAINT fk_usuarios_cuenta
        FOREIGN KEY (cuenta_id) REFERENCES cuentas(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- retiros: operacion sobre una cuenta
CREATE TABLE IF NOT EXISTS retiros (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    cuenta_id  INT NOT NULL,
    valor      DECIMAL(12,2) NOT NULL,
    fecha      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_retiros_cuenta
        FOREIGN KEY (cuenta_id) REFERENCES cuentas(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- transferencias: operacion entre dos cuentas
CREATE TABLE IF NOT EXISTS transferencias (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    cuenta_origen_id   INT NOT NULL,
    cuenta_destino_id  INT NOT NULL,
    valor              DECIMAL(12,2) NOT NULL,
    fecha              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transferencias_origen
        FOREIGN KEY (cuenta_origen_id) REFERENCES cuentas(id) ON DELETE RESTRICT,
    CONSTRAINT fk_transferencias_destino
        FOREIGN KEY (cuenta_destino_id) REFERENCES cuentas(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Siembra: recuerda que necesitas 5 clientes/cuentas/usuarios de prueba
-- (entregable obligatorio, seccion 9). Las claves se generan con
-- password_hash() en PHP, no se escriben en texto plano aqui.

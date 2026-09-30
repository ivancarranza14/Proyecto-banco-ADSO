-- Siembra de datos de prueba (entregable obligatorio, seccion 9 del
-- Taller Practico: "5 clientes/cuentas/usuarios de prueba").
--
-- Ejecuta esto UNA sola vez contra tu base db_banco_adso ya creada
-- (por phpMyAdmin, HeidiSQL, DBeaver o "mysql < siembra_datos.sql").
-- No lo ejecutes dos veces sin limpiar antes, o duplicaras las filas.
--
-- Las claves NO estan en texto plano: son hashes reales generados con
-- password_hash() de PHP (bcrypt), listos para que password_verify()
-- los valide en ServicioAutenticacion. Las contraseñas en texto plano
-- que corresponden a cada hash estan en sql/credenciales_prueba.md,
-- SOLO para que tu las uses al probar el login — nunca se guardan asi
-- en la base de datos.

INSERT INTO clientes (nombre) VALUES
    ('Ana Gómez'),
    ('Carlos Pérez'),
    ('Laura Rodríguez'),
    ('Miguel Torres'),
    ('Sofía Ramírez');

-- Asume que los 5 clientes anteriores quedaron con id 1-5 en ese orden
-- (tabla recien creada / auto_increment desde 1). Si tu tabla clientes
-- ya tenia filas antes, ajusta los cliente_id de abajo.

INSERT INTO cuentas (numero_cuenta, saldo, cliente_id) VALUES
    ('1001', 500000.00, 1),
    ('1002', 750000.00, 2),
    ('1003', 300000.00, 3),
    ('1004', 1000000.00, 4),
    ('1005', 150000.00, 5);

-- Mismo supuesto: las 5 cuentas anteriores quedaron con id 1-5.

INSERT INTO usuarios (cuenta_id, clave_hash) VALUES
    (1, '$2y$12$V4g16fh.XiCpEPDC.Ocne.9tZVjCNDn1nEzldG0M9MIIOGzSpuk3q'),
    (2, '$2y$12$6mxDNdEITJuTgZU0nNU8JerqN25HLBFRTANLGQ5hDwSVIMnSBbSd6'),
    (3, '$2y$12$oMG4XxO03bAtYIphS/0L7eWfyv7/rNAsF4WmQkx05LOeT2kIW0bKG'),
    (4, '$2y$12$p7qEdyOBYKl8dogzwWCiA.YGLpBZPh7BIPP8K1T8cOzFUYY/BdNz2'),
    (5, '$2y$12$PTAvF/tYaQpPrxqlZh0nleKqZYWeWmPCV4AIZG4aZcdNZX7VuATAW');

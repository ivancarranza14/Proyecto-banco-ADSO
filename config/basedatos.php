<?php

declare(strict_types=1);

/**
 * Parametros de conexion a la base de datos ya creada (db_banco_adso).
 *
 * Ajusta aqui usuario/clave/host segun tu entorno (php -S embebido o XAMPP).
 * Este archivo NO crea ni modifica tablas: la base de datos y sus 5 tablas
 * (clientes, cuentas, usuarios, retiros, transferencias) ya existen.
 *
 * Idealmente estos valores no se suben a un repositorio publico; para este
 * taller basta con dejarlos aqui, pero si usas git considera excluir este
 * archivo o moverlo a variables de entorno mas adelante.
 */
return [
    'host'    => '127.0.0.1',
    'puerto'  => '3306',
    'nombre'  => 'db_banco_adso',
    'usuario' => 'root',
    'clave'   => '',
    'charset' => 'utf8mb4',
];

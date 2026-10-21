<?php

require_once __DIR__ . '/conexion.php';

$database = new database();
$conexion = $database->getConnection();

if ($conexion) {
    echo "Conexión exitosa";
} else {
    echo "Error en la conexión";
    echo "Error: " . $database->getConnection()->connect_error;
};
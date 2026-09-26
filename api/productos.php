<?php

require_once __DIR__ . "/../app/controllers/productocontroller.php";

header("Content-Type: application/json; charset=UTF-8");

$productoController = new ProductoController();
$productos = $productoController->obtenerTodos();


echo json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

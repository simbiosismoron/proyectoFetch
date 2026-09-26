<?php

require_once "producto.php";

$producto = new Producto();

$productos = $producto->obtenerTodos();

echo "<pre>";
print_r($productos);
echo "</pre>";

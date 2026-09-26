<?php

require_once __DIR__ . "/../models/producto.php";

class ProductoController
{
    private Producto $producto;

    public function __construct()
    {
        $this->producto = new Producto();
    }

    public function obtenerTodos()
    {
        return $this->producto->obtenerTodos();
    }
}

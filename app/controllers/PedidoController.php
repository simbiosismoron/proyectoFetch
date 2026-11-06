<?php

require_once __DIR__ . "/../models/pedido.php";

class PedidoController
{
    private pedido $pedido;

    public function __construct()
    {
        $this->pedido = new pedido();
    }

    public function realizarPedido(int $idUsuario, $datos): array
    {
    if (
        !is_array($datos) ||
        !isset($datos['productos']) ||
        !is_array($datos['productos']) ||
        count($datos['productos']) === 0
    ) {
        throw new InvalidArgumentException("Datos de pedido inválidos");
    }

    foreach ($datos["productos"] as $producto) {

        if (
            !is_array($producto) ||
            !isset($producto["idProducto"], $producto["cantidad"]) ||
            filter_var($producto["idProducto"], FILTER_VALIDATE_INT) === false ||
            (int) $producto["idProducto"] <= 0 ||
            filter_var($producto["cantidad"], FILTER_VALIDATE_INT) === false ||
            (int) $producto["cantidad"] <= 0
        ) {
            throw new InvalidArgumentException("El pedido contiene productos inválidos");
        }
    }

        return $this->pedido->realizarPedido($idUsuario, $datos["productos"]);
    }
}

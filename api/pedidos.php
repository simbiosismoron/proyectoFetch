<?php

header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../app/models/pedido.php";

$pedido = new pedido();

$datos = json_decode(
    file_get_contents("php://input"),
    true
);

try {
    $resultado = $pedido->realizarPedido($datos['idUsuario'], $datos['productos']);
    echo json_encode($resultado);
} catch (Exception $e) {
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
}

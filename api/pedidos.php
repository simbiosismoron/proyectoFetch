<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../app/models/pedido.php";

$pedido = new pedido();

$datos = json_decode(
    file_get_contents("php://input"),
    true
);

try {

    if (!isset($_SESSION["idUsuario"])) {
        http_response_code(401);
        echo json_encode([
            "ok" => false,
            "error" => "No autenticado"
        ]);
        exit;
    }

    $resultado = $pedido->realizarPedido($_SESSION['idUsuario'], $datos['productos']);
    echo json_encode($resultado);
} catch (Throwable $e) {

    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        "ok" => false,
        "error" => "Ocurrió un error al procesar el pedido"
    ]);
}

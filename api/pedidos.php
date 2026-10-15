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

    if (
        !is_array($datos) ||
        !isset($datos['productos']) ||
        !is_array($datos['productos']) ||
        count($datos['productos']) === 0
    ) {
        http_response_code(400);
        echo json_encode([
            "ok" => false,
            "error" => "Datos de pedido inválidos"
        ]);
        exit;
    }

    foreach ($datos["productos"] as $producto) {

        if (
            !is_array($producto) ||
            !isset($producto["idProducto"], $producto["cantidad"]) ||
            filter_var($producto["idProducto"], FILTER_VALIDATE_INT) === false ||
            (int) $producto["idProducto"] <= 0 ||
            !is_numeric($producto["cantidad"]) ||
            !is_finite((float) $producto["cantidad"]) ||
            (float) $producto["cantidad"] <= 0
        ) {
            http_response_code(400);

            echo json_encode([
                "ok" => false,
                "error" => "El pedido contiene productos inválidos"
            ]);

            exit;
        }
    }

    $resultado = $pedido->realizarPedido($_SESSION['idUsuario'], $datos['productos']);
    echo json_encode($resultado);
} catch (InvalidArgumentException $e) {

    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
} catch (Throwable $e) {

    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        "ok" => false,
        "error" => "Ocurrió un error al procesar el pedido"
    ]);
}

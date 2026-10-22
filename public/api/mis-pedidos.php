<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../app/models/pedido.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Allow: GET");
    http_response_code(405);

    echo json_encode([
        "ok" => false,
        "error" => "Método HTTP no permitido"
    ]);

    exit;
}

if (!isset($_SESSION["idUsuario"])) {
    http_response_code(401);

    echo json_encode([
        "ok" => false,
        "error" => "No autenticado"
    ]);

    exit;
}

try {
    $pedido = new pedido();

    $pedidos = $pedido->obtenerPedidosPorUsuario(
        (int) $_SESSION["idUsuario"]
    );

    $ids = array_column($pedidos, "idVenta");

    $items = $pedido->obtenerItemsPorVentas($ids);

    $itemsPorVenta = [];

    foreach ($items as $item) {
        $idVenta = $item["idVenta"];
        $itemsPorVenta[$idVenta][] = $item;
    }

    foreach ($pedidos as $indice => $venta) {
        $idVenta = $venta["idVenta"];
        $pedidos[$indice]["items"] = $itemsPorVenta[$idVenta] ?? [];
    }


    echo json_encode([
        "ok" => true,
        "pedidos" => $pedidos
    ]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error interno del servidor"
    ]);
}

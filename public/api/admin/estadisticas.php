<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../../app/middleware/admin.php";
require_once __DIR__ . "/../../../app/models/Estadistica.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Allow: GET");
    http_response_code(405);

    echo json_encode([
        "ok" => false,
        "error" => "Método HTTP no permitido"
    ]);

    exit;
}

exigirAdmin(false);

try {
    $estadistica = new Estadistica();

    $resumen = $estadistica->obtenerResumenVentas();
    $cantidades = $estadistica->obtenerCantidadVendida();

    echo json_encode([
        "ok" => true,
        "estadisticas" => [
            "cantidadPedidos" => (int) $resumen["cantidadPedidos"],
            "importePedidos" => (float) $resumen["importePedidos"],
            "ticketPromedio" => (float) $resumen["ticketPromedio"],
            "unidadesVendidas" => (float) $cantidades["unidadesVendidas"]
        ]
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "No se pudieron obtener las estadísticas"
    ]);
}
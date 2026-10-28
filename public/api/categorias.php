<?php

header("Content-Type: Application/json; charset=UTF-8");

require_once __DIR__ . "/../../app/models/categoria.php";

if ($_SERVER["REQUEST_METHOD" !== "GET"]) {
    header("Allow: GET");
    http_response_code(405);

    echo json_encode([
        "ok" => false,
        "error" => "Método HTTP no permitido"
    ]);
    exit;
}

try {
    $categoria = new categoria();

    $categorias = $categoria->obtenerTodas();

    echo json_encode($categorias);
} catch (Throwable $e) {
    http_response_code(500);

    error_log($e->getMessage());

    echo json_encode([
        "ok" => false,
        "error" => "Error al obtener las categorías"
    ]);
}
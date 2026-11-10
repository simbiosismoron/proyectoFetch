<?php
header("Content-Type: application/json; charset=UTF-8");


require_once __DIR__ . "/../../app/controllers/UsuarioController.php";

$datos = json_decode(
    file_get_contents("php://input"),
    true
);


try {

    $controller = new UsuarioController();
    $resultado = $controller->registrar($datos);

    echo json_encode($resultado);
} catch (InvalidArgumentException $e) {

    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
} catch (Throwable $error) {

    http_response_code(500);
    error_log($error->getMessage());

    echo json_encode([
        "ok" => false,
        "error" => "No se pudo completar el registro"
    ]);
}

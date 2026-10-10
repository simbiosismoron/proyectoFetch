<?php
header("Content-Type: application/json; charset=UTF-8");


require_once __DIR__ . "/../app/models/usuario.php";

$datos = json_decode(
    file_get_contents("php://input"),
    true
);


try {

    if (
        !is_array($datos) ||
        !isset($datos["nombre"], $datos["correo"], $datos["password"])
    ) {
        throw new InvalidArgumentException("Faltan datos del registro");
    }

    if (
        !is_string($datos["nombre"]) ||
        !is_string($datos["correo"]) ||
        !is_string($datos["password"])
    ) {
        throw new InvalidArgumentException(
            "Los datos del registro deben ser texto"
        );
    }

    $usuario = new Usuario();

    $resultado = $usuario->registrar(
        $datos["nombre"],
        $datos["correo"],
        $datos["password"]
    );

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

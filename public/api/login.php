<?php

header("Content-Type: application/json; charset=UTF-8");

session_start();

require_once __DIR__ . "/../../app/controllers/UsuarioController.php";

try {
    $datos = json_decode(file_get_contents("php://input"), true);

    $controller = new UsuarioController();
    $resultado = $controller->iniciarSesion($datos);

    if (!$resultado) {
        http_response_code(401);

        echo json_encode([
            "ok" => false,
            "error" => "Correo o contraseña incorrectos"
        ]);

        exit;
    }

    session_regenerate_id(true);

    $_SESSION["idUsuario"] = $resultado["idUsuario"];
    $_SESSION["nombre"] = $resultado["nombre"];
    $_SESSION["rol"] = $resultado["rol"];

    echo json_encode([
        "ok" => true,
        "usuario" => $resultado
    ]);

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
        "error" => "Error interno del servidor"
    ]);
}   



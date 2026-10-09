<?php

header("Content-Type: application/json; charset=UTF-8");

session_start();

require_once __DIR__ . "/../app/models/usuario.php";

try {

    $datos = json_decode(file_get_contents("php://input"), true);

    if (
        !is_array($datos) ||
        !isset($datos["email"], $datos["password"]) ||
        !is_string($datos["email"]) ||
        !is_string($datos["password"])
    ) {
        throw new InvalidArgumentException("Datos de inicio de sesión inválidos");
    }

    $usuario = new Usuario();

    $resultado = $usuario->iniciarSesion(
        $datos["email"],
        $datos["password"]
    );

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
        "error" => "No se pudo iniciar sesión"
    ]);
}

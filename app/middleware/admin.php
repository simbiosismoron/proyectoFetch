<?php

require_once __DIR__ . "/../models/usuario.php";

function exigirAdmin(bool $respuestaJSON = true): void
{
    if (!isset($_SESSION["idUsuario"])) {
        responderErrorAdmin(
            401,
            "Debés iniciar sesión",
            $respuestaJSON
        );
    }

    $usuario = new usuario();

    $rol = $usuario->obtenerRolPorId(
        (int) $_SESSION["idUsuario"]
    );

    if ($rol !== "admin") {
        responderErrorAdmin(
            403,
            "No tenés permiso de administrador",
            $respuestaJSON
        );
    }
}

function responderErrorAdmin(
    int $codigo,
    string $mensaje,
    bool $respuestaJSON
): void {
    http_response_code($codigo);

    if ($respuestaJSON) {
        header("Content-Type: application/json; charset=UTF-8");

        echo json_encode([
            "ok" => false,
            "error" => $mensaje
        ]);
    } else {
        header("Content-Type: text/html; charset=UTF-8");

        echo "<h1>Acceso denegado</h1>";
        echo "<p>" . htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") . "</p>";
        echo '<a href="/">Volver a la tienda</a>';
    }

    exit;
}
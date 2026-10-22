<?php

require_once __DIR__ . "/../models/usuario.php";

function exigirAdmin(): void
{
    if (!isset($_SESSION["idUsuario"])) {
        http_response_code(401);

        echo json_encode([
            "ok" => false,
            "error" => "Debés iniciar sesión"
        ]);

        exit;
    }

    $usuario = new usuario();

    $rol = $usuario->obtenerRolPorId($_SESSION["idUsuario"]);

    if ($rol !== 'admin') {
        http_response_code(403);

        echo json_encode([
            "ok" => false,
            "error" => "No tenés permiso de administrador"
        ]);

        exit;
    }

}
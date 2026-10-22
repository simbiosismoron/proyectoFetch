<?php
header("Content-Type: application/json; charset=UTF-8");

session_start();

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $parametros = session_get_cookie_params();

    setcookie(session_name(), "", [
        "expires" => time() - 3600,
        "path" => $parametros["path"],
        "domain" => $parametros["domain"],
        "secure" => $parametros["secure"],
        "httponly" => $parametros["httponly"],
        "samesite" => $parametros["samesite"] ?? "Lax"
    ]);
}

session_destroy();

echo json_encode([
    "ok" => true,
    "mensaje" => "Sesión cerrada correctamente"
]);

<?php

header("Content-Type: application/json; charset=UTF-8");

session_start();

if (isset($_SESSION["idUsuario"])) {

    echo json_encode([
        "autenticado" => true,
        "idUsuario" => $_SESSION["idUsuario"],
        "nombre" => $_SESSION["nombre"]
    ]);
} else {

    echo json_encode([
        "autenticado" => false
    ]);
}

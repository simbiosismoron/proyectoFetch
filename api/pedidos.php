<?php

header("Content-Type: application/json; charset=UTF-8");

$datos = json_decode(
    file_get_contents("php://input"),
    true
);

echo json_encode([
    "ok" => true,
    "recibido" => $datos
]);

<?php

session_start ();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../../app/middleware/admin.php";
require_once __DIR__ . "/../../../app/models/producto.php";
require_once __DIR__ . "/../../../app/validators/productoValidator.php";

$metodo = $_SERVER["REQUEST_METHOD"];

if (!in_array($metodo, ["GET", "POST"], true)) {
    header("Allow: GET, POST");
    http_response_code(405);

    echo json_encode([
        "ok" => false,
        "error" => "Método HTTP no permitido"
    ]);

    exit;
}

try {
    exigirAdmin();

    if ($metodo === "GET") {
        $producto = new Producto();

        $productos = $producto->obtenerTodosAdmin();

        echo json_encode($productos);

        exit;
    }

    $datos = json_decode(
        file_get_contents("php://input"), true
    );

    if (!is_array($datos) || array_is_list($datos)) {
        throw new InvalidArgumentException(
            "El objeto no es JSON"
        );
    }

    $datos = validarDatosProducto($datos);

    $datos["foto"] = $foto;

    $producto = new producto();

    $idProducto = $producto->crear($datos);

    http_response_code(201);

    echo json_encode([
        "ok" => true,
        "idProducto" => $idProducto,
        "mensaje" => "Producto creado correctamente"
    ]);


} catch (InvalidArgumentException $e) {
    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);

} catch (PDOException $e) {
    $sqlState = $e->errorInfo[0] ?? null;
    $codigoMariaDB = (int) ($e->errorInfo[1] ?? 0);

    if ($sqlState === "23000" && $codigoMariaDB === 1062) {
        http_response_code(409);

        echo json_encode([
            "ok" => false,
            "error" => "Ya existe un producto con ese código"
        ]);
    } else {
        http_response_code(500);
        error_log($e->getMessage());

        echo json_encode([
            "ok" => false,
            "error" => "Error al guardar el producto"
        ]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());

    echo json_encode([
        "ok" => false,
        "error" => "Error interno del servidor"
    ]);
}
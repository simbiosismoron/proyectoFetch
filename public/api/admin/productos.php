<?php

session_start ();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../../app/middleware/admin.php";
require_once __DIR__ . "/../../../app/models/producto.php";
require_once __DIR__ . "/../../../app/validators/productoValidator.php";

$metodo = $_SERVER["REQUEST_METHOD"];

if (!in_array($metodo, ["GET", "POST", "PUT", "PATCH"], true)) {
    header("Allow: GET, POST, PUT, PATCH");
    
    http_response_code(405);

    echo json_encode([
        "ok" => false,
        "error" => "Método HTTP no permitido"
    ]);

    exit;
}

try {
    exigirAdmin();

    if ($metodo === "PATCH") {
        $datos = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (!is_array($datos) || array_is_list($datos)) {
            throw new InvalidArgumentException(
                "Se esperaba un objeto JSON válido"
            );
        }

        if (
            !isset($datos["idProducto"]) ||
            !is_int($datos["idProducto"]) ||
            $datos["idProducto"] <= 0
        ) {
            throw new InvalidArgumentException(
                "El identificador del producto no es válido"
            );
        }

        if (
            !array_key_exists("activo", $datos) ||
            !is_bool($datos["activo"])
        ) {
            throw new InvalidArgumentException(
                "El estado activo debe ser booleano"
            );
        }

        $producto = new Producto();

        $productoExistente = $producto->obtenerPorId(
            $datos["idProducto"]
        );

        if ($productoExistente === null) {
            http_response_code(404);

            echo json_encode([
                "ok" => false,
                "error" => "El producto no existe"
            ]);

            exit;
        }

        $producto->cambiarEstado(
            $datos["idProducto"],
            $datos["activo"]
        );

        echo json_encode([
            "ok" => true,
            "mensaje" => "Estado del producto actualizado correctamente"
        ]);

        exit;
    }

    if ($metodo === "GET") {
        $producto = new Producto();

        if (isset($_GET["idProducto"])) {
            $idProducto = filter_var(
                $_GET["idProducto"],
                FILTER_VALIDATE_INT,
                ["options" => ["min_range" => 1]]
            );

            if ($idProducto === false) {
                throw new InvalidArgumentException(
                    "El identificador del producto no es válido"
                );
            }

            $productoEncontrado = $producto->obtenerPorId($idProducto);

            if ($productoEncontrado === null) {
                http_response_code(404);

                echo json_encode([
                    "ok" => false,
                    "error" => "El producto no existe"
                ]);

                exit;
            }

            echo json_encode($productoEncontrado);

            exit;
        }

        $productos = $producto->obtenerTodosAdmin();

        echo json_encode($productos);

        exit;
    }

    if ($metodo === "PUT") {

        $datos = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (!is_array($datos) || array_is_list($datos)) {
            throw new InvalidArgumentException(
                "Se esperaba un objeto JSON válido"
            );
        }

        if (
            !isset($datos["idProducto"]) ||
            !is_int($datos["idProducto"]) ||
            $datos["idProducto"] <= 0
        ) {
            throw new InvalidArgumentException(
                "El identificador del producto no es válido"
            );
        }

        $idProducto = $datos["idProducto"];

        $datos = validarDatosProducto($datos);

        $producto = new Producto();

        $productoExistente = $producto->obtenerPorId($idProducto);

        if ($productoExistente === null) {
            http_response_code(404);

            echo json_encode([
                "ok" => false,
                "error" => "El producto no existe"
            ]);

            exit;
        }

        $producto->actualizar($idProducto, $datos);

        echo json_encode([
            "ok" => true,
            "mensaje" => "Producto actualizado correctamente"
        ]);

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
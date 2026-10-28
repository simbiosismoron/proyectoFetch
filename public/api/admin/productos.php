<?php

session_start ();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../../../app/middleware/admin.php";
require_once __DIR__ . "/../../../app/models/producto.php";

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

        $camposObligatorios = [
            "nombre",
            "codigo",
            "idCategoria",
            "descripcion",
            "costo",
            "precio",
            "idProveedor",
            "stock"
        ];

        foreach ($camposObligatorios as $campo) {
            if (!array_key_exists($campo, $datos)) {
                throw new InvalidArgumentException(
                    "Falta el campo obligatorio: $campo"
                );
            }
        }

        $camposTexto = ["nombre", "codigo", "descripcion"];

        foreach ($camposTexto as $campo) {
            if (!is_string($datos[$campo])) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser un texto"
                );
            }

            $datos[$campo] = trim($datos[$campo]);
        }

        if ($datos["nombre"] === "" || $datos["codigo"] === "") {
            throw new InvalidArgumentException(
                "El nombre y el código no pueden estar vacíos"
            );
        }

        $camposEnteros = ["idCategoria", "idProveedor", "stock"];

        foreach ($camposEnteros as $campo) {
            if (!is_int($datos[$campo])) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser un número entero"
                );
            }
        }

        if ($datos["idCategoria"] <= 0 || $datos["idProveedor"] <= 0) {
            throw new InvalidArgumentException(
                "La categoría y el proveedor deben ser válidos"
            );
        }

        if ($datos["stock"] < 0) {
            throw new InvalidArgumentException(
                "El stock no puede ser negativo"
            );
        }

        $camposPrecios = ["costo", "precio"];

        foreach ($camposPrecios as $campo) {
            if (
                !is_int($datos[$campo]) &&
                !is_float($datos[$campo])
            ) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser numérico"
                );
            }

            if (!is_finite((float) $datos[$campo])) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser un número finito"
                );
            }
        }

        if ($datos["costo"] < 0) {
            throw new InvalidArgumentException(
                "El costo no puede ser negativo"
            );
        }

        if ($datos["precio"] <= 0) {
            throw new InvalidArgumentException(
                "El precio debe ser mayor que cero"
            );
        }       

            $foto = $datos["foto"] ?? null;

        if ($foto !== null && !is_string($foto)) {
            throw new InvalidArgumentException(
                "La foto debe ser un texto o null"
            );
        }

        if (is_string($foto)) {
            $foto = trim($foto);

            if ($foto === "") {
                $foto = null;
            }
        }

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
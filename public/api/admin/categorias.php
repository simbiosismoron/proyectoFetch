<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../../../app/middleware/admin.php";
require_once __DIR__ . "/../../../app/models/categoria.php";
$metodo = $_SERVER["REQUEST_METHOD"];
if (!in_array($metodo, ["GET", "POST", "PUT", "DELETE"], true)) {
    header("Allow: GET, POST, PUT, DELETE");
    http_response_code(405);
    echo json_encode(["ok" => false, "error" => "Método no permitido"]);
    exit;
}
exigirAdmin();
try {
    $modelo = new Categoria();
    if ($metodo === "GET") {
        echo json_encode($modelo->listarAdmin());
        exit;
    }
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!is_array($datos) || array_is_list($datos)) throw new InvalidArgumentException("Se requiere un objeto JSON");
    $id = filter_var($datos["idCategoria"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($metodo !== "POST" && $id === false) throw new InvalidArgumentException("ID inválido");
    $campos = [];
    if ($metodo !== "DELETE") {
        $valor = $datos["nombre"] ?? null;
        if (!is_string($valor) || trim($valor) === "" || mb_strlen(trim($valor)) > 255) throw new InvalidArgumentException("Campo nombre inválido");
        $campos["nombre"] = trim($valor);
    }

    if ($metodo === "POST") {
        $nuevoId = $modelo->crear($campos);
        http_response_code(201);
        echo json_encode(["ok" => true, "idCategoria" => $nuevoId, "mensaje" => "Registro creado"]);
        exit;
    }
    if (!$modelo->existe($id)) {
        http_response_code(404);
        echo json_encode(["ok" => false, "error" => "Registro inexistente"]);
        exit;
    }
    if ($metodo === "PUT") {
        $modelo->actualizar($id, $campos);
        echo json_encode(["ok" => true, "mensaje" => "Registro actualizado"]);
    } else {
        $modelo->eliminar($id);
        echo json_encode(["ok" => true, "mensaje" => "Registro eliminado"]);
    }
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
} catch (DomainException $e) {
    http_response_code(409);
    echo json_encode(["ok" => false, "error" => $e->getMessage()]);
} catch (PDOException $e) {
    $code = (int) ($e->errorInfo[1] ?? 0);
    http_response_code(in_array($code, [1062, 1451], true) ? 409 : 500);
    error_log($e->getMessage());
    echo json_encode(["ok" => false, "error" => $code === 1062 ? "Ya existe un registro con esos datos" : ($code === 1451 ? "Existen registros asociados" : "Error de base de datos")]);
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(["ok" => false, "error" => "Error interno"]);
}

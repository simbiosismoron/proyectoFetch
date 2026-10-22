<?php

require_once __DIR__ . "/../../config/database.php";


class Producto
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT * FROM productos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function crear(array $datos): int
{
    $sql = "INSERT INTO productos (
                nombre,
                codigo,
                idCategoria,
                descripcion,
                costo,
                precio,
                foto,
                idProveedor,
                stock
            ) VALUES (
                :nombre,
                :codigo,
                :idCategoria,
                :descripcion,
                :costo,
                :precio,
                :foto,
                :idProveedor,
                :stock
            )";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
        "nombre" => $datos["nombre"],
        "codigo" => $datos["codigo"],
        "idCategoria" => $datos["idCategoria"],
        "descripcion" => $datos["descripcion"],
        "costo" => $datos["costo"],
        "precio" => $datos["precio"],
        "foto" => $datos["foto"] ?? null,
        "idProveedor" => $datos["idProveedor"],
        "stock" => $datos["stock"]
    ]);

    return (int) $this->conn->lastInsertId();
}
}

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

    public function obtenerTodosAdmin(): array
    {   
        $sql = "SELECT
                    idProducto,
                    codigo,
                    nombre,
                    idCategoria,
                    idProveedor,
                    costo,
                    precio,
                    stock
                FROM productos
                ORDER BY idProducto DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT foto, nombre, descripcion, precio, stock, idProducto FROM productos";
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
    

    public function obtenerPorId(int $idProducto): ?array
    {
        $sql = "SELECT *
                FROM productos
                WHERE idProducto = :idProducto";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "idProducto" => $idProducto
        ]);

        $producto = $stmt->fetch();

        return $producto === false ? null : $producto;
    }

    public function actualizar(int $idProducto, array $datos): bool
    {
        $sql = "UPDATE productos
                SET
                    nombre = :nombre,
                    codigo = :codigo,
                    idCategoria = :idCategoria,
                    descripcion = :descripcion,
                    costo = :costo,
                    precio = :precio,
                    foto = :foto,
                    idProveedor = :idProveedor,
                    stock = :stock
                WHERE idProducto = :idProducto";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "idProducto" => $idProducto,
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
    }
}

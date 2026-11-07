<?php

require_once __DIR__ . "/../../config/database.php";

class Proveedor
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT idProveedor, nombre
                FROM proveedores
                ORDER BY nombre ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }
    public function listarAdmin(): array {
        return $this->conn->query("SELECT idProveedor, nombre, cuit, razonSocial FROM proveedores ORDER BY nombre")->fetchAll();
    }
    public function crear(array $datos): int {
        $stmt = $this->conn->prepare("INSERT INTO proveedores (nombre, cuit, razonSocial) VALUES (:nombre, :cuit, :razonSocial)");
        $stmt->execute($datos);
        return (int) $this->conn->lastInsertId();
    }
    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->conn->prepare("UPDATE proveedores SET nombre = :nombre, cuit = :cuit, razonSocial = :razonSocial WHERE idProveedor = :id");
        $stmt->execute([...$datos, "id" => $id]);
        return $stmt->rowCount() > 0;
    }
    public function existe(int $id): bool {
        $stmt = $this->conn->prepare("SELECT 1 FROM proveedores WHERE idProveedor = ?");
        $stmt->execute([$id]);
        return (bool) $stmt->fetchColumn();
    }
    public function eliminar(int $id): bool {
        $stmt = $this->conn->prepare("SELECT 1 FROM productos WHERE idProveedor = ? LIMIT 1");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn()) throw new DomainException("No se puede eliminar: existen registros asociados");
        $stmt = $this->conn->prepare("DELETE FROM proveedores WHERE idProveedor = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}

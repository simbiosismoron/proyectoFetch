<?php

require_once __DIR__ . "/../../config/database.php";

class Categoria
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodas(): array
    {
        $sql = "SELECT idCategoria, nombre
                FROM categorias
                ORDER BY nombre ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }
    public function listarAdmin(): array {
        return $this->conn->query("SELECT idCategoria, nombre FROM categorias ORDER BY nombre")->fetchAll();
    }
    public function crear(array $datos): int {
        $stmt = $this->conn->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
        $stmt->execute($datos);
        return (int) $this->conn->lastInsertId();
    }
    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->conn->prepare("UPDATE categorias SET nombre = :nombre WHERE idCategoria = :id");
        $stmt->execute([...$datos, "id" => $id]);
        return $stmt->rowCount() > 0;
    }
    public function existe(int $id): bool {
        $stmt = $this->conn->prepare("SELECT 1 FROM categorias WHERE idCategoria = ?");
        $stmt->execute([$id]);
        return (bool) $stmt->fetchColumn();
    }
    public function eliminar(int $id): bool {
        $stmt = $this->conn->prepare("SELECT 1 FROM productos WHERE idCategoria = ? LIMIT 1");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn()) throw new DomainException("No se puede eliminar: existen registros asociados");
        $stmt = $this->conn->prepare("SELECT 1 FROM categorias WHERE idCategoriaPadre = ? LIMIT 1");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn()) throw new DomainException("No se puede eliminar: existen registros asociados");
        $stmt = $this->conn->prepare("DELETE FROM categorias WHERE idCategoria = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}

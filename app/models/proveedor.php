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
}
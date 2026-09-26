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
}

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
}



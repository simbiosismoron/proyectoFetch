<?php

require_once __DIR__ . "/../../config/database.php";

class Estadistica
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerResumenVentas(): array
    {
        $sql = "SELECT
                    COUNT(*) AS cantidadPedidos,
                    COALESCE(SUM(total), 0) AS importePedidos,
                    COALESCE(AVG(total), 0) AS ticketPromedio
                FROM ventas";

        $stmt = $this->conn->query($sql);   

        return $stmt->fetch();
    }

    public function obtenerCantidadVendida(): array
    {
        $sql = "SELECT
        COALESCE(SUM(cantidad), 0) AS unidadesVendidas 
        FROM items";

        $stmt = $this->conn->query($sql);

        return $stmt->fetch();

    }
}
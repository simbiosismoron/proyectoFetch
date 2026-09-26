<?php

class Database
{
    private string $host = "localhost";
    private string $db_name = "ppampa";
    private string $username = "root";
    private string $password = "";

    private ?PDO $conn = null;
    public function getConnection(): ?PDO
    {
        try {

            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->conn->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $this->conn;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
}

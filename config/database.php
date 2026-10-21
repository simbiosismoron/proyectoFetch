<?php

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

class Database
{
    private ?PDO $conn = null;

    public function getConnection(): ?PDO
    {
        try {
            $host = $_ENV["DB_HOST"];
            $db_name = $_ENV["DB_NAME"];
            $username = $_ENV["DB_USER"];
            $password = $_ENV["DB_PASSWORD"];

            $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";

            $this->conn = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            return $this->conn;

        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }
    }
}

<?php

require_once __DIR__ . "/../../config/database.php";

class usuario
{

    private PDO $conn;

    public function __construct()
    {
        $database = new database();
        $this->conn = $database->getConnection();
    }

    public function registrar($nombre, $email, $password)
    {

        $nombre = trim($nombre);
        $email = trim($email);

        if (strlen($nombre) < 3 || strlen($nombre) > 100) {
            throw new InvalidArgumentException(
                "El nombre debe tener entre 3 y 100 caracteres"
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                "El correo electrónico no es válido"
            );
        }

        if (strlen($password) < 8) {
            throw new InvalidArgumentException(
                "La contraseña debe tener al menos 8 caracteres"
            );
        }


        $sql1 = "SELECT * FROM usuarios where email = :email";

        $stmt1 = $this->conn->prepare($sql1);

        $stmt1->execute([
            "email" => $email
        ]);



        $sql = "INSERT INTO usuarios (nombre, email, password)
        VALUES (:nombre, :email, :password)";

        $stmt = $this->conn->prepare($sql);

        $usuarioexistente = $stmt1->fetch();

        if ($usuarioexistente) {
            throw new InvalidArgumentException(
                "El correo ya está registrado"
            );
        };

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt->execute([
            "nombre" => $nombre,
            "email" => $email,
            "password" => $passwordHash
        ]);

        $idusuario = $this->conn->lastInsertId();

        return [
            "ok" => true,
            "idusuario" => (int) $idusuario
        ];
    }

    public function iniciarSesion($email, $password)
    {

        $sql = "SELECT idUsuario, nombre, email, rol, password
        FROM usuarios
        WHERE email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "email" => $email
        ]);

        $usuario = $stmt->fetch();

        if (!$usuario) {
            return false;
        }
    
        if (!password_verify($password, $usuario["password"])) {
            return false;
        }

        return [
            "idUsuario" => (int) $usuario["idUsuario"],
            "nombre" => $usuario["nombre"],
            "email" => $usuario["email"],
            "rol" => $usuario["rol"]
        ];
    }

    public function obtenerRolPorId(int $idUsuario): ?string 
    {

        $sql = "SELECT rol 
        FROM usuarios
        WHERE idUsuario = :idUsuario";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "idUsuario" => $idUsuario
        ]);

        $rol = $stmt->fetchColumn();

        return $rol === false ? null : $rol;

    }
}

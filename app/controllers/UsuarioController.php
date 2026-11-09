<?php

require_once __DIR__ . "/../models/usuario.php";

class UsuarioController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function registrar($datos): array
    {
        if (
            !is_array($datos) ||
            !isset($datos["nombre"], $datos["correo"], $datos["password"])
        ) {
            throw new InvalidArgumentException("Faltan datos del registro");
        }

        if (
            !is_string($datos["nombre"]) ||
            !is_string($datos["correo"]) ||
            !is_string($datos["password"])
        ) {
            throw new InvalidArgumentException("Los datos del registro deben ser texto");
        }

        return $this->usuario->registrar(
            $datos["nombre"],
            $datos["correo"],
            $datos["password"]
        );
    }

    public function iniciarSesion($datos): array|false
    {
        if (
            !is_array($datos) ||
            !isset($datos["email"], $datos["password"]) ||
            !is_string($datos["email"]) ||
            !is_string($datos["password"])
        ) {
            throw new InvalidArgumentException("Datos de inicio de sesión inválidos");
        }

        return $this->usuario->iniciarSesion(
            $datos["email"],
            $datos["password"]
        );
    }
}

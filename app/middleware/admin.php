<?php

require_once __DIR__ . "/../models/usuario.php";

function exigirAdmin(bool $respuestaJSON = true): void
{
    if (!isset($_SESSION["idUsuario"])) {
        responderErrorAdmin(
            401,
            "Debés iniciar sesión",
            $respuestaJSON
        );
    }

    $usuario = new usuario();

    $rol = $usuario->obtenerRolPorId(
        (int) $_SESSION["idUsuario"]
    );

    if ($rol !== "admin") {
        responderErrorAdmin(
            403,
            "No tenés permiso de administrador",
            $respuestaJSON
        );
    }
}


function responderErrorAdmin(
    int $codigo,
    string $mensaje,
    bool $respuestaJSON
): void {
    http_response_code($codigo);

    if ($respuestaJSON) {
        header("Content-Type: application/json; charset=UTF-8");

        echo json_encode([
            "ok" => false,
            "error" => $mensaje
        ]);
    } else {
        header("Content-Type: text/html; charset=UTF-8");

        $mensajeSeguro = htmlspecialchars(
            $mensaje,
            ENT_QUOTES,
            "UTF-8"
        );

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Acceso denegado | Pampa</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >

            <style>
                body {
                    background: #f5f6f8;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: system-ui, sans-serif;
                }

                .error-card {
                    max-width: 460px;
                    width: 100%;
                    background: #fff;
                    padding: 48px 36px;
                    border-radius: 18px;
                    border: 1px solid #e9ecef;
                    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.05);
                    text-align: center;
                }

                .error-code {
                    font-size: 5rem;
                    font-weight: 800;
                    letter-spacing: -4px;
                    color: #dc3545;
                    line-height: 1;
                    margin-bottom: 20px;
                }

                .error-title {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: #212529;
                    margin-bottom: 12px;
                }

                .error-description {
                    color: #6c757d;
                    line-height: 1.6;
                    margin-bottom: 28px;
                }

                .btn-return {
                    padding: 12px 24px;
                    border-radius: 10px;
                    font-weight: 600;
                }
            </style>
        </head>

        <body>
            <main class="container d-flex justify-content-center px-3">
                <div class="error-card">
                    <div class="error-code">{$codigo}</div>

                    <h1 class="error-title">Acceso denegado</h1>

                    <p class="error-description">
                        {$mensajeSeguro}
                    </p>

                    <a href="/" class="btn btn-dark btn-return">
                        Volver a la tienda
                    </a>
                </div>
            </main>
        </body>
        </html>
        HTML;
    }

    exit;
}

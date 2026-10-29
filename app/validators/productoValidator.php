<?php

function validarDatosProducto(array $datos): array
{
    $camposObligatorios = [
            "nombre",
            "codigo",
            "idCategoria",
            "descripcion",
            "costo",
            "precio",
            "idProveedor",
            "stock"
        ];

        foreach ($camposObligatorios as $campo) {
            if (!array_key_exists($campo, $datos)) {
                throw new InvalidArgumentException(
                    "Falta el campo obligatorio: $campo"
                );
            }
        }

        $camposTexto = ["nombre", "codigo", "descripcion"];

        foreach ($camposTexto as $campo) {
            if (!is_string($datos[$campo])) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser un texto"
                );
            }

            $datos[$campo] = trim($datos[$campo]);
        }

        if ($datos["nombre"] === "" || $datos["codigo"] === "") {
            throw new InvalidArgumentException(
                "El nombre y el código no pueden estar vacíos"
            );
        }

        $camposEnteros = ["idCategoria", "idProveedor", "stock"];

        foreach ($camposEnteros as $campo) {
            if (!is_int($datos[$campo])) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser un número entero"
                );
            }
        }

        if ($datos["idCategoria"] <= 0 || $datos["idProveedor"] <= 0) {
            throw new InvalidArgumentException(
                "La categoría y el proveedor deben ser válidos"
            );
        }

        if ($datos["stock"] < 0) {
            throw new InvalidArgumentException(
                "El stock no puede ser negativo"
            );
        }

        $camposPrecios = ["costo", "precio"];

        foreach ($camposPrecios as $campo) {
            if (
                !is_int($datos[$campo]) &&
                !is_float($datos[$campo])
            ) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser numérico"
                );
            }

            if (!is_finite((float) $datos[$campo])) {
                throw new InvalidArgumentException(
                    "El campo $campo debe ser un número finito"
                );
            }
        }

        if ($datos["costo"] < 0) {
            throw new InvalidArgumentException(
                "El costo no puede ser negativo"
            );
        }

        if ($datos["precio"] <= 0) {
            throw new InvalidArgumentException(
                "El precio debe ser mayor que cero"
            );
        }       

            $foto = $datos["foto"] ?? null;

        if ($foto !== null && !is_string($foto)) {
            throw new InvalidArgumentException(
                "La foto debe ser un texto o null"
            );
        }

        if (is_string($foto)) {
            $foto = trim($foto);

            if ($foto === "") {
                $foto = null;
            }
        }
    return $datos;
}
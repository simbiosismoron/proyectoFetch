<?php

require_once __DIR__ . "/../config/database.php";

class pedido
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function realizarPedido($idUsuario, $productos)
    {

        foreach ($productos as $producto) {

            $idProducto = $producto['idProducto'];

            $sql = "SELECT * FROM productos WHERE idProducto = :idProducto";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'idProducto' => $idProducto
            ]);

            $productobd = $stmt->fetch();
            if (!$productobd) {
                throw new Exception("Producto no encontrado: " . $idProducto);
            }

            $cantidad = (float) $producto['cantidad'];

            if ($cantidad < 0) {
                throw new Exception("Cantidad no válida para el producto: " . $idProducto);
            }
            if ($cantidad > $productobd['stock']) {
                throw new Exception("Cantidad solicitada excede el stock disponible para el producto: " . $idProducto);
            }
        }
    }
}

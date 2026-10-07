<?php

require_once __DIR__ . "/../../config/database.php";

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
        $total = 0;
        $itemsvalidados = [];

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

            $precio = (float) $productobd['precio'];
            $subTotal = $cantidad * $precio;
            $total += $subTotal;
            $itemsvalidados[] = [
                'idProducto' => $idProducto,
                'cantidad' => $cantidad,
                'precio' => $precio,
                'subTotal' => $subTotal
            ];
        }

        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO ventas (idUsuario, fecha, total, estadoPago, estadoEnvio)
            values (:idUsuario, NOW(), :total, :estadoPago, :estadoEnvio)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'idUsuario' => $idUsuario,
                'total' => $total,
                'estadoPago' => 'p',
                'estadoEnvio' => 'p'
            ]);

            $idventa = $this->conn->lastInsertId();

            $sqlItem = "INSERT INTO items
            (idVenta, idProducto, cantidad, precio, subtotal)
            VALUES
            (:idVenta, :idProducto, :cantidad, :precio, :subtotal)";

            $stmtItem = $this->conn->prepare($sqlItem);

            foreach ($itemsvalidados as $item) {
                $stmtItem->execute([
                    'idVenta' => $idventa,
                    'idProducto' => $item['idProducto'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subTotal']
                ]);
            }

            $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE idProducto = :idProducto";
            $stmtStock = $this->conn->prepare($sqlStock);
            foreach ($itemsvalidados as $item) {
                $stmtStock->execute([
                    'cantidad' => $item['cantidad'],
                    'idProducto' => $item['idProducto']
                ]);
            }

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
        return [
            "ok" => true,
            "idVenta" => $idventa,
            "total" => $total
        ];
    }
}
